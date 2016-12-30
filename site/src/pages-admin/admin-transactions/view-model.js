import can from 'can';
import 'can/map/define/';
import templateNewTransactionModel from './template-admin-new-transaction.stache!';
import AdminTransactionModels from 'easyapp/models/admin-transactions/';

export default can.Map.extend({
  templateNewTransactionModel: templateNewTransactionModel,
  define: {
    loaded: {
      value: false
    },
    users: {
      value: []
    },
    usersLoaded: {
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
  revokeTransaction(transaction) {
    transaction.save(() => {
      transaction.attr("rejected", true);
    });
  },
  addTransaction(transaction) {
    transaction.save(() => {
      transaction.attr("timeDate", Math.floor(Date.now() / 1000));
      this.attr("users").forEach(item => {
        if (item.id == transaction.userID) transaction.attr("username", item.userName);
      });
      this.attr("data").push(transaction);
      this.attr("newTransaction", {});
      $('#admin-new-transaction').modal('hide');
    });
  }
});