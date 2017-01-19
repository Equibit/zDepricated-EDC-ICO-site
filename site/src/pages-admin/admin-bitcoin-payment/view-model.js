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
		search: {
			type: 'string',
			serialize: false
		},
		newBitcoinAddress: {
			Type: AdminTransactionModels,
      value: new AdminTransactionModels({})
		}
	},
	updateSearch(searchStr) {
		let newData = this.attr("bitcoinData").filter((elem, index, arr) =>  elem.username.includes(searchStr));
		this.attr("data", newData);
	}
});