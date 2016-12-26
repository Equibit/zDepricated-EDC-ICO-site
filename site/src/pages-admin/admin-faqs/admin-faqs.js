import can from 'can';
import template from './template.stache!';
import viewModel from './view-model';
import AdminFAQs from 'easyapp/models/admin-faqs/';

can.Component.extend({
    tag: 'admin-faqs',
    viewModel: viewModel,
    template: template,
    events: {
        inserted() {
            AdminFAQs.findAll({})
                .then(data => {
                    this.viewModel.attr('adminFAQsData', data);
                    this.viewModel.attr('loadedData', true);
                })
                .fail(err => {
                    console.log('FAILED to load', err);
                });
        }
    }
});
