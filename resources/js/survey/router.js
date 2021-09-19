import Vue from 'vue';
import Router from 'vue-router';
import AppPage from './views/layout/Page'
import AppSurvey from './views/layout/Survey'


import Home from './views/page/Home'
import ThankYou from './views/page/ThankYou'
import Ending from './views/page/Ending'

import SocialData from './views/survey/SocialData'
import TravelData from './views/survey/TravelData'
import ScreeningData from './views/survey/ScreeningData'
import SurveyPrefernce from './views/survey/SurveyPreference'

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
      path: "/survey",
      redirect: '/home',
      component: AppSurvey,
      children: [{
          path: 'social-data',
          name: 'SocialData',
          component: SocialData,
          props: {
            routerData: {
              progress: 0
            }
          },
        },
        {
          path: 'screening-data',
          name: 'ScreeningData',
          component: ScreeningData,
          props: {
            routerData: {
              progress: 40
            }
          },
        },
        {
          path: 'travel-data',
          name: 'TravelData',
          component: TravelData,
          props: {
            routerData: {
              progress: 50
            }
          },
        },
        {
          path: 'preference',
          name: 'Preference',
          component: SurveyPrefernce,
          props: {
            routerData: {
              progress: 75
            }
          },
        },
      ]
    },
    {
      path: "",
      redirect: '/home',
      component: AppPage,
      props: {
        routerData: {
          background: "bg-home",
        }
      },
      children: [{
        path: '/home',
        name: 'Home',
        component: Home,
      }, ]

    },
    {
      path: "/thank-you",
      component: AppPage,
      props: {
        routerData: {
          background: "bg-end",
        }
      },
      children: [{
        path: '',
        name: 'ThankYou',
        component: ThankYou,
      }, ]

    },
    {
      path: "/ending",
      component: AppPage,
      props: {
        routerData: {
          background: "bg-end",
        }
      },
      children: [{
        path: '',
        name: 'Ending',
        component: Ending,
      }, ]

    },


  ]
});
router.mode = 'html5'

export default router
