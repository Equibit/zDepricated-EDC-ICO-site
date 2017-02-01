import can from 'can';
import 'can/map/define/';

export default can.Map.extend({
	define: {
		countdown: {
			value: false
		},
		haveEQBRemaining : {
			value: -1,
			get() {
				if (this.attr("eqbConfirmed") && this.attr("eqbConfirmed") >= 0) {
					return (this.attr("eqbConfirmed") < 1000000)
				} else {
					return true;
				}
			}
		}
	}
});