import can from 'can';
import template from './template.stache!';
import viewModel from './view-model';

can.Component.extend({
  tag: 'change-password-component',
  viewModel: viewModel,
  template: template
});