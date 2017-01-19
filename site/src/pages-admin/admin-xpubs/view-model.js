import can from 'can';
import 'can/map/define/';
import templateNewAddressModel from './template-admin-new-xpub.stache!';
import AdminXPubsModels from 'easyapp/models/admin-xpubs/';

export default can.Map.extend({
	templateNewAddressModel: templateNewAddressModel,
	define: {
		loaded: {
			value: false
		},
		xPubData: {
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
		newXPub: {
			Type: AdminXPubsModels,
      value: new AdminXPubsModels({})
		}
	}
});