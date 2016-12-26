import 'can/map/define/';
import restAPI from 'rest-api';

let AdminNotifyModel = can.Model.extend({
  findAll(attrs) {
    return restAPI.requestPromise('GET', '/wapi/admin-notify/' + attrs.userID + '/', {});
  },
  create(attrs) {
    return restAPI.requestPromise('POST', '/wapi/admin-notify/' + attrs.userID + '/', attrs);
  },
  update(id, attrs) {
    return restAPI.requestPromise('POST', '/wapi/admin-notify/' + attrs.userID + '/' + id + '/', attrs);
  },
  destroy(id, attrs) {
    return restAPI.requestPromise('DELETE', '/wapi/admin-notify/' + attrs.userID + '/' + id + '/', {});
  }
}, {
  define: {
    inputDisabled: {
      value: false,
      serialize: false
    },
    id: {
      value: null,
      type: 'number'
    },
    userID: {
      value: null,
      type: 'number'
    },
    title: {
      value: '',
      type: 'string'
    },
    text: {
      value: '',
      type: 'string'
    }
  }
});

export default AdminNotifyModel;
