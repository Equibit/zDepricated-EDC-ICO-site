import can from 'can';
import 'can/map/define/';
import restAPI from 'rest-api';

let AccountSettings = can.Model.extend({
  parseModel(data){
    data.roles = data.roles.reduce((acc, item) => {
      acc[item] = true;
      return acc;
    }, {});
    return data;
  },
  findOne(){
    return new Promise(function (resolve, reject) {
      restAPI.request('GET', '/wapi/account-settings/', {}, resolve, reject);
    });
  }
}, {});

export default AccountSettings;