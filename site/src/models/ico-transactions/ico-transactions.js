import 'can/map/define/';
import restAPI from 'rest-api';

let ICOTransactionModel = can.Model.extend({
  findAll(){
    return restAPI.requestPromise('GET', '/wapi/ico-transactions/', {});
  },
  create(attrs){
    return restAPI.requestPromise('POST', '/wapi/ico-transactions/', attrs);
  }
}, {});

export default ICOTransactionModel;
