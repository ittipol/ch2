var socket = require( 'socket.io' );
var express = require( 'express' );
var http = require( 'http' );

var db = require('./db');

var app = express();
var server = http.createServer( app );

var io = socket.listen( server );

io.sockets.on( 'connection', function( client ) {
	console.log( "New client !" );
	
	client.on( 'send message', function( data ) {

		  db.query('SELECT * FROM notifications WHERE unread = 1', function(err, rows) {
		    if (err) {
		        throw err;
		    }
		    for (id in rows) {
		       io.sockets.emit( 'send message', { name: rows[id].id, message: rows[id].name } );

		       db.query('UPDATE notifications SET unread = 0 WHERE unread = 1', function(err, rows) {});

		    }
			}); 
		
		// client.broadcast.emit( 'send message', { name: data.name, message: data.message } );
		// io.sockets.emit( 'send message', { name: data.name, message: data.message } );

		// client.broadcast.to(data.room).emit('conversation private post', {
  //       message: data.message
  //   });

	});
});

server.listen( 8080 );