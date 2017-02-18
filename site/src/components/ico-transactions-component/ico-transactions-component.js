import can from 'can';
import template from './template.stache!';
import viewModel from './view-model';
import ICOTransactionModels from 'easyapp/models/ico-transactions/';

can.Component.extend({
  tag: 'ico-transactions-component',
  viewModel: viewModel,
  template: template,
  events: {
    inserted() {
      ICOTransactionModels.findAll({})
        .then(data => {
          this.viewModel.attr('data', data);
          data.forEach(item => {
            if (item.completed == 0 && item.rejected == 0) this.viewModel.attr("confirmed", false);
          });
          this.viewModel.attr('loaded', true);
        })
        .fail(err => {
          console.log('FAILED to load keys', err);
        });
    }
  }
});