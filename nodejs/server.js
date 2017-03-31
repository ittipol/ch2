var socket = require( 'socket.io' );
var express = require( 'express' );
var http = require( 'http' );
var app = express();
var server = http.createServer( app );
var io = socket.listen( server );

var db = require('./db');

io.sockets.on('connection', function(socket){

  socket.on('subscribe', function(room) { 
      socket.join(room); 
      // io.sockets.in(room).emit('user joined', room);
  })

  socket.on('unsubscribe', function(room) {  
      socket.leave(room); 
  })

  socket.on('check-notification', function(data) {

    db.query('SELECT COUNT(id) AS count FROM `people` WHERE `id` = '+data.person+' AND `token` = "'+data.token+'"', function(err, rows){
      if (err) {
        io.sockets.in(data.room).emit('update-notification', { err:err });
      }else{
        checkNotification(data);
      }
    });

  });

});

// io.sockets.on('connection', function (socket) {
//   socket.on('disconnect', function () {
//     console.log('User Disconnected');
//     console.log(socket.id);
//   });
// });

server.listen( 8080 );

function checkNotification(data) {

  db.query('SELECT id FROM `notifications` WHERE (`receiver` = "Person" AND `receiver_id` = '+data.person+') AND `notify` = 1 ORDER BY id ASC LIMIT 1', function(err, rows) {
    if (err) {
      io.sockets.in(data.room).emit('update-notification', { err:err });
    }else{

      if(rows.length > 0) {

        for (id in rows) {
          db.query('UPDATE notifications SET notify = 2 WHERE id = '+rows[id].id);
        }
        
        io.sockets.in(data.room).emit('update-notification', { updated:1 });
      }

    }
    
  });

}