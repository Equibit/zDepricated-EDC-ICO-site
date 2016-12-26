import can from 'can';
import 'can/map/define/';
import restAPI from 'rest-api';
import config from 'config';

export default can.Map.extend({
  define: {
    loadedData: {
      value: false
    },
    hasPhone: {
      value: config.general.requirePhone,
      serialize: false
    },
    textVariables: {
      value: []
    },
    useLanguages: {
      value: 'en'
    }
  },
  sendEmail(emailTemplate){
    this.attr("buttonRunning", true);
    var _self = this;
    restAPI.request('POST', '/wapi/admin-send-email/' + emailTemplate + '/', {useLanguages: _self.attr("useLanguages")},
      () => _self.attr("buttonRunning", false),
      () => _self.attr("buttonRunning", false))
  },
  sendSMS(smsTemplate){
    this.attr("buttonRunning", true);
    var _self = this;
    restAPI.request('POST', '/wapi/admin-send-sms/' + smsTemplate + '/', {useLanguages: _self.attr("useLanguages")},
      () => _self.attr("buttonRunning", false),
      () => _self.attr("buttonRunning", false))
  },
  deleteVariable(variable) {
    variable.destroy();
  }
});