import can from 'can';
import template from './template.stache!';
import viewModel from './view-model';
import isSsr from 'easyapp/utils/isSsr';

can.Component.extend({
  tag: 'ico-progress-component',
  viewModel: viewModel,
  template: template,
  events: {
    inserted() {

      if (!isSsr) {
        this.viewModel.updateData();
        this.viewModel.startInternal();
      }

    },
    removed() {
      clearInterval(this.viewModel.attr("interval"));
    }
  }
});