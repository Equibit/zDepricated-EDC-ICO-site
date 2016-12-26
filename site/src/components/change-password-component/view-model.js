import can from 'can';
import 'can/map/define/';
import config from 'config';
import i18n from 'easyapp/i18n/i18n';
import restAPI from 'rest-api';

export default can.Map.extend({
  languages: config.languages,
  i18n: i18n,
  buttonRunning: null,
  inputDisabled: null,
  generalError: false,
  successMessage: false,
  saveSettings() {

    this.attr("buttonRunning", 'disabled');
    this.attr("inputDisabled", 'disabled');
    this.attr("successMessage", false);
    this.attr("generalError", false);

    if (this.attr("passwordOldChange") != ''
        && this.attr("passwordChange") != ''
        && this.attr("passwordRetypedChange") != ''
        && this.attr("passwordChange") == this.attr("passwordRetypedChange")) {

      var dataObj = {
        oldPassword: restAPI.hashMe(this.attr("passwordOldChange")),
        newPassword: restAPI.hashMe(this.attr("passwordChange")),
        retype: restAPI.hashMe(this.attr("passwordRetypedChange"))
      };

      restAPI.request('POST', '/wapi/change-password/', dataObj,
          () => {
            this.attr("buttonRunning", null);
            this.attr("inputDisabled", null);
            this.attr("successMessage", true);
          }, () => {
            this.attr("buttonRunning", null);
            this.attr("inputDisabled", null);
            this.attr("generalError", true);
          });
    } else {
      this.attr("buttonRunning", null);
      this.attr("inputDisabled", null);
    }
    return false;

  },
  passwordOldChange: '',
  passwordChange: '',
  passwordRetypedChange: '',
  passwordStrengthText: '',
  passwordStrengthColor: 'orange-text',
  passwordMismatchError: false,
  strongPassFunc(pass) {

    if (pass != '') {
      var strongRegex = new RegExp("^(?=.{12,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
      var mediumRegex = new RegExp("^(?=.{10,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
      var enoughRegex = new RegExp("(?=.{8,}).*", "g");
      if (false == enoughRegex.test(pass)) {
        this.attr("passwordStrengthText", i18n.longerPasswordNeeded);
        this.attr("passwordStrengthColor", 'orange-text');
      } else if (strongRegex.test(pass)) {
        this.attr("passwordStrengthText", i18n.strongPassword);
        this.attr("passwordStrengthColor", 'green-text');
      } else if (mediumRegex.test(pass)) {
        this.attr("passwordStrengthText", i18n.okPassword);
        this.attr("passwordStrengthColor", 'black-text');
      } else {
        this.attr("passwordStrengthText", i18n.weakPassword);
        this.attr("passwordStrengthColor", 'orange-text');
      }
    }

  },
  comparePasswordFunc(pass2) {

    if (pass2.length >= this.attr("passwordChange").length && pass2 != this.attr("passwordChange")) {
      this.attr("passwordMismatchError", true);
    } else {
      this.attr("passwordMismatchError", false);
    }

  }
});