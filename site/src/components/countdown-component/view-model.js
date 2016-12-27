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
    loaded: {
      value: false
    },
    hasMonths: {
      get() {
        return parseInt(this.attr("months"))
      }
    },
    hasDays: {
      get() {
        return parseInt(this.attr("days")) || parseInt(this.attr("months"))
      }
    },
    hasHours: {
      get() {
        return parseInt(this.attr("hours")) || parseInt(this.attr("days")) || parseInt(this.attr("months"))
      }
    },
    hasMinutes: {
      get() {
        return parseInt(this.attr("minutes")) || parseInt(this.attr("hours")) || parseInt(this.attr("days")) || parseInt(this.attr("months"))
      }
    },
    hasSeconds: {
      get() {
        return parseInt(this.attr("seconds")) || parseInt(this.attr("minutes")) || parseInt(this.attr("hours")) || parseInt(this.attr("days")) || parseInt(this.attr("months"))
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
    },
    yearProgress: {
      get() {
        return (parseInt(this.attr("months"))/12)*100
      }
    },
    monthProgress: {
      get() {
        return (parseInt(this.attr("days"))/30)*100
      }
    },
    dayProgress: {
      get() {
        return (parseInt(this.attr("hours"))/24)*100
      }
    },
    hourProgress: {
      get() {
        return (parseInt(this.attr("minutes"))/60)*100
      }
    },
    minuteProgress: {
      get() {
        return (parseInt(this.attr("seconds"))/60)*100
      }
    }
  }
});