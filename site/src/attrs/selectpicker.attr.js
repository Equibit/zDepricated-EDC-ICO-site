import can from 'can';
import isSsr from 'easyapp/utils/isSsr';
import 'bootstrap-select';

/**
 * A bridge to 3rd party selectpicker. This will instantiate the plugin for the element base on "selectpicker" attribute.
 * Example:
 * ```
 *    <select selectpicker="large" class="selectpicker show-tick">
 *      {{#each ../availableFactors}}
 *        <option data-subtext="{{lookup factorDesc i18n}}">{{lookup factorType i18n}}</option>
 *      {{/each}}
 *    </select>
 * ```
 */
can.view.attr('selectpicker', (el, attrData) => {
  if (isSsr){
    return;
  }

  let $el = $(el),
      value = el.getAttribute('selectpicker'),
      style = value === 'large' ? 'btn-form btn-sm' : 'btn-form btn-xs';

  // todo: if on mobile add .selectpicker('mobile');

  setTimeout(() => $el.selectpicker({style: style, showSubtext: true}), 0);

  $el.on('reset-select-picker', function() {
    $el.find('option').each((i,a) => {
      if (a.getAttribute('data-subtext')) {
        $(a).data('subtext', a.getAttribute('data-subtext'))
      }
    });
    $el.selectpicker('refresh');
  })
});