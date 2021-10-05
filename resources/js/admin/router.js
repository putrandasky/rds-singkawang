import Vue from 'vue';
import Router from 'vue-router';
import Dashboard from './views/dashboard'


Vue.use(Router);

const router = new Router({
  mode: 'history',
  //   history: true,
  //   hashbang: false,
  //   base: '',
  //   linkActiveClass: 'open active',
  // scrollBehavior: () => ({
  //   y: 0
  // }),
  routes: [{
      path: "/admin/dashboard",
      component: Dashboard,

    }

  ]
});
router.mode = 'html5'

export default router
