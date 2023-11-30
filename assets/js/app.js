import '../css/app.scss';

const $ = require('jquery');
global.$ = global.jQuery = $;
const tus = require("tus-js-client");
global.tus = tus;

require('bootstrap');
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');
require('select2');
require('select2/dist/css/select2.css');
