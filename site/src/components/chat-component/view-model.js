import can from 'can';
import 'can/map/define/';
import config from 'config';
import i18n from 'easyapp/i18n/i18n';

export default can.Map.extend({
  define: {
    loadedData: {
      value: false
    },
    messageData: {
      value: [],
    },
    newMessage: {
      value: false,
      type: 'boolean'
    },
    init: {
      get() {
        this.attr("setUsername", (typeof localStorage !== 'undefined' && localStorage.getItem('authUser') ? localStorage.getItem('authUser') : (typeof sessionStorage !== 'undefined' && sessionStorage.getItem('authUser') ? sessionStorage.getItem('authUser') : 'Error' )));

        if (config.webSockets) {
          this.ws_chat = new WebSocket(config.server.sockets + 'chat');
          this.ws_chat.onopen = () => {
            this.ws_chat.onmessage = (e) => {
              this.addObject(JSON.parse(e.data));
              if (!$('#chat-dropdown').is(':visible')) this.attr("newMessage", true);
            };
            var obj = {
              action: "join",
              username: this.attr("setUsername"),
              dateTime: Math.round(new Date().getTime() / 1000)
            };
            var sendObj = restAPI.signObj(config.server.sockets + 'chat', obj, obj.dateTime);
            this.ws_chat.send(JSON.stringify(sendObj));
          };
          return null;
        }
      }
    }
  },
  sendMessage() {
    if (this.attr("message").length != 0) {
      var obj = {
        action: 'message',
        message: this.attr("message"),
        username: this.attr("setUsername"),
        dateTime: Math.round(new Date().getTime() / 1000)
      };
      var sendObj = restAPI.signObj(config.server.sockets + 'chat', obj, obj.dateTime);
      this.ws_chat.send(JSON.stringify(sendObj));
      this.attr("message", '');
    }
  },
  addObject(obj) {
    if (this.attr("messageData").length > 15) {
      this.attr("messageData").shift();
    }
    if (obj.action == 'join') obj.message = i18n.chatComponentJoin;
    if (obj.action == 'left') obj.message = i18n.chatComponentLeft;
    this.attr("messageData").push(obj);

    setTimeout(() => $(".chat-window").animate({ scrollTop: $(".chat-window").height() }, "fast"), 0);
  },
  readMessages() {
    if (!$('#chat-dropdown').is(':visible')) {
      if (this.attr("newMessage")) setTimeout(() => this.attr("newMessage", false), 0);
      setTimeout(() => $(".chat-window").animate({ scrollTop: $(".chat-window").height() }, "fast"), 0);
    }
    return true;
  }
});