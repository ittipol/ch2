var socket = io.connect( 'http://localhost:8080' );

let room = 'roomOne';

// $( "#btn" ).click( function() {
// 	var nameVal = $( "#nameInput" ).val();
// 	var msg = $( "#messageInput" ).val();
	
// 	socket.emit( 'push-notification', { name: nameVal, message: msg, room: room } );
	
// 	return false;
// });

// setInterval(function(){
// 	socket.emit( 'push-notification', { name: 'xxx', message: 'desc', room: room } );
// },1000);

// socket.on( 'push-notification', function( data ) {

// 	console.log(data);

// 	var actualContent = $( "#messages" ).html();
// 	var newMsgContent = '<li> <strong>' + data.name + '</strong> : ' + data.message + '</li>';
// 	var content = newMsgContent + actualContent;
	
// 	$( "#messages" ).html( content );
// });

$( "#btn" ).click( function() {
	console.log('subscribe');
	socket.emit('subscribe', room);	
});

setInterval(function(){
	console.log('x');
	socket.emit('send', { room: room, message: 'test socket' });
},1000);

socket.on( 'send', function( data ) {
	console.log('aaa');
});