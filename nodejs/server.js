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

    db.query('SELECT * FROM notifications WHERE notify = 1 ORDER BY id ASC LIMIT 1', function(err, rows) {
      if (err) {
          // throw err;
        io.sockets.in(data.room).emit('update-notification', { err:err });
        return false;
      }

      if(rows.length > 0) {

        for (id in rows) {
           db.query('UPDATE notifications SET notify = 0 WHERE id = '+rows[id].id);
           io.sockets.in(data.room).emit('update-notification', { id: rows[id].id, title: rows[id].title, message: rows[id].message});
        }

        io.sockets.in(data.room).emit('count-notification', { count: rows.length });
      }

      // if(rows.length > 0) {
      //   db.query('SELECT COUNT(unread) AS count FROM notifications WHERE unread = 1', function(err, rows) {
      //     io.sockets.in(data.room).emit('count-notification', { count: rows[0].count });
      //   });
      // }
      
    });

  });

});

io.sockets.on('connection', function (socket) {
  socket.on('disconnect', function () {
    console.log('User Disconnected');
    console.log(socket.id);
  });
});

server.listen( 8080 );