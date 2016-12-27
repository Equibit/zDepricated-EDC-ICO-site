import can from 'can';
import 'can/map/define/';

export default can.Map.extend({
  define: {
    countdown: {
      value: false,
      serialize: false
    },
    salesStart: {
      value: 1483228800,
      serialize: false
    },
    salesEnds: {
      value: 1485907200,
      serialize: false
    }
  }
});