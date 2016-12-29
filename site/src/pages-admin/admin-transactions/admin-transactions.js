import can from 'can';
import template from './template.stache!';
import viewModel from './view-model';
import AdminTransactionModels from 'easyapp/models/admin-transactions/';

can.Component.extend({
  tag: 'admin-transactions',
  viewModel: viewModel,
  template: template,
  events: {
    inserted() {
      AdminTransactionModels.findAll({})
        .then(data => {
          this.viewModel.attr('usersData', data);
          this.viewModel.attr('loaded', true);
        })
        .fail(err => {
          console.log('FAILED to load keys', err);
        });
    }
  }
});