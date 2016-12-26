import can from 'can';
import 'can/map/define/';

export default can.Map.extend({
  define: {
    countdown: {
      value: false
    },
    showZeros: {
      value: false
    },
    months: {
      value: "00"
    },
    days: {
      values: "00"
    },
    hours: {
      value: "00"
    },
    minutes: {
      values: "00"
    },
    seconds: {
      value: "00"
    },
    hasMonths: {
      get() {
        return parseInt(this.attr("months"))
      }
    },
    hasDays: {
      get() {
        return parseInt(this.attr("days"))
      }
    },
    hasHours: {
      get() {
        return parseInt(this.attr("hours"))
      }
    },
    hasMinutes: {
      get() {
        return parseInt(this.attr("minutes"))
      }
    },
    hasSeconds: {
      get() {
        return parseInt(this.attr("seconds"))
      }
    },
    manyMonths: {
      get() {
        return (parseInt(this.attr("months")) > 1)
      }
    },
    manyDays: {
      get() {
        return (parseInt(this.attr("days")) > 1)
      }
    },
    manyHours: {
      get() {
        return (parseInt(this.attr("hours")) > 1)
      }
    },
    manyMinutes: {
      get() {
        return (parseInt(this.attr("minutes")) > 1)
      }
    },
    manySeconds: {
      get() {
        return (parseInt(this.attr("seconds")) > 1)
      }
    }
  }
});