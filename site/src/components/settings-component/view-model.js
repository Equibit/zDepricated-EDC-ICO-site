import can from 'can';
import 'can/map/define/';
import i18n from 'easyapp/i18n/i18n';
import { setLang } from 'easyapp/i18n/i18n';
import restAPI from 'rest-api';
import config from 'config';

export default can.Map.extend({
    languages: null,
    i18n: i18n,
    hasEmail: config.general.requireEmail,
    hasPhone: config.general.requirePhone,
    buttonRunning: null,
    inputDisabled: null,
    generalError: false,
    successMessage: false,
    lang: typeof localStorage !== 'undefined' && localStorage.getItem('locale'),
    newLanguage: (typeof localStorage !== 'undefined' ? localStorage.getItem('locale') : 'en'),
    saveSettings() {

        this.attr("buttonRunning", 'disabled');
        this.attr("inputDisabled", 'disabled');
        this.attr("successMessage", false);
        this.attr("generalError", false);

        if (!this.attr("generalError")) {

            var dataObj = {
                twoFactorType: this.attr("accountSettings.twoFactorType"),
                baseLang: this.attr("newLanguage"),
                emailNotifications: this.attr("accountSettings.emailNotifications"),
                phoneNotifications: this.attr("accountSettings.phoneNotifications")
            };

            restAPI.request('POST', '/wapi/account-settings/', dataObj,
                () => {
                    this.attr("buttonRunning", null);
                    this.attr("inputDisabled", null);
                    this.attr("successMessage", true);

                    setLang(this.attr("newLanguage"));
                    this.attr("lang", this.attr("newLanguage"));

                    $('#settings-modal').modal('hide');
                }, () =>{
                    this.attr("buttonRunning", null);
                    this.attr("inputDisabled", null);
                    this.attr("generalError", true);
                });
        } else {
            this.attr("buttonRunning", null);
            this.attr("inputDisabled", null);
        }
        return false;

    }
});