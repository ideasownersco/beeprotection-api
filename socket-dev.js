var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var middleware = require('socketio-wildcard')();
var Redis = require('ioredis');
var redis = new Redis({
  'password' : 'redispassword',
  'host' : '10.136.32.151',
  'port' : 6379
});

/** REDIS */
redis.psubscribe('*', function (err, count) {
  console.log('subscribing to redis');
  if (err) {
    console.log('Redis could not subscribe.', err);
  }
});

redis.on('pmessage', function (subscribed, channel, payload) {
  console.log('new message on channel', channel);
  payload = JSON.parse(payload);
  payload.channel = channel;
  io.sockets.in(channel).emit(payload.event, payload.data);
});

redis.on("error", function (err) {
  console.log('redis error', err);
});

var users = [];

/** SOCKET */

io.on('connection', function (socket) {
  console.log('connected',socket.id);

  socket.on('*', function(packet){
    console.log('packet',packet);
  });

  socket.on('user.connected', function (userID) {
    users.indexOf(userID) === -1 && users.push(userID);
    socket.user = userID;
  });

  socket.on('job.track.subscribe', function (jobID) {
    console.log('job.track.subscribe jobID', jobID);
    if (jobID) {
      socket.join('job.track.' + jobID);
    }
  });

  socket.on('track.drivers.subscribe', function () {
    console.log('track.drivers.subscribe');
    socket.join('track.drivers');
  });

  socket.on('customer.track.drivers.subscribe', function () {
    console.log('customer.track.drivers.subscribe');
    socket.join('customer.track.drivers');
  });

  socket.on('disconnect', function () {
    console.log('disconnected');
    var index = users.indexOf(socket.user);
    if (index > -1) {
      users.splice(index, 1);
    }
  });

  socket.on('error',function(socket){
    console.log('error',socket);
  });
});

http.listen(3000, function () {
  console.log('listening on *:3000');
});
