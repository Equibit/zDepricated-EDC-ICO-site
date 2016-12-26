import can from 'can';
import 'can/map/define/';
import i18n from 'easyapp/i18n/i18n';
import restAPI from 'rest-api';
import config from 'config';

export default can.Map.extend({
  i18n: i18n,
  hasEmail: config.general.requireEmail,
  hasPhone: config.general.requirePhone,
  buttonRunning: null,
  inputDisabled: null,
  generalError: false,
  usernameOrEmail: '',
  securityQuestionAsked: false,
  securityAnswerDisabled: null,
  securityQuestionDynamic: '',
  securityAnswer: '',
  secondFactorAsked: false,
  secondFactor: '',
  secondFactorDisabled: null,
  runForgotPasswordFunc() {

    this.attr("buttonRunning", 'disabled');
    this.attr("inputDisabled", 'disabled');
    this.attr("passwordInputDisabled", 'disabled');
    this.attr("generalSuccess", false);

    if (this.attr("usernameOrEmail") != '') {

      if (!this.attr("secondFactorAsked") && (this.attr("hasEmail") || this.attr("hasPhone"))) this.attr("pleaseWaitMessage", true);

      var dataObj = {
        usernameOrEmail: this.attr("usernameOrEmail"),
        secondFactor: this.attr("secondFactor"),
        answer: this.attr("securityAnswer"),
        passwordForgot: restAPI.hashMe(this.attr("passwordForgot")),
        passwordRetypedForgot: restAPI.hashMe(this.attr("passwordRetypedForgot"))
      };

      restAPI.requestUnsigned('POST', '/wapi/forgot-password/', dataObj,
          res => {
            this.attr("generalError", false);
            if (!this.attr("secondFactorAsked") && typeof res.secondFactor != 'undefined' && res.secondFactor) {
              this.attr("secondFactorAsked", true);
              this.attr("buttonRunning", null);
            } else if (!this.attr("securityQuestionAsked") && typeof res.question != 'undefined' && res.question != '') {
              this.attr("secondFactorDisabled", 'disabled');
              this.attr("securityQuestionAsked", true);
              this.attr("pleaseWaitMessage", false);
              this.attr("securityQuestionDynamic", res.question);
              this.attr("buttonRunning", null);
            } else if (!this.attr("newPasswordAsked") && typeof res.askForNewPassword != 'undefined' && res.askForNewPassword) {
              this.attr("securityAnswerDisabled", 'disabled');
              this.attr("passwordInputDisabled", null);
              this.attr("pleaseWaitMessage", false);
              this.attr("newPasswordAsked", true);
              this.attr("buttonRunning", null);
            } else if (typeof res.flowDone != 'undefined' && res.flowDone) {
              this.attr("generalError", true);
              this.attr("buttonRunning", null);
              this.attr("inputDisabled", null);

              //reset
              this.attr("pleaseWaitMessage", false);
              this.attr("generalError", false);
              this.attr("generalSuccess", true);
              this.attr("newPasswordAsked", false);
              this.attr("secondFactorDisabled", null);
              this.attr("secondFactorAsked", false);
              this.attr("secondFactor", '');
              this.attr("usernameOrEmail", '');
              this.attr("securityQuestionAsked", false);
              this.attr("securityQuestionDynamic", '');
              this.attr("securityAnswer", '');
              this.attr("securityAnswerDisabled", null);
              this.attr("passwordInputDisabled", null);

              $('#forgot-password-modal').modal('hide');
              $('#login-modal').modal('show');
            }
          },
          () => {
            this.attr("generalError", true);
            this.attr("buttonRunning", null);
            this.attr("inputDisabled", null);

            //reset
            this.attr("pleaseWaitMessage", false);
            this.attr("newPasswordAsked", false);
            this.attr("secondFactorDisabled", null);
            this.attr("secondFactorAsked", false);
            this.attr("secondFactor", '');
            this.attr("securityQuestionAsked", false);
            this.attr("securityQuestionDynamic", '');
            this.attr("securityAnswer", '');
            this.attr("securityAnswerDisabled", null);
            this.attr("passwordInputDisabled", null);
          });
    } else {
      this.attr("pleaseWaitMessage", false);
      this.attr("buttonRunning", null);
      this.attr("inputDisabled", null);
    }
    return false;

  },
  passwordInputDisabled: null,
  newPasswordAsked: false,
  passwordForgot: '',
  passwordRetypedForgot: '',
  passwordMismatchError: false,
  passwordStrengthText: '',
  passwordStrengthColor: 'red-text',
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

    if (pass2.length >= this.attr("passwordForgot").length && pass2 != this.attr("passwordForgot")) {
      this.attr("passwordMismatchError", true);
    } else {
      this.attr("passwordMismatchError", false);
    }

  }
});