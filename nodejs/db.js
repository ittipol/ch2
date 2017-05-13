var mysql = require('mysql');
var connection = mysql.createConnection({
    host     : '127.0.0.1',
    user     : 'root',
    password : 'ss1q2w3e4rzz',
    database : 'sundaysquare'
});

connection.connect(function(err) {
    if (err) throw err;
});

module.exports = connection;