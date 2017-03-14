var socket = require( 'socket.io' );
var express = require( 'express' );
var http = require( 'http' );
var app = express();
var server = http.createServer( app );
var io = socket.listen( server );

var db = require('./db');

io.sockets.on('connection', function(socket){

	console.log('connected');

    socket.on('subscribe', function(room) { 
        console.log('joining room', room);
        socket.join(room); 
        // io.sockets.in(room).emit('user joined', room);
    })

    socket.on('unsubscribe', function(room) {  
        console.log('leaving room', room);
        socket.leave(room); 
    })

    socket.on('send', function(data) {
        // console.log('sending message');
        // console.log('room: '+data.room);
        io.sockets.in(data.room).emit('send', 'EEEEEEEEEEEEEEEEEEEEEEEE');
    });
});

io.sockets.on('connection', function (socket) {
    socket.on('disconnect', function () {
    	console.log('Disconnected!!!');
      console.log(socket.id);
    });
});


server.listen( 8080 );