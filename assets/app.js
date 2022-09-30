/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application

import './js/collapse.js';
import './js/modal.js';
import './js/swiper.js';
import './js/dropdown.js';
import './js/user.js';
import './js/paralax.js';
import './bootstrap.js';

import Filter from './js/filter';
new Filter(document.querySelector('.js-filter'));
