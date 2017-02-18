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
		newUserTransaction: {
			value: new AdminTransactionModels({}),
      Type: AdminTransactionModels
    }
  },
  updateSearch(searchStr) {
    let newData = this.attr("usersData").filter((elem, index, arr) =>  elem.username.includes(searchStr));
    this.attr("data", newData);
  },
  revokeTransaction(transactionRejected) {
		transactionRejected.attr("rejected", 1);
		transactionRejected.save();
  },
  confirmTransaction(transactionCompleted) {
		transactionCompleted.attr("completed", 1);
		transactionCompleted.attr("rejected", 0);
		transactionCompleted.save();
  },
  addTransaction(transaction) {
    transaction.save(data => {
			data.attr("timeDate", Math.floor(Date.now() / 1000));
      this.attr("users").forEach(item => {
        if (item.id == data.userID) data.attr("username", item.userName);
      });
      this.attr("data").push(data);
			this.attr("newUserTransaction.id", null);
			this.attr("newUserTransaction", {});
      $('#admin-new-transaction').modal('hide');
    });
  }
});