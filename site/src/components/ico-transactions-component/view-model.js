import can from 'can';
import 'can/map/define/';
import ICOPurchase from '../../components/ico-transactions-component/template-ico-purchase.stache!'
import ICOTransactionModels from 'easyapp/models/ico-transactions/';

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
    },
		btcPrediction: {
			value: "0.000000"
		},
		eqbNumberPositive: {
			get() {
				let num = this.attr("eqbNumber");
				return Number(parseFloat(num)) == num && num != 0
			}
		},
		eqbNumber: {
      value: null,
      type: "number"
    }
  },
  buyEQBs() {
		let newTransaction = new ICOTransactionModels({});
		newTransaction.attr("numberEQB", this.attr("eqbNumber"));
		newTransaction.attr("manualTransaction", false);

		console.log(newTransaction);
  },
	updateEstimation(btc) {
		let tranche = this.attr("currentTranche");
		let tranchePrice = 0;
		if (btc == '') {
			btc = 0;
			this.attr("btcPrediction", "0.000000");
		} else {
			tranchePrice = this.attr("btcPrices")[tranche-1];
			this.attr("btcPrediction", (tranchePrice * btc).toFixed(6));
		}
	}
});