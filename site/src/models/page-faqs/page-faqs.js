import 'can/map/define/';
import restAPI from 'rest-api';

let FAQsModel = can.Model.extend({
  findAll(attrs){
    return restAPI.requestUnsignedPromise('GET', '/wapi/faqs/' + attrs.lang + '/', {});
  }
}, {
  define: {
    loadedData: {
      value: false,
      serialize: false
    }
  }
});

export default FAQsModel;
