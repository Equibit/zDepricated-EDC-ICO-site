import can from 'can';
import 'can/map/define/';
import i18n from 'easyapp/i18n/i18n';
import restAPI from 'rest-api';

export default can.Map.extend({
  i18n: i18n,
  buttonRunning: null,
  inputDisabled: null,
  successMessage: false,
  secondFactorVisible: false,
  newEmail: "",
  secondFactor: "",
  saveChange() {

    if (!this.attr("emailCreateError")) {
      this.attr("buttonRunning", true);
      this.attr("inputDisabled", 'disabled');
      this.attr("generalError", false);
      this.attr("successMessage", false);

      var dataObj = {
        newEmail: this.attr("newEmail"),
        emailedCode: this.attr("secondFactor")
      };

      restAPI.request('POST', '/wapi/change-email/', dataObj,
          res => {
            if (!this.attr("secondFactorAsked") && typeof res.secondFactor != 'undefined' && res.secondFactor) {
              this.attr("secondFactorVisible", true);
            } else {
              this.attr("newEmail", "");
              this.attr("secondFactor", "");
              this.attr("secondFactorVisible", false);
              this.attr("successMessage", true);
            }
            this.attr("buttonRunning", null);
            this.attr("inputDisabled", null);
          },
          () => {
            this.attr("buttonRunning", null);
            this.attr("inputDisabled", null);
          });
    }

    return false;
  },
  emailCheckFunc() {

    if (this.attr("newEmail") != '') {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      if (re.test(this.attr("newEmail")) && this.attr("newEmail") != '') {
        this.attr("emailCreateInvalidError", false);
        if (this.attr('newEmail') != '') {
          restAPI.requestUnsigned('GET', '/wapi/check-email/' + this.attr('newEmail') + '/', {},
              () => {
                this.attr('emailCreateError', false);
                return true;
              }, () => {
                this.attr('emailCreateError', true);
                return false;
              });
        }
      } else {
        this.attr('emailCreateError', false);
        this.attr("emailCreateInvalidError", true);
        return false;
      }
    } else {
      this.attr('emailCreateError', false);
      this.attr("emailCreateInvalidError", false);
      return false;
    }

  }
});