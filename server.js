/*jshint esversion: 6 */

var app = require('http').createServer();

/*var app = require('http').createServer(function(req,res){
// Set CORS headers
    res.setHeader('Access-Control-Allow-Origin', 'http://---your-base-url---');
    res.setHeader('Access-Control-Request-Method', '*');
    res.setHeader('Access-Control-Allow-Methods', 'UPGRADE, OPTIONS, GET');
    res.setHeader('Access-Control-Allow-Credentials', true);
    res.setHeader('Access-Control-Allow-Headers', req.header.origin);
    if ( req.method === 'OPTIONS' || req.method === 'UPGRADE' ) {
        res.writeHead(200);
        res.end();
        return;
    }
});*/

// Se tiverem problemas com "same-origin policy" deverão activar o CORS.

// Aqui, temos um exemplo de código que ativa o CORS (alterar o url base) 

// var app = require('http').createServer(function(req,res){
// Set CORS headers
//  res.setHeader('Access-Control-Allow-Origin', 'http://---your-base-url---');
//  res.setHeader('Access-Control-Request-Method', '*');
//  res.setHeader('Access-Control-Allow-Methods', 'UPGRADE, OPTIONS, GET');
//  res.setHeader('Access-Control-Allow-Credentials', true);
//  res.setHeader('Access-Control-Allow-Headers', req.header.origin);
//  if ( req.method === 'OPTIONS' || req.method === 'UPGRADE' ) {
//      res.writeHead(200);
//      res.end();
//      return;
//  }
// });

// NOTA: A solução correta depende da configuração do próprio servidor, 
// e alguns casos do próprio browser.
// Assim sendo, não se garante que a solução anterior funcione.
// Caso não funcione é necessário procurar/investigar soluções alternativas

var io = require('socket.io')(app);

app.listen(8080, function(){
    console.log('listening on *:8080');
});

// ------------------------
// Estrutura dados - server
// ------------------------

io.on('connection', function (socket) {
    console.log('client has connected (socket ID = '+socket.id+')' );

    // Emit message to the same cliente 
    // socket.emit('my_active_games_changed');

    // Handle message sent from the client to the server
    // socket.on('messageType_from_client_to_server', function (data){

    // });

	socket.on('joinCook', function() {
	    socket.join('cook');
        console.log('client ' + socket.id + ' has joined "cook"');
    });

    socket.on('leaveCook', function() {
        socket.leave('cook');
        console.log('client ' + socket.id + ' has left "cook"');
    });

	socket.on('joinWaiter', function() {
	    socket.join('waiter');
        console.log('client ' + socket.id + ' has joined "waiter"');
    });

    socket.on('leaveWaiter', function() {
        socket.leave('waiter');
        console.log('client ' + socket.id + ' has left "waiter"');
    });

	socket.on('joinCashier', function() {
	    socket.join('cashier');
        console.log('client ' + socket.id + ' has joined "cashier"');
    });

    socket.on('leaveCashier', function() {
        socket.leave('cashier');
        console.log('client ' + socket.id + ' has left "cashier"');
    });

	socket.on('propagateCookOrder', function(order) {
	    socket.broadcast.to('cook').emit('propagateCookOrder', order);
    });

	socket.on('propagateWaiterDeliveries', function() {
	    socket.broadcast.to('waiter').emit('propagateWaiterDeliveries');
    });

	socket.on('propagateTerminateOrder', function() {
	    socket.broadcast.to('waiter').emit('propagateTerminateOrder');
	    socket.broadcast.to('cashier').emit('propagateTerminateOrder');
    });

	/*socket.on('msg_from_client', (msg, userInfo) => {
		if (userInfo === undefined) {
			io.sockets.emit('msg_from_server', 'User Unknown: "' + msg + '"');
		} else {
			io.sockets.emit('msg_from_server', userInfo.name +': "' + msg + '"');
		}
	});*/
});
