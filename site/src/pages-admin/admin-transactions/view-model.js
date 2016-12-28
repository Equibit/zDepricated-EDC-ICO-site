import can from 'can';
import 'can/map/define/';

export default can.Map.extend({
  define: {
    loaded: {
      value: false
    },
    usersData: {
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
    }
  },
  updateSearch(searchStr) {
    var newData = this.attr("usersData").filter((elem, index, arr) =>  elem.username.includes(searchStr));
    this.attr("data", newData);
  },
});