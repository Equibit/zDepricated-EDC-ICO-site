import can from 'can';
import 'can/map/define/';
import FAQs from 'easyapp/models/page-faqs/';

export default can.Map.extend({
  define: {
    loadedData: {
      value: false
    },
    search: {
      type: 'string',
      serialize: false
    },
    lang: {
      value: typeof localStorage !== 'undefined' && localStorage.getItem('locale'),
      set(newValue){
        FAQs.findAll({lang: newValue})
          .then(data => {
            this.attr('faqsData', data);
            this.attr('loadedData', true);
          })
          .fail(err => {
            console.log('FAILED to load', err);
          });
        return newValue;
      }
    },
    faqsData: {
      value: [],
      set(newValue) {
        this.attr("faqs", newValue);
        return newValue;
      },
      serialize: false
    },
    faqs: {
      value: []
    }
  },
  updateSearch(searchStr) {
    let newData = this.attr("faqsData").filter(elem => elem.question.toUpperCase().includes(searchStr.toUpperCase()) || elem.answer.toUpperCase().includes(searchStr.toUpperCase()));
    this.attr("faqs", newData);
  }
});