import can from 'can';
import 'can/map/define/';

export default can.Map.extend({
  define: {
    loaded: {
      value: false
    },
    btcPrices: {
      value: [{"price": 0.000023},{"price": 0.00010023},{"price": 0.01000023},{"price": 0.10000023},{"price": 0.10000023},{"price": 0.10000023},{"price": 0.10000023},{"price": 0.10000023},{"price": 0.10000023},{"price": 0.10000023}]
    },
    eqbRemaining: {
      value: [{"remaining": 100000},{"remaining": 100000},{"remaining": 100000},{"remaining": 100000},{"remaining": 100000},{"remaining": 100000},{"remaining": 100000},{"remaining": 100000},{"remaining": 100000},{"remaining": 100000}]
    },
    currentTranche: {
      get() {
        var highestZero = 0;
        this.attr("eqbRemaining").forEach((item, index) => {
          if (item.remaining == 0) highestZero = index + 1;
        });

        return highestZero + 1;
      }
    },
    progress: {
      get() {
        var total = 0;
        this.attr("eqbRemaining").forEach(item => total += item.remaining);
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
  }
});