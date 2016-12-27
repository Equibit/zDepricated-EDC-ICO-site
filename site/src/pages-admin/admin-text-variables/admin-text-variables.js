import can from 'can';
import template from './template.stache!';
import viewModel from './view-model';
import AdminText from 'easyapp/models/admin-text-variables/';
import restAPI from 'rest-api';

can.Component.extend({
  tag: 'admin-text-variables',
  viewModel: viewModel,
  template: template,
  events: {
    inserted() {
      AdminText.findAll({})
        .then(data => {
          this.viewModel.attr('textVariables', data);
          this.viewModel.attr('loadedData', true);
        })
        .fail(err => {
          console.log('FAILED to load', err);
        });

      restAPI.request('GET', '/wapi/admin-comm-templates-email/', {},
        data => {
          this.viewModel.attr('emailTemplates', data);
          this.viewModel.attr('loadedEmailData', true);
        },
        err => console.log('FAILED to load email', err));

      restAPI.request('GET', '/wapi/admin-comm-templates-sms/', {},
        data => {
          this.viewModel.attr('smsTemplates', data);
          this.viewModel.attr('loadedSMSData', true);
        },
        err => console.log('FAILED to load sms', err))
    }
  }
});
