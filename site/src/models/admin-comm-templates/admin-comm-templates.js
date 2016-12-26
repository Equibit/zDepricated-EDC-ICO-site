import 'can/map/define/';
import restAPI from 'rest-api';

let AdminCommTemplatesModel = can.Model.extend({
  findAll(){
    return restAPI.requestPromise('GET', '/wapi/admin-comm-templates/', {});
  },
  update(id, attrs) {
    return restAPI.requestPromise('POST', '/wapi/admin-comm-templates/' + id + '/', attrs);
  },
  create(attrs){
    return restAPI.requestPromise('POST', '/wapi/admin-comm-templates/', attrs);
  },
  destroy(id){
    return restAPI.requestPromise('DELETE', '/wapi/admin-comm-templates/' + id + '/', {});
  }
}, {
  define: {
    inputDisabled: {
      value: false,
      serialize: false
    },
    deleting: {
      value: false,
      serialize: false
    },
    buttonRunning: {
      value: false,
      serialize: false
    },
    templateName: {
      type: 'string'
    },
    subject: {
      type: 'string'
    },
    htmlTemplate: {
      type: 'string'
    },
    textTemplate: {
      type: 'string'
    },
    smsTemplate: {
      type: 'string'
    }
  }
});

export default AdminCommTemplatesModel;
