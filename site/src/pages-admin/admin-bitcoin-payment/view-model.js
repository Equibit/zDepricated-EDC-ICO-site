import can from 'can';
import 'can/map/define/';
import templateNewAddressModel from './template-admin-new-address.stache!';
import AdminTransactionModels from 'easyapp/models/admin-bitcoin-transactions/';

export default can.Map.extend({
	templateNewAddressModel: templateNewAddressModel,
	define: {
		loaded: {
			value: false
		},
		txLoaded: {
			value: false
		},
		transactionData: {
			value: []
		},
		bitcoinData: {
			value: [],
			set(newValue) {
				this.attr("data", newValue);
				return newValue;
			},
			serialize: false
		},
		data: {
			value: []
		},
		newBitcoinAddress: {
			Type: AdminTransactionModels,
      value: new AdminTransactionModels({})
		}
	},
	updateSearch(searchStr) {
		var newData = this.attr("bitcoinData").filter((elem, index, arr) =>  elem.userName.includes(searchStr) || elem.email.includes(searchStr));
		this.attr("data", newData);
	},
	addAddress(sale) {
		let tokenSaleID = sale.attr("tokenSaleID");

		if (tokenSaleID != 0 && tokenSaleID != '') {
			sale.save(saved => {
				saved.attr("timeDate", Math.floor(Date.now() / 1000));
				saved.attr("expectedPayment", saved.attr("expectedPayment") * 100000000);
				sale.attr("noAddress", false);
				this.attr("data").push(saved);
				console.log(saved);
			});
		}
	},
	resetAddress() {
		this.attr("newBitcoinAddress", {});
	}
});