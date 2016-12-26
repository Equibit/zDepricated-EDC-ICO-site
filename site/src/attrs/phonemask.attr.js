import can from 'can';
import isSsr from 'easyapp/utils/isSsr';
import Numbered from 'input.numbered';
import config from 'config';

can.view.attr('phonemask', (el, attrData) => {
  if (isSsr){
    return;
  }

  new Numbered(el, {
    mask: config.general.phoneMask,
    empty: '_'
  });

});