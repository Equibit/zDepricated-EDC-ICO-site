import 'can/map/define/';
import restAPI from 'rest-api';

let AdminBitcoinTransactionModel = can.Model.extend({
  findAll(){
    return restAPI.requestPromise('GET', '/wapi/admin-bitcoin-transactions/', {});
  },
  update(id, attrs) {
    return restAPI.requestPromise('POST', '/wapi/admin-bitcoin-transactions/' + id + '/', attrs);
  },
  create(attrs){
    return restAPI.requestPromise('POST', '/wapi/admin-bitcoin-transactions/', attrs);
  }
}, {});

export default AdminBitcoinTransactionModel;
