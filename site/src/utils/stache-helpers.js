import i18n from 'easyapp/i18n/';
import moment from 'moment';

can.stache.registerSimpleHelper('lookup', key => {
  if (typeof i18n[key] != 'undefined') return i18n[key];
  else return key;
});

can.stache.registerSimpleHelper('leading', num => {
  let s = "000000000" + num;
  return s.substr(s.length-8);
});

can.stache.registerSimpleHelper('shorten', s => {
	if (s != 'undefined' && s) return s.substr(0,25);
  else return 'None';
});

can.stache.registerSimpleHelper('addCommas', num => {
  let nf = new Intl.NumberFormat();
  return nf.format(num)
});

can.stache.registerSimpleHelper('toBTC', num => {
  if (num != 'undefined' && num) return num / 100000000;
  else return 0
});

can.stache.registerSimpleHelper('cleanKey', str => {
  str = str.replace(/_/g, ' ');
  return str.replace(/\w\S*/g, txt => {return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
});

can.stache.registerSimpleHelper('timestamp', ts => {
  let format = i18n.attr('timestamp');
  return moment(ts * 1000).format(format);
});

can.stache.registerSimpleHelper('timestampDetailed', ts => {
  let format = i18n.attr('timestampDetailed');
  return moment(ts * 1000).format(format);
});