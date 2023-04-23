const express = require("express");
const http = require("http");
const app = express();
const server = http.createServer(app);
const socketio = require("socket.io")(server,{
    cors: {
        origins: ['http://*']
    }
});

const socketEvents = require("./socketEvents");

socketEvents.setup(socketio);

server.listen(4000,()=>{
    console.log('Listening on port 4000');
});