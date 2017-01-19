import 'can/map/define/';
import restAPI from 'rest-api';

let AdminXPubsModel = can.Model.extend({
  findAll(){
    return restAPI.requestPromise('GET', '/wapi/admin-xpubs/', {});
  },
  update(id, attrs) {
    return restAPI.requestPromise('POST', '/wapi/admin-xpubs/' + id + '/', attrs);
  },
  create(attrs){
    return restAPI.requestPromise('POST', '/wapi/admin-xpubs/', attrs);
  }
}, {});

export default AdminXPubsModel;
