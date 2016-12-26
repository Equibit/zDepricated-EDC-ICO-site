import 'can/map/define/';
import restAPI from 'rest-api';

let UserModel = can.Model.extend({
  findAll(){
    return restAPI.requestPromise('GET', '/wapi/manage-child-users/', {});
  },
  create(attrs){
    return restAPI.requestPromise('POST', '/wapi/manage-child-users/', attrs);
  },
  update(id, attrs){
    if (!attrs.password){
      delete attrs.password;
    }
    return restAPI.requestPromise('POST', '/wapi/manage-child-users/' + id + '/', attrs);
  },
  destroy(id){
    return restAPI.requestPromise('DELETE', '/wapi/manage-child-users/' + id + '/', {});
  }
}, {
  define: {
    userName: {
      value: '',
      type: 'string',
      set(val){
        return val && val.toLowerCase();
      }
    },
    email: {
      value: '',
      type: 'string',
      set(val){
        return val && val.toLowerCase();
      }
    },
    password: {
      get(){
        return this.attr('passwordNew') && restAPI.hashMe(this.attr('passwordNew'));
      },
      serialize: true
    },
    twoFactorType: {
      value: '0',
      type: 'string'
    },
    roles: {
      value: []
    },
    isSaving: {
      serialize: false
    },
    passwordNew: {
      value: '',
      serialize: false
    },
    passwordRetyped: {
      value: '',
      serialize: false
    },
    passwordStrengthText: {
      serialize: false
    },
    passwordStrengthColor: {
      value: 'red-text',
      serialize: false
    },
    passwordMismatchError: {
      serialize: false
    },
    generalError: {
      serialize: false
    },
    inputDisabled: {
      value: false,
      serialize: false
    },
    successMessage: {
      value: false,
      serialize: false
    },
    emailError: {
      serialize: false
    },
    emailInvalidError: {
      serialize: false
    },
    userNameError: {
      serialize: false
    },
    userNameLengthError: {
      serialize: false
    }
  }
});

export default UserModel;
