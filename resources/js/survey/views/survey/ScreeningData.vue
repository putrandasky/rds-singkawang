<template>
  <div class="full-bg-survey bg-survey d-flex justify-content-center py-3">

    <b-col v-if="step == 1" lg="4" md="6" sm="8">
      <b-card class="shadow-sm mb-3">
        <div>
          <p>
            Sebelum adanya pandemic Covid 19, Dalam satu tahun, berapa kali kamu melakukan perjalanan menggunakan pesawat?
          </p>
          <vue-slider v-model="input.travel_frequence" :height="6" :min="0" :max="365" :interval="1" />

        </div>
      </b-card>
      <b-btn variant="primary" class="font-weight-bold" block @click="nextStep(2,43)">Lanjut</b-btn>
    </b-col>
    <b-col v-if="step == 2 " lg="4" md="6" sm="8">
      <b-card class="shadow-sm mb-3">
        <div>
          <p>
            Berapa alokasi rata-rata biaya <b>transportasi udara</b> untuk tiap perjalanan pada pertanyaan sebelumnya?
          </p>
          <vue-slider v-model="input.avg_airplane_transportation_cost" :height="6" :min="0" :max="100" :interval="1" :tooltip-formatter="sliderTooltipFormat">

          </vue-slider>
          <div class="text-center">
            <span v-if="income.max == null">≤</span>
            <span v-if="income.min != null"> Rp. {{income.min * input.avg_airplane_transportation_cost/100 | currency}}</span>

            <span v-if="income.min != null && income.max != null"> - </span>
            <span v-if="income.min == null">></span>

            <span v-if="income.max != null"> Rp. {{income.max * input.avg_airplane_transportation_cost/100 | currency}}</span>
          </div>
        </div>
      </b-card>
      <b-btn v-if="input.avg_airplane_transportation_cost !=null" variant="primary" class="font-weight-bold" block @click="nextStep(3,46)">Lanjut</b-btn>
    </b-col>
    <b-col v-if="step == 3" lg="4" md="6" sm="8">
      <b-card no-body class="shadow-sm mb-3">
        <!-- <iframe class="travel-map-mymap p-0" :src="selectedData.src" style="position:relative; top:-55px; border:none;margin-bottom:-60px"></iframe> -->
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d685971.4485784924!2d109.17237850745909!3d0.8318165792914!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sid!2sid!4v1632024069947!5m2!1sid!2sid" class="travel-map-mymap" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        <b-card-body>
          <p>
            Sebelum adanya pandemic Covid 19, pernahkan anda bepergian dari atau menuju Kota Singkawang dan sekitarnya?
          </p>
          <b-form-radio-group stacked v-model="input.singkawang_related" button-variant="outline-warning" buttons class="btn-block">
            <b-form-radio v-if="is_singkawang_domicile == 1" :value="1">Pernah/sering bepergian dari Singkawang</b-form-radio>
            <b-form-radio v-if="is_singkawang_domicile == 0" :value="2">Pernah/sering bepergian menuju Singkawang</b-form-radio>
            <b-form-radio :value="0">Tidak pernah</b-form-radio>
          </b-form-radio-group>

        </b-card-body>
      </b-card>
      <b-btn v-if="input.singkawang_related != null" variant="primary" class="font-weight-bold" block @click="nextStep(4,48)">Lanjut</b-btn>
    </b-col>
    <b-col v-if="step == 4" lg="4" md="6" sm="8">
      <b-card no-body class="shadow-sm mb-3">
        <!-- <iframe class="travel-map-mymap p-0" :src="selectedData.src" style="position:relative; top:-55px; border:none;margin-bottom:-60px"></iframe> -->
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d685971.4485784924!2d109.17237850745909!3d0.8318165792914!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sid!2sid!4v1632024069947!5m2!1sid!2sid" class="travel-map-mymap" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        <b-card-body>
          <p>
            Di waktu yang mendatang, akankah anda berpotensi bepergian dari atau menuju Kota Singkawang dan sekitarnya?
          </p>
          <b-form-radio-group stacked v-model="input.singkawang_related_potentially" button-variant="outline-warning" buttons class="btn-block">
            <b-form-radio v-if="is_singkawang_domicile == 1" :value="1">akan bepergian dari Singkawang</b-form-radio>
            <b-form-radio v-if="is_singkawang_domicile == 0" :value="2">akan bepergian menuju Singkawang</b-form-radio>
            <b-form-radio :value="0">Tidak akan</b-form-radio>
          </b-form-radio-group>

        </b-card-body>
      </b-card>
      <b-btn v-if="input.singkawang_related_potentially != null" variant="primary" class="font-weight-bold" block @click="submit">Lanjut</b-btn>
    </b-col>
  </div>
</template>
<script>
  import {
    mapState,
  } from 'vuex'
  import {
    AuthRespondent
  } from '../../mixins/AuthRespondent.js'
  import {
    MethodsSurvey
  } from '../../mixins/Survey.js'

  export default {
    name: 'ScreeningData',
    mixins: [
      AuthRespondent,
      MethodsSurvey
    ],
    props: ["routerData"],

    data: function() {
      return {
        submitting: false,
        srcMap: "https://www.google.com/maps/d/embed?mid=1htfGOJfkOLbEf-lGl5fMDCPWA0j1krpW&ll=-6.185622133940079%2C106.82362947503714&z=11",
        step: 1,
        sliderTooltipFormat: '{value}%',
        input: {
          travel_frequence: 0,
          avg_airplane_transportation_cost: null,
          singkawang_related: null,
          singkawang_related_potentially: null,
        }
      }
    },
    created() {
      this.$emit("childinit", this.routerData);

    },
    watch: {
      //   step(newVal, oldVal) {
      //     console.log(newVal);
      //     if (newVal == 2) {
      //       this.is_singkawang_domicile == 1 ? null : this.step = 3
      //     }
      //     if (newVal == 4) {
      //       this.input.singkawang_related == 0 ? this.submit() : null
      //     }
      //   }
    },
    computed: {
      ...mapState({
        is_singkawang_domicile: state => state.respondent.is_singkawang_domicile,
        income: state => state.respondent.income


      })
    },
    methods: {
      handleNext(token, routeName) {
        this.$router.replace({
          name: routeName,
          query: {
            token: token
          }
        });
      },
      nextStep(step, progressData) {
        let newStep = step
        if (newStep == 2) {
          this.is_singkawang_domicile == 1 ? null : newStep = 3
        }
        if (newStep == 4) {
          if (this.input.singkawang_related != 0) {
            this.submit()
            return
          }
        }
        this.step = newStep

        let data = {
          progress: progressData
        }
        this.$emit("childinit", data);

      },
      submit() {
        // setTimeout(() => {
        // this.$store.dispatch("isLoading", false);
        // this.handleNext("fsfsdf", "TravelData");
        // }, 1000);
        this.$store.dispatch("isLoading", true);

        axios
          .post(
            `/respondent/survey/screening-data?token=${this.$route.query.token}`,
            this.input
          )
          .then(response => {
            console.log(response.data);
            this.$store.dispatch("storeToken", response.data);
            this.handleNext(response.data.token, response.data.step);
            // this.submitting = false;
            this.$store.dispatch("isLoading", false);
          })
          .catch(error => {
            console.log(error);
          });
      },
    },
  }
</script>
<style>
</style>
