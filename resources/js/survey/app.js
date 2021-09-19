import App from './views/App';
import Lottie from 'vue-lottie'
import router from './router'
import {
  store
} from './store';
import './filter';
import BootstrapVue from 'bootstrap-vue';
import VueSlider from 'vue-slider-component'
import Loading from 'vue-loading-overlay';

// import 'vue-slider-component/theme/default.css'
require('./bootstrap');

window.Vue = require('vue').default;
Vue.use(BootstrapVue);
window.dayjs = require('dayjs');
Vue.component('loading', Loading);

Vue.component('lottie', Lottie);
Vue.component('VueSlider', VueSlider)

const app = new Vue({
  el: '#app',
  store,
  router,
  render: h => h(App),
});
