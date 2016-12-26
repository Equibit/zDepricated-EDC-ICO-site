import 'can/map/define/';
import restAPI from 'rest-api';

let AdminBlogModel = can.Model.extend({
  findAll(){
    return restAPI.requestPromise('GET', '/wapi/admin-blog/', {});
  },
  update(id, attrs) {
    return restAPI.requestPromise('POST', '/wapi/admin-blog/' + id + '/', attrs);
  },
  create(attrs){
    return restAPI.requestPromise('POST', '/wapi/admin-blog/', attrs);
  },
  destroy(id){
    return restAPI.requestPromise('DELETE', '/wapi/admin-blog/' + id + '/', {});
  }
}, {
  define: {
    inputDisabled: {
      value: false,
      serialize: false
    },
    deleting: {
      value: false,
      serialize: false
    }
  }
});

export default AdminBlogModel;
