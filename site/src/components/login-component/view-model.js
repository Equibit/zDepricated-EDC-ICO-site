import can from 'can';
import 'can/map/define/';
import i18n from 'easyapp/i18n/i18n';
import restAPI from 'rest-api';

export default can.Map.extend({
  isLoggedIn: null,
  i18n: i18n,
  buttonRunning: null,
  inputDisabled: null,
  secondFactorDisabled: null,
  loginErrorVisible: false,
  secondFactorVisible: false,
  userLogin: '',
  passwordLogin: '',
  secondFactorLogin: '',
  loginFunc() {
    this.attr("buttonRunning", 'disabled');
    this.attr("inputDisabled", 'disabled');
    this.attr("secondFactorDisabled", 'disabled');

    if (this.attr("userLogin") != '' && this.attr("passwordLogin") != '') {

      this.attr("loginErrorVisible", false);
      if (this.attr("secondFactorVisible") && this.attr("secondFactorLogin") != '') {
        restAPI.addSecondFactor(this.attr("secondFactorLogin"));
        restAPI.request('GET', '/wapi/check-login/', {},
            () => {
              this.attr("isLoggedIn", true);
              this.attr("buttonRunning", null);
              this.attr("inputDisabled", null);
              this.attr("secondFactorDisabled", null);
              this.attr("userLogin", "");
              this.attr("passwordLogin", "");
              if ($('#checkbox_share_login').prop('checked')) restAPI.switchStoredDataLocally();
              $('#login-modal').modal('hide');
            }, () => {
              this.attr("loginErrorVisible", true);

              // reset form
              this.attr("secondFactorVisible", false);
              this.attr("secondFactorLogin", '');

              this.attr("buttonRunning", null);
              this.attr("inputDisabled", null);
              this.attr("secondFactorDisabled", null);
            });
      } else if (!this.attr("secondFactorVisible")) {
        restAPI.addAuthUser(this.attr("userLogin"));
        restAPI.passwordToSecret(this.attr("passwordLogin"));
        restAPI.request('POST', '/wapi/initiate/', {},
            res => {
              if (typeof res.secondFactor !== 'undefined' && res.secondFactor != '' && res.secondFactor) {
                this.attr("secondFactorVisible", true);
                this.attr("buttonRunning", null);
                this.attr("secondFactorDisabled", null);
              } else {
                this.attr("isLoggedIn", true);
                this.attr("buttonRunning", null);
                this.attr("inputDisabled", null);
                this.attr("secondFactorDisabled", null);
                this.attr("userLogin", "");
                this.attr("passwordLogin", "");
                if ($('#checkbox_share_login').prop('checked')) restAPI.switchStoredDataLocally();
                $('#login-modal').modal('hide');
              }

              this.attr("loginErrorVisible", false);
            }, () => {
              this.attr("loginErrorVisible", true);

              this.attr("buttonRunning", null);
              this.attr("inputDisabled", null);
              this.attr("secondFactorDisabled", null);
            });
      } else {
        this.attr("buttonRunning", null);
        this.attr("secondFactorDisabled", null);
      }
    } else {
      this.attr("buttonRunning", null);
      this.attr("inputDisabled", null);
      this.attr("secondFactorDisabled", null);
    }
    return false;

  },
  openForgotPassword() {
    $('#login-modal').modal('hide');
    setTimeout(() => $('#forgot-password-modal').modal('show'), 300);
    return false;
  }
});