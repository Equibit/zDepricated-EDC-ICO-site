import can from 'can';
import template from './template.stache!';
import viewModel from './view-model';
import APIKeysModel from 'easyapp/models/api-keys/';

can.Component.extend({
  tag: 'manage-api-keys-component',
  viewModel: viewModel,
  template: template,
  events: {
    inserted() {
      APIKeysModel.findAll({})
        .then(data => {
          this.viewModel.attr('myKeys', data);
          this.viewModel.attr('loadingData', false);
        })
        .fail(err => {
          console.log('FAILED to load keys', err);
        });
    }
  }
});