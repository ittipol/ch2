var socket = io.connect( 'http://localhost:8080' );


setInterval(function(){
	socket.emit( 'send message', { name: 'xxx', message: 'desc', room: 111 } );
},1000);

socket.on( 'send message', function( data ) {
	// return here
});

// socket.on('conversation private post', function(data) {
//     //display data.message
//     console.log('mmm');
// });