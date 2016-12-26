import can from 'can';
import 'can/map/define/';
import i18n from 'easyapp/i18n/i18n';
import { setLang } from 'easyapp/i18n/i18n';
import restAPI from 'rest-api';
import ChildUser from 'easyapp/models/child-user/';
import templateAddUser from './template-add-user.stache!';

export default can.Map.extend({
  templateAddUser: templateAddUser,
  define: {
    myUsers: {
      value: []
    },
    userCountAboveTen: {
      get(){
        return this.attr('myUsers.length') >= 10;
      }
    },
    newUser: {
      value: new ChildUser({}),
      Type: ChildUser
    }
  },
  languages: null,
  i18n: i18n,
  buttonRunning: null,
  inputDisabled: null,
  loadingData: true,
  availableRoles: null,
  addUser(user) {
    user.save(savedUser => {
      // Clear password and add to the table:
      savedUser.attr({passwordNew: '', passwordRetyped: ''});
      this.attr('myUsers').push(savedUser);

      $('#add-user-modal').modal('hide');
      setTimeout(() => $('#manage-users-modal').modal('show'), 500);
    });
    // todo: finish this, also check that username and email is unique
  },
  delUser(user) {
    user.attr('deleting', true);
    user.destroy();
    return false;
  },
  strongPassFunc(pass, user) {
    if (pass != '') {
      var strongRegex = new RegExp("^(?=.{12,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
      var mediumRegex = new RegExp("^(?=.{10,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
      var enoughRegex = new RegExp("(?=.{8,}).*", "g");
      if (false == enoughRegex.test(pass)) {
        user.attr("passwordStrengthText", i18n.longerPasswordNeeded);
        user.attr("passwordStrengthColor", 'orange-text');
      } else if (strongRegex.test(pass)) {
        user.attr("passwordStrengthText", i18n.strongPassword);
        user.attr("passwordStrengthColor", 'green-text');
      } else if (mediumRegex.test(pass)) {
        user.attr("passwordStrengthText", i18n.okPassword);
        user.attr("passwordStrengthColor", 'black-text');
      } else {
        user.attr("passwordStrengthText", i18n.weakPassword);
        user.attr("passwordStrengthColor", 'orange-text');
      }
    }
  },
  comparePasswordFunc(pass2, user) {
    let passwordMismatchError = (pass2.length >= user.attr('passwordNew').length && pass2 != user.attr('passwordNew'));
    user.attr({ passwordMismatchError });
  },
  changePassword(user) {
    user.attr("inputDisabled", 'disabled');
    user.attr("successMessage", false);
    user.attr("generalError", false);

    if (user.attr("passwordNew") != ''
        && user.attr("passwordRetyped") != ''
        && user.attr("passwordNew") == user.attr("passwordRetyped")) {

      user.save().then(() => {
        user.attr("inputDisabled", null);
        user.attr("successMessage", true);
      }).fail(() => {
        user.attr("inputDisabled", null);
        user.attr("generalError", true);
      });
    } else {
      user.attr("inputDisabled", null);
    }
    return false;
  },
  openAddUser() {
    this.clearUser();
    $('#manage-users-modal').modal('hide');
    setTimeout(() => $('#add-user-modal').modal('show'), 500);
  },
  cancelAddUser() {
    $('#add-user-modal').modal('hide');
    setTimeout(() => $('#manage-users-modal').modal('show'), 500);
  },
  clearUser(){
    this.attr('newUser', {});
  },
  usernameCheckFunc(user) {
    // todo: accept only letters numbers underscore and hyphens

    if (user.attr("userName").length < 8 && user.attr("userName") != '') {
      user.attr("userNameLengthError", true);
      user.attr('userNameError', false);
    } else if (this.attr("userName") != '') {
      user.attr("userNameLengthError", false);

      if (user.attr('userName') != '') {
        restAPI.requestUnsigned('GET', '/wapi/check-username/' + user.attr('userName') + '/', {},
            () => {
              user.attr('userNameError', false);
            }, () => {
              user.attr('userNameError', true);
            });
      }
    } else {
      user.attr("userNameLengthError", false);
      user.attr('userNameError', false);
    }
  },
  emailCheckFunc(user) {
    if (user.attr("email") != '') {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      if (re.test(user.attr("email")) && user.attr("email") != '') {
        user.attr("emailInvalidError", false);
        if (user.attr('email') != '') {
          restAPI.requestUnsigned('GET', '/wapi/check-email/' + user.attr('email') + '/', {},
              () =>{
                user.attr('emailError', false);
              }, () => {
                user.attr('emailError', true);
              });
        }
      } else {
        user.attr('emailError', false);
        user.attr("emailInvalidError", true);
      }
    } else {
      user.attr('emailError', false);
      user.attr("emailInvalidError", false);
    }
  },
  saveUser(user) {
    user.attr('passwordNew', '');
    user.attr('isSaving', true);
    user.save(() => {
      user.attr('isSaving', false);
    }, () => {
      user.attr('isSaving', false);
    });

    return false;
  },
  collapse(target, close, index) {
    $('#' + close + index).collapse('hide');
    $('#' + target + index).collapse('toggle');
    return false;
  }
});