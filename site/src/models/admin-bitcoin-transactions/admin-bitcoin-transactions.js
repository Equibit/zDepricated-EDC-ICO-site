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
    },
		difference: {
		  get() {
		    if (this.attr("expectedPayment") && this.attr("receivedPayment") && this.attr("receivedPayment") != 0) {
		      return this.attr("receivedPayment") - this.attr("expectedPayment")
        }
        return 0
      }
    }
  }
});

export default AdminBitcoinTransactionModel;
