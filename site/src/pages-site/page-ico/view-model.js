import can from 'can';
import 'can/map/define/';

export default can.Map.extend({
	define: {
		countdown: {
			value: false
		},
		haveEQBRemaining : {
			value: true,
			get() {
				if (this.attr("eqbConfirmed")) {
					return (this.attr("eqbConfirmed") < 1000000)
				} else {
					return true;
				}
			}
		}
	}
});