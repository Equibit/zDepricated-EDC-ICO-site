import can from 'can';
import 'can/map/define/';
import NewTransactionModel from './template-admin-new-transaction.stache';
import AdminTransactionModels from 'easyapp/models/admin-transactions/';

export default can.Map.extend({
  NewTransactionModel: NewTransactionModel,
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
    },
    newTransaction: {
      Type: AdminTransactionModels,
      value: new AdminTransactionModels({})
    }
  },
  updateSearch(searchStr) {
    var newData = this.attr("usersData").filter((elem, index, arr) =>  elem.username.includes(searchStr));
    this.attr("data", newData);
  },
});