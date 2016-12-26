import 'can/map/define/';
import restAPI from 'rest-api';

let TextVariablesModel = can.Model.extend({
  findAll(){
    return restAPI.requestPromise('GET', '/wapi/admin-text-variables/', {});
  },
  update(id, attrs) {
    return restAPI.requestPromise('POST', '/wapi/admin-text-variables/' + id + '/', attrs);
  },
  create(attrs){
    return restAPI.requestPromise('POST', '/wapi/admin-text-variables/', attrs);
  },
  destroy(id){
    return restAPI.requestPromise('DELETE', '/wapi/admin-text-variables/' + id + '/', {});
  }
}, {});

export default TextVariablesModel;
