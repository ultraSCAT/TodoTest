const setup = async (socketio) => {
    socketio.on("connection", socket => {        
        console.log("Nodo conectado...");
        socket.on("event", (data) => {
            console.log(data);
            if(data.type) socket.broadcast.emit(data.type,data.task)
        });
        socket.on("disconnect", async () => {
            console.log("Nodo desconectado...");
        });
    });
}

module.exports = {
    setup: setup
};