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
  newPhone: "",
  secondFactor: "",
  saveChange() {

    this.attr("newPhone", ($('#new_phone').val()).replace(/[^0-9]/g, ''));

    if (!this.attr("phoneCreateError")) {
      this.attr("buttonRunning", true);
      this.attr("inputDisabled", 'disabled');
      this.attr("generalError", false);
      this.attr("successMessage", false);

      var dataObj = {
        newPhone: this.attr("newPhone"),
        phoneCode: this.attr("secondFactor")
      };

      restAPI.request('POST', '/wapi/change-phone/', dataObj,
          res => {
            if (!this.attr("secondFactorAsked") && typeof res.secondFactor != 'undefined' && res.secondFactor) {
              this.attr("secondFactorVisible", true);
            } else {
              this.attr("newPhone", "");
              this.attr("secondFactor", "");
              this.attr("secondFactorVisible", false);
              this.attr("successMessage", true);
              $('#new_phone').val('');
              this.attr("accountSettings.phoneConfirmed", true);
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
  phoneCheckFunc() {

    this.attr("newPhone", ($('#new_phone').val()).replace(/[^0-9]/g, ''));

    if (this.attr("newPhone") != '') {
      this.attr("phoneCreateInvalidError", false);
      if (this.attr('phoneCreate') != '') {
        restAPI.requestUnsigned('GET', '/wapi/check-phone/' + this.attr('newPhone') + '/', {},
          () => {
            this.attr('phoneCreateError', false);
            return true;
          }, () => {
            this.attr('phoneCreateError', true);
            return false;
          });
      } else {
        this.attr('phoneCreateError', false);
        this.attr("phoneCreateInvalidError", true);
        return false;
      }
    } else {
      this.attr('phoneCreateError', false);
      this.attr("phoneCreateInvalidError", false);
      return false;
    }

  }
});