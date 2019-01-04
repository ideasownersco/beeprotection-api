var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');
var redis = new Redis({
  'password' : 'redispassword',
  'host' : '10.136.32.151',
  'port' : 6379
});

/** REDIS */
redis.psubscribe('*');

redis.on('pmessage', function (subscribed, channel, payload) {
  payload = JSON.parse(payload);
  payload.channel = channel;
  io.sockets.in(channel).emit(payload.event, payload.data);
});

var users = [];

/** SOCKET */

io.on('connection', function (socket) {
  socket.on('user.connected', function (userID) {
    users.indexOf(userID) === -1 && users.push(userID);
    socket.user = userID;
  });

  socket.on('job.track.subscribe', function (jobID) {
    if (jobID) {
      socket.join('job.track.' + jobID);
    }
  });

  socket.on('track.drivers.subscribe', function () {
    socket.join('track.drivers');
  });

  socket.on('customer.track.drivers.subscribe', function () {
    socket.join('customer.track.drivers');
  });

  socket.on('disconnect', function () {
    var index = users.indexOf(socket.user);
    if (index > -1) {
      users.splice(index, 1);
    }
  });
});

http.listen(3000);
