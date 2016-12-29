import can from 'can';
import 'can/map/define/';

export default can.Map.extend({
  define: {
    countdown: {
      value: false
    },
    btePrediction: {
      value: 0
    },
    btcPrediction: {
      value: "0.00000"
    },
    eqbNumber: {
      value: ''
    }
  },
  updateEstimation(btc) {
    var tranche = this.attr("currentTranche");
    var tranchePrice = 0;
    if (btc == '') {
      btc = 0;
      this.attr("btcPrediction", "0.00000");
    } else if (Number(parseFloat(btc)) != btc) {
      this.attr("eqbNumber", 0);
      $("#removeFocus").blur();
    } else if (btc > 9999) {
      this.attr("eqbNumber", 9999);
      btc = 9999;
      $("#removeFocus").blur();
    } else {
      tranchePrice = this.attr("btcPrices")[tranche-1];
      this.attr("btcPrediction", (tranchePrice * btc).toFixed(5));
    }
  }
});