import 'can/map/define/';
import restAPI from 'rest-api';

let AdminFAQsModel = can.Model.extend({
  findAll(){
    return restAPI.requestPromise('GET', '/wapi/admin-faqs/', {});
  },
  update(id, attrs) {
    return restAPI.requestPromise('POST', '/wapi/admin-faqs/' + id + '/', attrs);
  },
  create(attrs){
    return restAPI.requestPromise('POST', '/wapi/admin-faqs/', attrs);
  },
  destroy(id){
    return restAPI.requestPromise('DELETE', '/wapi/admin-faqs/' + id + '/', {});
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
    },
    lang: {
      value: typeof localStorage !== 'undefined' && localStorage.getItem('locale')
    }
  }
});

export default AdminFAQsModel;
