import 'can/map/define/';
import restAPI from 'rest-api';

let AdminICOTransactionModel = can.Model.extend({
  findAll(){
    return restAPI.requestPromise('GET', '/wapi/admin-transactions/', {});
  },
  create(attrs){
    return restAPI.requestPromise('POST', '/wapi/admin-transactions/', attrs);
  }
}, {});

export default AdminICOTransactionModel;
