<template>
  <div class="d-flex justify-content-center py-3">
    <b-col v-if="!isLoading" lg="4" md="6" sm="8">
      <b-card class="shadow-sm mb-3">
        <div>
          <div>

            <p v-if="is_singkawang_domicile == 1">
              Jika terdapat rute penerbangan antara kota Singkawang dan kota tujuan anda, <b>{{city.description}}</b>, dengan waktu penerbangan :
            </p>
            <p v-if="is_singkawang_domicile == 0">
              Jika terdapat rute penerbangan antara kota Singkawang dan kota anda saat ini, <b>{{city.description}}</b>, dengan waktu penerbangan :
            </p>
            <div class="text-center">
              <p>
                <b>{{currentData.timeState.amount}} menit </b>
              </p>
            </div>
            <p v-if="categoryId == 1 ||  categoryId == 5">
              Apakah anda bersedia beralih menggunakan rute pesawat tersebut dengan biaya tiket seharga:
            </p>
            <p v-if="categoryId == 2 ||  categoryId == 6">
              Apakah anda bersedia beralih dari menggunakan ferry menjadi pesawat dengan rute tersebut dan
              <b>
                <span v-if="currentData.costState.amount>=0">tambahan</span>
                <span v-if="currentData.costState.amount<0">penghematan</span>
                biaya tiket sebesar:
              </b>
            </p>
            <p v-if="categoryId == 3 ||  categoryId == 7">
              Apakah anda bersedia beralih dari menggunakan bus menjadi pesawat dengan rute tersebut dan
              <b>
                <span v-if="currentData.costState.amount>=0">tambahan</span>
                <span v-if="currentData.costState.amount<0">penghematan</span>
              </b>
              biaya tiket sebesar:
            </p>
            <p v-if="categoryId == 4 || categoryId == 8">
              Apakah anda bersedia beralih dari menggunakan mobil/motor pribadi menjadi pesawat dengan rute tersebut dan biaya tiket seharga:
            </p>
            <div class="text-center">

              <p>
                <b>
                  Rp.
                  <transition name="slide-shrink-fade" mode="out-in">
                    <span :key="currentData.costState.amount">
                      {{currentData.costState.amount | absolute| currency }}
                    </span>
                  </transition>
                </b>
              </p>

            </div>
            <div v-if="categoryId == 2 ||  categoryId == 6">
              <small>
                <em>
                  *Asumsi: perjalanan ferry per orang per rute, tanpa membawa mobil

                  <span v-if="is_singkawang_domicile == 1">
                    menuju
                  </span>
                  <span v-if="is_singkawang_domicile == 0">
                    dari
                  </span>
                  DKI Jakarta (harga tiket ferry ± Rp 275.000)
                </em>
              </small>
            </div>
            <div class="w-100" role="group">
            </div>
            <b-btn class="btn-custom" variant="outline-warning" block @click="submit(1)">
              Ya, Bersedia
            </b-btn>
            <b-btn class="btn-custom" variant="outline-warning" block @click="submit(0)">
              Tidak
            </b-btn>
          </div>
        </div>
      </b-card>
    </b-col>
    <b-modal v-model="modalComplete" :no-close-on-esc="true" :hide-header-close="true" :no-close-on-backdrop="true" :hide-header="true" ok-only hide-footer centered>
      <lottie :options="defaultOptions" v-on:animCreated="handleAnimation" :height="300" />
      <h5 class="mb-3 text-primary">
        Selamat! Semua pertanyaan survei sudah selesai dijawab dengan
        baik.
      </h5>
      <b-btn variant="primary" class="float-right" @click="handleNext($route.query.token, 'ThankYou')">Lanjut</b-btn>
    </b-modal>
  </div>
</template>
<script>
  import {
    AuthRespondent
  } from '../../mixins/AuthRespondent.js'
  import {
    MethodsSurvey
  } from '../../mixins/Survey.js'
  import animationData from "../../asset/checked_blue_yellow.js";
  import {
    mapState,
  } from 'vuex'
  export default {
    name: 'SurveyPreference',
    mixins: [
      AuthRespondent,
      MethodsSurvey
    ],
    props: ["routerData"],
    data: function() {
      return {
        currentProgress: 0,
        defaultOptions: {
          animationData: animationData,
          loop: false
        },
        animationSpeed: 1,
        modalComplete: false,
        isLoading: false,
        city: {
          description: '',
          airport: ''
        },
        stateDataCollection: {
          respondent_id: null,
          data: []
        },
        categoryId: null,
        costIndex: null,
        timeIndex: null,
        currentData: {
          timeState: {},
          costState: {},
        },
        times: [{
          id: null,
          amount: null
        }],
        costs: [{
          id: null,
          amount: null
        }],
      }
    },
    created() {
      this.getPreferences()
      this.$emit("childinit", this.routerData);
      this.currentProgress = this.routerData.progress
    },
    watch: {
      timeIndex(newVal, oldVal) {
        this.currentData.timeState = this.times[newVal - 1];
      },
      costIndex(newVal, oldVal) {
        let self = this;

        if (this.costIndex > this.costs.length) {
          this.submitStateCollection();
        } else {
          self.currentData.costState = self.costs[newVal - 1];
        }
      }
    },
    computed: {
      ...mapState({
        is_singkawang_domicile: state => state.respondent.is_singkawang_domicile,

      })
    },
    methods: {
      handleAnimation: function(anim) {
        this.anim = anim;
      },
      handleNext(token, routeName) {
        this.$router.replace({
          name: routeName,
          query: {
            token: token
          }
        });
      },
      submitStateCollection() {
        this.$store.dispatch("isLoading", true);
        this.$emit("childinit", {
          progress: 100
        });
        let self = this;
        axios
          .post(
            `/respondent/survey/preferences-data?token=${this.$route.query.token}`,
            self.stateDataCollection
          )
          .then(response => {
            console.log(response.data);
            this.$store.dispatch("storeToken", response.data);
            // this.handleNext(response.data.token, response.data.step);
            this.modalComplete = true;

            this.$store.dispatch("isLoading", false);

          })
          .catch(error => {
            console.log(error);
          });
      },
      submit(respond) {
        let self = this;
        this.currentProgress += 2
        this.$emit("childinit", {
          progress: this.currentProgress
        });
        let costId = this.currentData.costState.id;
        let timeId = this.currentData.timeState.id;
        this.stateDataCollection.data.push({
          cost_id: costId,
          time_id: timeId,
          respond: respond
        });
        respond ? self.costIndex++ : this.submitStateCollection();
        // console.table(this.stateDataCollection.data);
      },
      getPreferences() {
        this.isLoading = true;

        axios.get(`/respondent/survey/preferences-data?token=${this.$route.query.token}`)
          .then((response) => {
            console.log(response.data)
            this.times = response.data.time_preference
            this.costs = response.data.cost_preference
            this.city = response.data.city
            this.categoryId = response.data.category_id
            this.timeIndex = 1
            this.costIndex = 1
            this.isLoading = false;

          })
          .catch((error) => {
            console.log(error);
          })
      },
    },
  }
</script>
<style>
</style>
