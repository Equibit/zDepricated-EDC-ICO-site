import can from 'can';
import template from './template.stache!';
import viewModel from './view-model';
import moment from 'moment';

can.Component.extend({
  tag: 'ico-progress-component',
  viewModel: viewModel,
  template: template,
  events: {
    inserted() {

    }
  }
});