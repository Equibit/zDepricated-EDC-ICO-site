import can from 'can';
import template from './template.stache!';
import viewModel from './view-model';
import AdminUsers from 'easyapp/models/admin-users/';

can.Component.extend({
    tag: 'admin-users',
    viewModel: viewModel,
    template: template,
    events: {
        inserted() {
            AdminUsers.findAll({})
                .then(data => {
                    this.viewModel.attr('usersData', data);
                    this.viewModel.attr('loadedData', true);
                })
                .fail(err => {
                    console.log('FAILED to load', err);
                });
        }
    }
});
