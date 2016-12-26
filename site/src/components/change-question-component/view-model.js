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
  successMessage: false,
  saveSettings() {

    this.attr("buttonRunning", 'disabled');
    this.attr("inputDisabled", 'disabled');
    this.attr("generalError", false);
    this.attr("selectQuestionError", false);

    if (!this.attr("userCreateLengthError")
        && this.attr("securityAnswerChange").length > 5
        && this.attr("securityQuestionChange") != '') {

      var dataObj = {
        question: this.attr("securityQuestionChange"),
        answer: this.attr("securityAnswerChange")
      };

      restAPI.request('POST', '/wapi/change-question/', dataObj,
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
      if (this.attr("securityQuestionChange") == '') this.attr("selectQuestionError", true);
      if (this.attr("securityAnswerChange").length < 6) this.attr("securityAnswerError", true);

      this.attr("buttonRunning", null);
      this.attr("inputDisabled", null);
    }

  },
  securityQuestionChange: '',
  securityAnswerChange: '',
  securityAnswerError: false,
  lengthAnswerFunc() {

    if (this.attr("securityAnswerChange").length < 6 && this.attr("securityAnswerChange") != '') {
      this.attr("securityAnswerError", true);
    } else {
      this.attr("securityAnswerError", false);
    }

  },
});