import 'can/map/define/';
import restAPI from 'rest-api';

let AdminPoloniexModel = can.Model.extend({
  findAll() {
    return restAPI.requestPromise('GET', '/wapi/admin-poloniex/', {});
  }
}, {});

export default AdminPoloniexModel;
