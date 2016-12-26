import can from 'can';
import template from './template.stache!';
import viewModel from './view-model';
import AdminPoloniex from 'easyapp/models/admin-poloniex/';

can.Component.extend({
  tag: 'admin-poloniex',
  viewModel: viewModel,
  template: template,
  events: {
    inserted() {
      AdminPoloniex.findAll({})
        .then(data => {
          console.log(data);
          this.viewModel.attr('prices', data);
          this.viewModel.attr('loadedData', true);
        })
        .fail(err => {
          console.log('FAILED to load', err);
        });
    }
  }
});
