import 'can/map/define/';
import restAPI from 'rest-api';

let AdminUsersModel = can.Model.extend({
  findAll(){
    return restAPI.requestPromise('GET', '/wapi/admin-users/', {});
  },
  update(id, attrs) {
    return restAPI.requestPromise('POST', '/wapi/admin-users/' + id + '/', attrs);
  },
  destroy(id){
    return restAPI.requestPromise('DELETE', '/wapi/admin-users/' + id + '/', {});
  }
}, {
  define: {
    inputDisabled: {
      value: false,
      serialize: false
    },
    userName: {
      value: '',
      type: 'string'
    },
    deleting: {
      value: false,
      type: 'boolean',
      serialize: false
    }
  }
});

export default AdminUsersModel;
