import Vue from 'vue';
import Vuex from 'vuex';
// import Cookies from "js-cookie";
// import router from './router/index';

Vue.use(Vuex);

const RESPONDENT = "RESPONDENT";
const ISLOADING = "ISLOADING";
const ISPAGELOADED = "ISPAGELOADED";

export const store = new Vuex.Store({
  state: {
    isLoading: false,
    isPageLoaded: false,
    respondent: {
      token: null,
      is_singkawang_domicile: null,
      singkawang_related: null,
      singkawang_related_potentially: null,
      city_id: null,
      category_id: null,
      income: {
        min: 0,
        max: 0
      },
      step: {
        id: null,
        name: ""
      }
    }
  },
  mutations: {

    [RESPONDENT](state, n) {
      state.respondent.token = n.token;
      state.respondent.is_singkawang_domicile = n.is_singkawang_domicile;
      state.respondent.singkawang_related = n.singkawang_related;
      state.respondent.singkawang_related_potentially = n.singkawang_related_potentially;
      state.respondent.step = n.step;
      state.respondent.income.min = n.income.min;
      state.respondent.income.max = n.income.max;
      state.respondent.city_id = n.city_id;
      state.respondent.category_id = n.category_id;
    },
    [ISLOADING](state, n) {
      state.isLoading = n;
    },
    [ISPAGELOADED](state, n) {
      state.isPageLoaded = n;
    },

  },
  actions: {
    isLoading({
      commit
    }, isLoading) {
      commit(ISLOADING, isLoading)
    },
    isPageLoaded({
      commit
    }, isPageLoaded) {
      commit(ISPAGELOADED, isPageLoaded)
    },
    storeToken({
      commit
    }, creds) {
      let respondent_data = {
        token: creds.token,
        step: creds.step,
        is_singkawang_domicile: creds.is_singkawang_domicile,
        singkawang_related: creds.singkawang_related,
        singkawang_related_potentially: creds.singkawang_related_potentially,
        income: {
          min: creds.income.min,
          max: creds.income.max
        },
        city_id: creds.city_id,
        category_id: creds.category_id,
        // transportation_mode_id: creds.transportation_mode_id,
      }
      console.log(creds);
      commit(RESPONDENT, respondent_data);
    },
    checkExist({
      commit
    }) {
      // console.log(typeof this.$route.query.token === 'undefined');
      let token = typeof store.state.route.query.token === 'undefined' ? false : store.state.route.query.token
      if (token) {
        axios.get(`survey/form/auth-respondent?token=${token}`)
          .then((response) => {
            console.log(response.data)
            if (response.data.status == "exist") {
              console.log('exist');
              store.dispatch('storeToken', response.data)
              // this.handleRoute(response.data.token, response.data.step.name)
            } else {
              console.log('new');
              store.dispatch('storeToken', response.data)

            }
          })
          .catch((error) => {
            console.log(error);
          })
      } else {
        axios.get(`survey/form/auth-respondent`)
          .then((response) => {
            console.log('new');
            store.dispatch('storeToken', response.data)

          })
          .catch((error) => {
            console.log(error);
          })


      }

    },


  },
  getters: {
    category: state => {
      return state.respondent.category
    },
    token: state => {
      return state.respondent.token
    },

    step: state => {
      return state.respondent.step
    },
  }
});
