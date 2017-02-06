import $ from 'jquery';
import config from 'config';

if (config.analytics != '') {
	$.getScript('//www.google-analytics.com/analytics.js');
	window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
	ga('create', config.analytics, 'auto');
	ga('send', 'pageview');
}
