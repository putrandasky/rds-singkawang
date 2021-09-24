import App from './views/App';
import router from './router'
import {
  store
} from './store';
import './filter';
import BootstrapVue from 'bootstrap-vue';
import Loading from 'vue-loading-overlay';

// import 'vue-slider-component/theme/default.css'
require('./bootstrap');

window.Vue = require('vue').default;
Vue.use(BootstrapVue);
window.dayjs = require('dayjs');
Vue.component('loading', Loading);


const app = new Vue({
  el: '#app',
  store,
  router,
  render: h => h(App),
});
