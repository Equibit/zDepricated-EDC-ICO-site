import can from 'can';
import 'can/map/define/';
import ICOPurchase from '../../components/ico-transactions-component/template-ico-purchase.stache!'
import ICOTransactionModels from 'easyapp/models/ico-transactions/';
import restAPI from 'rest-api';

export default can.Map.extend({
	ICOPurchase:ICOPurchase,
  define: {
		inputDisabled: {
			value: false
		},
		makeRed: {
			value: false
		},
		expiredOrder: {
			value: false
		},
		completedOrder: {
			value: false
		},
		expireTime: {
			value: Math.floor(Date.now() / 1000 - 300)
		},
		buttonRunning: {
			value: false
		},
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
				return Number(parseFloat(num)) == num && num > 0
			}
		},
		eqbNumber: {
      value: null,
      type: "number",
			get(lastSetValue) {
      	if (lastSetValue > 100000) {
      		return 100000;
				} else {
      		return lastSetValue;
				}
			}
    },
		noAddress: {
    	value: true
		},
		addressData: {
    	value: null,
			Type: ICOTransactionModels
		}
  },
  buyEQBs() {
		this.attr("buttonRunning", true);
		this.attr("inputDisabled", true);

		let newTransaction = new ICOTransactionModels({});
		newTransaction.attr("numberEQB", this.attr("eqbNumber"));
		newTransaction.attr("manualTransaction", false);
		newTransaction.save(saved => {
			saved.attr("timeDate", Math.floor(Date.now() / 1000));
			let remainingTime = 600;
			let timer = setInterval(() => {
				remainingTime--;
				if (remainingTime < 30) {
					this.attr("makeRed", true);
				}
				this.attr("expireTime", Math.floor(Date.now() / 1000 - remainingTime));
				if (remainingTime <= 0) {
					clearInterval(timer);
					// this.attr("expiredOrder", true);
				}
				if (remainingTime % 30 == 0) {
					restAPI.request('GET', '/wapi/check-sale/' + saved.attr("id") + '/', {},
						() => {
							clearInterval(timer);
							this.attr("completedOrder", true);
							saved.attr("confirmed", true);
						},
						err => console.log('FAILED', err)
					);
				}
			}, 1000);
			saved.attr("fundingLevel", this.attr("currentTranche"));
			this.attr("noAddress", false);
			this.attr("addressData", saved);
			this.attr("confirmed", false);
			this.attr("data").push(saved);

			this.attr("buttonRunning", false);
			this.attr("inputDisabled", false);
		});
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