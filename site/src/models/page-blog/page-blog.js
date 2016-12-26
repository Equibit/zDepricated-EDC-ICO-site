import 'can/map/define/';
import restAPI from 'rest-api';

let BlogModel = can.Model.extend({
  findAll(attrs){
    return restAPI.requestUnsignedPromise('GET', '/wapi/blog/' + attrs.lang + '/', {});
  }
}, {
  define: {
    title: {
      value: '',
      type: 'string'
    },
    img: {
      value: '',
      type: 'string'
    },
    link: {
      value: '',
      type: 'string'
    },
    short: {
      value: '',
      type: 'string'
    },
    post: {
      value: '',
      type: 'string'
    },
    lang: {
      value: '',
      type: 'string'
    },
    dateTime: {
      value: '',
      type: 'string'
    },
    loadedData: {
      value: false,
      serialize: false
    }
  }
});

export default BlogModel;
