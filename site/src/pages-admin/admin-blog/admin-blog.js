import can from 'can';
import template from './template.stache!';
import viewModel from './view-model';
import AdminBlog from 'easyapp/models/admin-blog/';

can.Component.extend({
    tag: 'admin-blog',
    viewModel: viewModel,
    template: template,
    events: {
        inserted() {
            AdminBlog.findAll({})
                .then(data => {
                    this.viewModel.attr('adminBlogData', data);
                    this.viewModel.attr('loadedData', true);
                })
                .fail(err => {
                    console.log('FAILED to load', err);
                });
        }
    }
});
