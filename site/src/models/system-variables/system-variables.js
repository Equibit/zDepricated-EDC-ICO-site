import can from 'can';
import 'can/map/define/';
import restAPI from 'rest-api';

let SystemSettings = can.Model.extend({
  findOne(){
    return restAPI.requestPromise('GET', '/wapi/system-variables/', {});
  }
}, {});

export default SystemSettings;