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
		tokenSaleID: {
			value: null
		}
	},
	addAddress() {
		console.log(this.attr("tokenSaleID"));
	}
});