import can from 'can';
import 'can/map/define/';
import config from 'config';

export default can.Map.extend({
  define: {
    loadedData: {
      value: false
    },
    notifications: {
      value:[],
      serialize: false,
      set(newVal){
        if (newVal) this.attr("notificationCount", newVal.filter((elem) => !elem.seen).length);
        return newVal
      }
    },
    notificationCount: {
      serialize: false
    },
    init: {
      get() {
        if (config.webSockets) {
          this.ws_notifications = new WebSocket(config.server.sockets + 'notifications');
          this.ws_notifications.onopen = () => {
            this.ws_notifications.onmessage = (e) => {
              let data = JSON.parse(e.data);
              if ($.isArray(data)) {
                data.forEach((eel) => {
                  if (!this.attr("notifications").filter((elem) => elem.id == eel.id).length) {
                    this.attr("notifications").push(eel);
                    this.attr("notificationCount", this.attr("notifications").filter((elem) => !elem.seen).length);
                  }
                });
              }
            };
            var sendObj = restAPI.signObj(config.server.sockets + 'notifications', {"action": "sub"}, Math.round(new Date().getTime() / 1000));
            this.ws_notifications.send(JSON.stringify(sendObj));
          };
          return null;
        }
      }
    }
  },
  removeNotification(notification) {
    notification.attr("seen", true);
    notification.destroy();
    this.attr("notificationCount", this.attr("notifications").filter((elem) => !elem.seen).length);
    return false;
  },
  openNotification(notification) {
    notification.attr("seen", true);
    notification.save();
    this.attr("notificationCount", this.attr("notifications").filter((elem) => !elem.seen).length);
    return false;
  }
});