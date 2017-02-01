import can from 'can';
import 'can/map/define/';
import ICOPurchase from '../../components/ico-transactions-component/template-ico-purchase.stache!'

export default can.Map.extend({
	ICOPurchase:ICOPurchase,
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