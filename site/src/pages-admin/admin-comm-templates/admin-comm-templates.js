import can from 'can';
import template from './template.stache!';
import viewModel from './view-model';
import AdminCommTemplates from 'easyapp/models/admin-comm-templates/';

can.Component.extend({
    tag: 'admin-comm-templates',
    viewModel: viewModel,
    template: template,
    events: {
        inserted() {
            AdminCommTemplates.findAll({})
              .then(data => {
                  this.viewModel.attr('adminCommTemplatesData', data);
                  this.viewModel.attr('loadedData', true);
              })
              .fail(err => {
                  console.log('FAILED to load', err);
              });
        }
    }
});
