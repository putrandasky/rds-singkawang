import Lottie from 'vue-lottie'
import router from './router'
import {
  store
} from './store';
import './filter';
require('./bootstrap');

window.Vue = require('vue').default;
Vue.use(BootstrapVue);
window.dayjs = require('dayjs');

Vue.component('lottie', Lottie);

const app = new Vue({
  el: '#app',
  store,
  router,
  render: h => h(App),
});
