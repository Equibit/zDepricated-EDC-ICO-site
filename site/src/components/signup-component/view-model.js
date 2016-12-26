import $ from 'jquery';
import can from 'can';
import 'can/map/define/';
import restAPI from 'rest-api';
import i18n from 'easyapp/i18n/i18n';
import config from 'config';

export default can.Map.extend({
  i18n: i18n,
  hasEmail: config.general.signUpWithEmail,
  hasPhone: config.general.signUpWithPhone,
  hasBoth: config.general.signUpWithPhone && config.general.signUpWithEmail,
  buttonRunning: null,
  inputDisabled: null,
  secondFactorDisabled: null,
  generalError: false,
  userCreate: '',
  userCreateError: false,
  userCreateLengthError: false,
  userCreateAcceptedError: false,
  pleaseWaitMessage: false,
  usernameCheckFunc() {
    // todo: accept only letters numbers underscore and hyphens

    if (this.attr("userCreate").length < config.general.usernameMinLength && this.attr("userCreate") != '') {
      this.attr("userCreateLengthError", true);
      this.attr('userCreateError', false);
    } else if (this.attr("userCreate") != '') {
      this.attr("userCreateLengthError", false);

      if (this.attr('userCreate') != '') {
        restAPI.requestUnsigned('GET', '/wapi/check-username/' + this.attr('userCreate') + '/', {},
            () => {
              this.attr('userCreateError', false);
            }, () => {
              this.attr('userCreateError', true);
            });
      }
    } else {
      this.attr("userCreateLengthError", false);
      this.attr('userCreateError', false);
    }

  },
  emailCreate: '',
  emailCreateError: false,
  emailCreateInvalidError: false,
  emailCheckFunc() {

    if (this.attr("emailCreate") != '') {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      if (re.test(this.attr("emailCreate")) && this.attr("emailCreate") != '') {
        this.attr("emailCreateInvalidError", false);
        if (this.attr('emailCreate') != '') {
          restAPI.requestUnsigned('GET', '/wapi/check-email/' + this.attr('emailCreate') + '/', {},
              () => {
                this.attr('emailCreateError', false);
              }, () => {
                this.attr('emailCreateError', true);
              });
        }
      } else {
        this.attr('emailCreateError', false);
        this.attr("emailCreateInvalidError", true);
      }
    } else {
      this.attr('emailCreateError', false);
      this.attr("emailCreateInvalidError", false);
    }

  },
  phoneCreate: '',
  phoneCreateError: false,
  phoneCreateInvalidError: false,
  phoneCheckFunc() {

    if (this.attr("hasPhone")) this.attr("phoneCreate", $('#phone_create').val().replace(/[^0-9]/g, ''));

    if (this.attr("phoneCreate") != '') {
      this.attr("emailCreateInvalidError", false);
      if (this.attr('phoneCreate') != '') {
        restAPI.requestUnsigned('GET', '/wapi/check-phone/' + this.attr('phoneCreate') + '/', {},
            () => {
              this.attr('phoneCreateError', false);
            }, () => {
              this.attr('phoneCreateError', true);
            });
      } else {
        this.attr('phoneCreateError', false);
        this.attr("phoneCreateInvalidError", true);
      }
    } else {
      this.attr('phoneCreateError', false);
      this.attr("phoneCreateInvalidError", false);
    }

  },
  securityQuestionCreate: i18n.changeQuestionQuestions[0],
  securityAnswerCreate: '',
  securityAnswerError: false,
  lengthAnswerFunc() {

    if (this.attr("securityAnswerCreate").length < config.general.securityAnswerMinLength && this.attr("securityAnswerCreate") != '') {
      this.attr("securityAnswerError", true);
    } else {
      this.attr("securityAnswerError", false);
    }

  },
  passwordCreate: '',
  passwordRetypedCreate: '',
  passwordMismatchError: false,
  signUpFunc() {
    this.attr('generalError', false);
    this.attr("buttonRunning", 'disabled');
    this.attr("inputDisabled", 'disabled');
    this.attr("secondFactorDisabled", 'disabled');

    if (this.attr("hasPhone")) this.attr("phoneCreate", $('#phone_create').val().replace(/[^0-9]/g, ''));

    if (!this.attr("userCreateError")
        && !this.attr("userCreateLengthError")
        && !this.attr("emailCreateError")
        && !this.attr("emailCreateInvalidError")
        && !this.attr("securityAnswerError")
        && !this.attr("passwordMismatchError")
        && this.attr("userCreate") != ''
        && (this.attr("emailCreate") != '' || !this.attr("hasEmail"))
        && (this.attr("phoneCreate") != '' || !this.attr("hasPhone"))
        && this.attr("securityAnswerCreate") != ''
        && this.attr("passwordCreate") != ''
        && this.attr("passwordRetypedCreate") != ''
        && this.attr("securityAnswerCreate").length >= config.general.securityAnswerMinLength) {

      if (!this.attr("secondFactorVisible") && (this.attr("hasEmail") || this.attr("hasPhone"))) this.attr("pleaseWaitMessage", true);

      var dataObj = {
        user: this.attr("userCreate"),
        email: this.attr("emailCreate"),
        phone: this.attr("phoneCreate"),
        question: this.attr("securityQuestionCreate"),
        answer: this.attr("securityAnswerCreate"),
        pass: restAPI.hashMe(this.attr("passwordCreate")),
        retype: restAPI.hashMe(this.attr("passwordRetypedCreate")),
        factor: this.attr("secondFactorSignUp"),
        lang: localStorage.getItem('locale'),
      };

      if (this.attr("hasEmail") && this.attr("hasPhone")) {
        dataObj.confirm = $('input[type=radio][name=confirm_with]:checked').val();
      }

      if (!this.attr("secondFactorVisible") || (this.attr("secondFactorVisible") && this.attr("secondFactorSignUp") != '')) {
        restAPI.requestUnsigned('POST', '/wapi/sign-up/', dataObj,
            res =>  {
              this.attr('generalError', false);
              if (this.attr("secondFactorVisible") || (!this.attr("hasEmail") && !this.attr("hasPhone"))) {
                $('#sign-up-modal').modal('hide');
                $('#login-modal').modal('show');
                this.attr("buttonRunning", null);
                this.attr("inputDisabled", null);

                // reset form
                this.attr("generalError", false);
                this.attr("userCreateError", false);
                this.attr("userCreateLengthError", false);
                this.attr("emailCreateError", false);
                this.attr("emailCreateInvalidError", false);
                this.attr("securityAnswerError", false);
                this.attr("passwordMismatchError", false);
                this.attr("passwordStrengthGood", false);
                this.attr("secondFactorVisible", false);
                this.attr("pleaseWaitMessage", false);
                this.attr("userCreate", '');
                this.attr("emailCreate", '');
                this.attr("phoneCreate", '');
                $('#phone_create').val('');
                this.attr("securityAnswerCreate", '');
                this.attr("passwordCreate", '');
                this.attr("secondFactorLogin", '');
                this.attr("passwordRetypedCreate", '');
                this.attr("passwordStrengthText", '');
                this.attr("secondFactorSignUp", '');
                this.attr("passwordStrengthColor", 'red-text');

              } else if (typeof res.secondFactor !== 'undefined' && res.secondFactor != '' && res.secondFactor) {
                this.attr("secondFactorVisible", true);
                this.attr("pleaseWaitMessage", false);
                this.attr("buttonRunning", null);
                this.attr("secondFactorDisabled", null);
              }
            },  () => {
              this.attr('generalError', true);
              this.attr("pleaseWaitMessage", false);
              this.attr("buttonRunning", null);
              this.attr("inputDisabled", null);
              this.attr("secondFactorDisabled", null);
            });
      } else {
        this.attr("buttonRunning", null);
      }
    } else if (this.attr("secondFactorVisible")) {
      this.attr("buttonRunning", null);
      this.attr("secondFactorDisabled", null);
    } else {
      this.attr("buttonRunning", null);
      this.attr("inputDisabled", null);
      this.attr("secondFactorDisabled", null);
    }

    return false;
  },
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

    if (pass2.length >= this.attr("passwordCreate").length && pass2 != this.attr("passwordCreate")) {
      this.attr("passwordMismatchError", true);
    } else {
      this.attr("passwordMismatchError", false);
    }

  },
  secondFactorSignUp: '',
  secondFactorVisible: false
});