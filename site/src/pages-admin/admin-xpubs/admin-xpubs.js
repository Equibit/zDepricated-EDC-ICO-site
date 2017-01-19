import can from 'can';
import template from './template.stache!';
import viewModel from './view-model';
import AdminxPubModels from 'easyapp/models/admin-xpubs/';

can.Component.extend({
  tag: 'admin-xPubs',
  viewModel: viewModel,
  template: template,
  events: {
    inserted() {
			AdminxPubModels.findAll({})
        .then(data => {
          this.viewModel.attr('xPubData', data);
          this.viewModel.attr('loaded', true);
        })
        .fail(err => {
          console.log('FAILED to load keys', err);
        });

    }
  }
});