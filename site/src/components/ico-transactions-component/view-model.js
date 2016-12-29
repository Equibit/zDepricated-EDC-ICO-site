import can from 'can';
import 'can/map/define/';

export default can.Map.extend({
  define: {
    data: {
      value: []
    },
    loaded: {
      value: false
    },
    confirmed: {
      value: true
    }
  }
});