import can from 'can';
import template from './template.stache!';
import viewModel from './view-model';

can.Component.extend({
  tag: 'settings-component',
  viewModel: viewModel,
  template: template,
  events: {
    inserted() {
      var _self = this;

      this.viewModel.languages.forEach(item => {
        if (item.selected) _self.viewModel.attr("newLanguage", item.code);
      });
    }
  }
});