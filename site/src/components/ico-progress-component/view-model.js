import can from 'can';
import 'can/map/define/';
import restAPI from 'rest-api';

export default can.Map.extend({
  define: {
    loaded: {
      value: false
    },
    btcPrices: {
      value: []
    },
    eqbRemaining: {
      value: []
    },
    currentTranche: {
      get() {
        var highestZero = 0;
        this.attr("eqbRemaining").forEach((item, index) => {
          if (item == 0) highestZero = index + 1;
        });

        return highestZero + 1;
      }
    },
    progress: {
      get() {
        var total = 0;
        this.attr("eqbRemaining").forEach(item => total += item);
        return ((1000000-total)/1000000)*100
      }
    },
    progressOverHalf: {
      get() {
        return this.attr("progress") > 50
      }
    },
    showMoreStart: {
      get() {
        return this.attr("currentTranche") != 1 && this.attr("currentTranche") != 2
      }
    },
    showMoreEnd: {
      get() {
        return this.attr("currentTranche") != 9 && this.attr("currentTranche") != 10
      }
    },
    showOne: {
      get() {
        return this.attr("currentTranche") == 1 || this.attr("currentTranche") == 2
      }
    },
    showTwo: {
      get() {
        return this.attr("currentTranche") == 1 || this.attr("currentTranche") == 2 || this.attr("currentTranche") == 3
      }
    },
    showThree: {
      get() {
        return this.attr("currentTranche") == 2 || this.attr("currentTranche") == 3 || this.attr("currentTranche") == 4
      }
    },
    showFour: {
      get() {
        return this.attr("currentTranche") == 3 || this.attr("currentTranche") == 4 || this.attr("currentTranche") == 5
      }
    },
    showFive: {
      get() {
        return this.attr("currentTranche") == 4 || this.attr("currentTranche") == 5 || this.attr("currentTranche") == 6
      }
    },
    showSix: {
      get() {
        return this.attr("currentTranche") == 5 || this.attr("currentTranche") == 6 || this.attr("currentTranche") == 7
      }
    },
    showSeven: {
      get() {
        return this.attr("currentTranche") == 6 || this.attr("currentTranche") == 7 || this.attr("currentTranche") == 8
      }
    },
    showEight: {
      get() {
        return this.attr("currentTranche") == 7 || this.attr("currentTranche") == 8 || this.attr("currentTranche") == 9
      }
    },
    showNine: {
      get() {
        return this.attr("currentTranche") == 8 || this.attr("currentTranche") == 9 || this.attr("currentTranche") == 10
      }
    },
    showTen: {
      get() {
        return this.attr("currentTranche") == 9 || this.attr("currentTranche") == 10
      }
    },
    oneAvailable: {
      get() {
        return this.attr("currentTranche") <= 1
      }
    },
    twoAvailable: {
      get() {
        return this.attr("currentTranche") <= 2
      }
    },
    threeAvailable: {
      get() {
        return this.attr("currentTranche") <= 3
      }
    },
    fourAvailable: {
      get() {
        return this.attr("currentTranche") <= 4
      }
    },
    fiveAvailable: {
      get() {
        return this.attr("currentTranche") <= 5
      }
    },
    sixAvailable: {
      get() {
        return this.attr("currentTranche") <= 6
      }
    },
    sevenAvailable: {
      get() {
        return this.attr("currentTranche") <= 7
      }
    },
    eightAvailable: {
      get() {
        return this.attr("currentTranche") <= 8
      }
    },
    nineAvailable: {
      get() {
        return this.attr("currentTranche") <= 9
      }
    },
    tenAvailable: {
      get() {
        return this.attr("currentTranche") <= 10
      }
    }
  },
  updateData() {

    restAPI.requestUnsigned('GET', '/wapi/crowd-sale-progress/', {},
      data => {
        this.attr('eqbRemaining', data.eqbRemaining);
        this.attr('btcPrices', data.btcPrices);
        this.attr('loaded', true);
      },
      err => console.log('FAILED to load email', err)
    );

  },
  startInternal() {

    var _self = this;

    var startInterval = setInterval(() => {
      restAPI.requestUnsigned('GET', '/wapi/crowd-sale-progress/', {},
        data => {
          _self.attr('eqbRemaining', data.eqbRemaining);
          _self.attr('btcPrices', data.btcPrices);
        },
        err => {
          console.log('FAILED to load email', err);
          clearInterval(startInterval);
        }
      );
    }, 30000);

    this.attr("interval", startInterval);

  }
});