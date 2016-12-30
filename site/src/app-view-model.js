import can from 'can';
import $ from 'jquery';
import 'can/map/define/';
import 'can/route/pushstate/';
import i18n from 'easyapp/i18n/i18n';
import { setLang } from 'easyapp/i18n/i18n';
import { translate } from 'easyapp/i18n/i18n';
import restAPI from 'rest-api';
import config from 'config';
import AccountSettings from 'easyapp/models/account-settings/';
import SystemVariables from 'easyapp/models/system-variables/';
import contentTemplate from './app.stache!';

const AppViewModel = can.Map.extend({
  contentTemplate: contentTemplate,
  define: {
    ref: {
      value: null,
      serialize: false
    },
    hasChat: {
      value: config.general.hasChat,
      serialize: false
    },
    hasSubUsers: {
      value: config.general.hasSubUsers,
      serialize: false
    },
    hasAPI: {
      value: config.general.hasAPI,
      serialize: false
    },
    hasBlog: {
      value: config.general.hasBlog,
      serialize: false
    },
    hasFAQs: {
      value: config.general.hasFAQs,
      serialize: false
    },
    hasEmail: {
      value: config.general.requireEmail,
      serialize: false
    },
    hasPhone: {
      value: config.general.requirePhone,
      serialize: false
    },
    loaded: {
      type: 'boolean',
      value: false,
      serialize: false
    },
    page: {
      serialize: false
    },
    lang: {
      value: typeof localStorage !== 'undefined' && (localStorage.getItem('locale')?localStorage.getItem('locale'):'en'),
      serialize: false,
      set(newVal) {
        setTimeout(() => $('.selectpicker').trigger('reset-select-picker'), 100);
        return newVal;
      }
    },
    isAccountReady: {
      value: false,
      serialize: false
    },
    isLoggedIn: {
      type: 'boolean',
      value: false,
      serialize: false,
      set(newValue){
        if (newValue) {
          if (newValue) this.attr("page", 'pageHome');
          this.loadAccountSettings().then(() => {
            let lang = this.attr('accountSettings.baseLang');
            this.selectLanguage(this.attr('languages'), lang);
            return this.loadSystemVariables();
          }).then(() => {
            this.attr('hasErrorMessage', false);
            this.attr('isAccountReady', true);
            this.attr('loaded', true);
          }).fail(err => {
            console.log('ERROR', err);
          });
        } else {
          this.attr('isAccountReady', false);
        }
        return newValue;
      }
    },
    i18n: {
      value: i18n,
      serialize: false
    },
    languages: {
      value: config.languages,
      serialize: false
    },
    accountSettings: {
      serialize: false
    },
    systemVariables: {
      serialize: false,
      get(val){
        if (val){
          let lang = this.attr('lang');
          val.attr('availableFactors', val.attr('availableFactors').map(a => {
            a.attr('factorDescValue', translate(a.factorDesc));
            a.attr('factorTypeValue', translate(a.factorType));
            return a;
          }));
          val.attr('availableRoles', val.attr('availableRoles').map(a => {
            a.attr('roleNameValue', translate(a.roleName));
            a.attr('roleDescValue', translate(a.roleDesc));
            return a;
          }));
          return val;
        }
      }
    },
    hasErrorMessage: {
      type: 'boolean',
      value: false,
      serialize: false
    },
    errorMessage: {
      type: 'string',
      value: '',
      serialize: false
    },
    initApp: {
      get(){
        this.start();
        return '';
      }
    }
  },
  start(){
    restAPI.addDomain(config.server.domain);

    restAPI.addLogoutFunction(() => {
      $('.modal').modal('hide');
      setTimeout(() => {
        this.attr("errorMessage", i18n.sessionExpired);
        this.attr("isLoggedIn", false);
        this.attr("hasErrorMessage", true);
      }, 500);
    });

    restAPI.request('GET', '/wapi/check-login/', {},
      () => {
        this.attr("page", 'pageHome');
        this.attr("isLoggedIn", true);
      },
      () => {
        restAPI.logout();
        this.attr('loaded', true);
      });
  },
  logoutFunc(){
    restAPI.logout();
    this.attr({
      isLoggedIn: false,
      page: 'pageHome'
    });
    return false;
  },
  switchLang(code) {
    this.attr("lang", code);
    $("#langDropDown").dropdown("toggle");
    setLang(code);
    return false;
  },
  loadAccountSettings(){
    return AccountSettings.findOne({}).then(data => this.attr('accountSettings', data));
  },
  loadSystemVariables(){
    return SystemVariables.findOne({}).then(data => this.attr('systemVariables', data));
  },
  selectLanguage(languages, lang){
    languages.forEach(item => item.attr('selected', item.code == lang));
    this.switchLang(lang);

    return true;
  },
  switchPage(page){
    this.attr("page", page);
    if ($('.navbar-toggle').is(':visible') && $('.navbar-collapse').hasClass('in')) $('.navbar-toggle').trigger('click');
  }
});

can.route(':page', {page: 'pageHome'});

export default AppViewModel;