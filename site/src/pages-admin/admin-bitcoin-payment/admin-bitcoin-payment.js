import can from 'can';
import template from './template.stache!';
import viewModel from './view-model';
import AdminBitcoinModels from 'easyapp/models/admin-bitcoin-transactions/';
import AdminTransactionModels from 'easyapp/models/admin-transactions/';

can.Component.extend({
  tag: 'admin-bitcoin-payment',
  viewModel: viewModel,
  template: template,
  events: {
    inserted() {
			AdminBitcoinModels.findAll({})
        .then(data => {
          this.viewModel.attr('bitcoinData', data);
          this.viewModel.attr('loaded', true);
        })
        .fail(err => {
          console.log('FAILED to load keys', err);
        });

			AdminTransactionModels.findAll({})
				.then(data => {
					this.viewModel.attr('transactionData', data);
					this.viewModel.attr('txLoaded', true);
				})
				.fail(err => {
					console.log('FAILED to load keys', err);
				});
    }
  }
});