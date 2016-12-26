import can from 'can';
import 'can/map/define/';
import Blog from 'easyapp/models/page-blog/';

export default can.Map.extend({
  define: {
    loadedData: {
      value: false
    },
    lang: {
      value: typeof localStorage !== 'undefined' && localStorage.getItem('locale'),
      set(newValue){
        Blog.findAll({lang: newValue})
            .then(data => {
              this.attr('blog', data);
              this.attr('loadedData', true);
            })
            .fail(err => {
              console.log('FAILED to load', err);
            });
        return newValue;
      }
    },
    blog: {
      value: []
    }
  }
});