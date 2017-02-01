import can from 'can';
import 'can/map/define/';
import isSsr from 'easyapp/utils/isSsr';

export default can.Map.extend({
	define: {
		countdown: {
			value: false
		},
		haveEQBRemaining : {
			get() {
				if (!isSsr && this.attr("eqbRemaining") != 'undefined' && Object.prototype.toString.call( this.attr("eqbRemaining") ) === '[object Object]') {
					let total = 0;
					this.attr("eqbRemaining").forEach(item => total += item);
					return (total > 0)
				} else {
					return true;
				}
			}
		}
	}
});