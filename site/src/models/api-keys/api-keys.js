import 'can/map/define/';
import restAPI from 'rest-api';

let APIkeysModel = can.Model.extend({
  findAll(){
    return restAPI.requestPromise('GET', '/wapi/manage-api-keys/', {});
  },
  findOne(id){
    return restAPI.requestPromise('GET', '/wapi/manage-api-keys/' + id + '/', {});
  },
  update(id, attrs) {
    return restAPI.requestPromise('POST', '/wapi/manage-api-keys/' + id + '/', attrs);
  },
  create(attrs){
    return restAPI.requestPromise('POST', '/wapi/manage-api-keys/', attrs);
  },
  destroy(id){
    return restAPI.requestPromise('DELETE', '/wapi/manage-api-keys/' + id + '/', {});
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
    keyDesc: {
      type: 'string'
    }
  }
});

export default APIkeysModel;
