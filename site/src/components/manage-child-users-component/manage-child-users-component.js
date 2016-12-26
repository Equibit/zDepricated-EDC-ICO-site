import can from 'can';
import template from './template.stache!';
import viewModel from './view-model';
import UserModel from 'easyapp/models/child-user/';

can.Component.extend({
  tag: 'manage-child-users-component',
  viewModel: viewModel,
  template: template,
  events: {
    inserted() {
      UserModel.findAll({})
        .then(data => {
          this.viewModel.attr('myUsers', data);
          this.viewModel.attr('loadingData', false);
        })
        .fail(err => {
          console.log('FAILED to load users', err);
        });
    }
  }
});