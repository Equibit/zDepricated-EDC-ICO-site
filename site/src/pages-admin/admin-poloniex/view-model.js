import can from 'can';
import 'can/map/define/';

export default can.Map.extend({
  define: {
    loadedData: {
      value: false
    },
    prices: {
      value: []
    }
  }
});