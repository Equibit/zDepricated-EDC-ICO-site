import $ from 'jquery';
import can from 'can';

can.view.attr('scrollstop', function(el){
  $(el).on('wheel', function (e) {
    var $this = $(this);
    if (e.originalEvent.deltaY < 0) {
      /* scrolling up */
      return ($this.scrollTop() > 0);
    } else {
      /* scrolling down */
      return ($this.scrollTop() + $this.innerHeight() < $this[0].scrollHeight);
    }
  });
});