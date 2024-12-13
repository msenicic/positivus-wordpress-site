// Import scripts
//import './vendor/swiper-bundle.min.js';
// Styles
import '../sass/main.scss';

// Import asset images
// import './images';

// Import javascript
// import debounce from './helpers/debounce';
import activeTrigger from './components/utilities/activeTrigger';
import siteNav from './components/siteNav';
import swiperSettings from './components/swiperSettings';
import services from './components/blocks/services';
import processes from './components/blocks/processes';
import header from './components/header';
import smoothScroll from './helpers/smoothScroll';
import form from './components/blocks/form';

jQuery(document).ready(function() {
  header(jQuery);
  //activeTrigger();
  //siteNav(jQuery);
  swiperSettings();
  services(jQuery);
  processes(jQuery);
  smoothScroll(jQuery);
  form(jQuery);
});
