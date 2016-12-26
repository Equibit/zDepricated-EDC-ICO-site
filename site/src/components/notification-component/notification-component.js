import can from 'can';
import template from './template.stache!';
import viewModel from './view-model';
import Notifications from 'easyapp/models/notifications/';

can.Component.extend({
  tag: 'notification-component',
  viewModel: viewModel,
  template: template,
  events: {
    inserted() {
      return Notifications.findAll({}).then(data => this.viewModel.attr('notifications', data));
    }
  }
});