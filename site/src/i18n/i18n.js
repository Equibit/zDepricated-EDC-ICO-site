import can from 'can';
import en from './en';
import fr from './fr';
import moment from 'moment';

// Don't forget to load supported locales!
import 'moment/locale/fr';

window.moment = moment;

let i18n = new can.Map(en);

let userLang = typeof localStorage !== 'undefined' && localStorage.getItem('locale');
if (userLang){
  setLang(userLang);
}

function setLang(lang){
  let langOptions;
  switch (lang){
    case 'fr':
      langOptions = fr;
      break;
    default:
      langOptions = en;
  }
  can.batch.start();
  i18n.attr(en);
  i18n.attr(langOptions);

  // tell momentjs about new locale:
  moment.locale(lang);

  can.batch.stop();

  if (typeof localStorage !== 'undefined') localStorage.setItem('locale', lang);
}

function translate(term, silent){
  return i18n.attr(term) || term + (silent ? '' : ' (!i18n!)');
}

window.setLang = setLang;

export default i18n;
export { setLang };
export { translate };