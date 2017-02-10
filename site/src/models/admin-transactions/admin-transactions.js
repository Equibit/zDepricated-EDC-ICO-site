import 'can/map/define/';
import restAPI from 'rest-api';

let AdminICOTransactionModel = can.Model.extend({
  findAll(){
    return restAPI.requestPromise('GET', '/wapi/admin-transactions/', {});
  },
	update(id, attrs) {
		return restAPI.requestPromise('POST', '/wapi/admin-transactions/' + id + '/', attrs);
	},
  create(attrs){
    return restAPI.requestPromise('POST', '/wapi/admin-transactions/', attrs);
  }
}, {
  define: {
    paidBTC: {
      value: null
    },
    paidUSD: {
      value: null
    },
    manualTransaction: {
      value: true
    },
		numberEQB: {
      value: 0
    },
    address: {
      value: ''
    }
  }
});

export default AdminICOTransactionModel;
