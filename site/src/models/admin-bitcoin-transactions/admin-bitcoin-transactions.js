import 'can/map/define/';
import restAPI from 'rest-api';

let AdminBitcoinTransactionModel = can.Model.extend({
  findAll(){
    return restAPI.requestPromise('GET', '/wapi/admin-bitcoin-transactions/', {});
  },
  create(attrs){
    return restAPI.requestPromise('POST', '/wapi/admin-bitcoin-transactions/', attrs);
  }
}, {
  define: {
		noAddress: {
		  value: true
    }
  }
});

export default AdminBitcoinTransactionModel;
