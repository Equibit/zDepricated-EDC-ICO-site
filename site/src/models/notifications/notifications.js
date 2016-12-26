import 'can/map/define/';
import restAPI from 'rest-api';
import CachingModel from 'easyapp/utils/caching-model';

let NotificationsModel = CachingModel({
  findAll(){
    return restAPI.requestPromise('GET', '/wapi/notifications/', {});
  },
  update(id, attrs){
    return restAPI.requestPromise('POST', '/wapi/notifications/' + id + '/', attrs);
  },
  destroy(id){
    return restAPI.requestPromise('DELETE', '/wapi/notifications/' + id + '/', {});
  }
}, {});

export default NotificationsModel;
