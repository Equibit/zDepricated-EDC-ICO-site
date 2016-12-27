import can from 'can';
import template from './template.stache!';
import viewModel from './view-model';
import moment from 'moment';

can.Component.extend({
  tag: 'countdown-component',
  viewModel: viewModel,
  template: template,
  events: {
    inserted() {
      var startInterval = setInterval(() => {
        if (typeof this.viewModel.attr("timestamp") != 'undefined') {
          clearInterval(startInterval);
          var eventTime = this.viewModel.attr("timestamp"),
            currentTime = Math.floor(Date.now() / 1000),
            diffTime = eventTime - currentTime,
            duration = moment.duration(diffTime * 1000, 'milliseconds'),
            interval = 1000;

          if (diffTime > 0) {
            var countdownInterval = setInterval(() => {
              duration = moment.duration(duration.asMilliseconds() - interval, 'milliseconds');
              //console.log(duration._milliseconds);
              if (duration._milliseconds >= 0) {
                var o = moment.duration(duration).months(),
                  d = moment.duration(duration).days(),
                  h = moment.duration(duration).hours(),
                  m = moment.duration(duration).minutes(),
                  s = moment.duration(duration).seconds();

                if (this.viewModel.attr("showZeros")) {
                  o = $.trim(o).length === 1 ? '0' + o : o;
                  d = $.trim(d).length === 1 ? '0' + d : d;
                  h = $.trim(h).length === 1 ? '0' + h : h;
                  m = $.trim(m).length === 1 ? '0' + m : m;
                  s = $.trim(s).length === 1 ? '0' + s : s;
                }

                this.viewModel.attr("months", o);
                this.viewModel.attr("days", d);
                this.viewModel.attr("hours", h);
                this.viewModel.attr("minutes", m);
                this.viewModel.attr("seconds", s);

                this.viewModel.attr("loaded", true);
              } else {
                this.viewModel.attr("countdown", true);
                clearInterval(countdownInterval);
              }

            }, interval);
          } else {
            this.viewModel.attr("countdown", true);
            this.viewModel.attr("loaded", true);
          }
        }
      }, 1000);
    }
  }
});