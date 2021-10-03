<template>
  <div class="d-flex justify-content-center py-3">
    <card-survey-slider v-if="step == 1" :step="step" action="Lanjut" :height="6" :min="0" :max="200" :interval="1" :input="input.travel_frequence" @onChange="input.travel_frequence = $event" @onNext="nextStep(2)">
      <template v-slot:question>
        <b>Sebelum adanya pandemic Covid 19</b>, Dalam satu tahun, berapa kali anda melakukan perjalanan menggunakan pesawat rute domestik?
        <div>
          <small>Note: Pulang dan Pergi dihitung 2 kali perjalanan</small>
        </div>
      </template>
      <template v-slot:info>
        <div v-show="input.travel_frequence != null">
          <div v-if="input.travel_frequence == 0">
            Tidak pernah menggunakan pesawat dalam setahun
          </div>
          <div v-else>
            Menggunakan pesawat {{input.travel_frequence}} kali dalam setahun
          </div>
        </div>
      </template>
    </card-survey-slider>
    <!-- slider berubah pake slider aja kelipatan 200rb max 10jt -->
    <card-survey-slider v-if="step == 2" action="Lanjut" :height="6" :min="0" :max="10000000" :interval="200000" :input="input.avg_airplane_transportation_cost" @onChange="input.avg_airplane_transportation_cost = $event" @onBack="backStep(1)" @onNext="nextStep(3)">
      <template v-slot:question>
        Berapa rata-rata harga <b>tiket pesawat</b> untuk satu kali perjalanan domestik (termasuk transit, jika ada) pada pertanyaan sebelumnya ?
      </template>
      <template v-slot:info>
        <span v-if="input.avg_airplane_transportation_cost != null">Rp. {{input.avg_airplane_transportation_cost|currency}}</span>
        <!-- <span v-if="income.max == null">≤</span>
        <span v-if="income.min != null"> Rp. {{income.min * input.avg_airplane_transportation_cost/100 | currency}}</span>

        <span v-if="income.min != null && income.max != null"> - </span>
        <span v-if="income.min == null">></span>

        <span v-if="income.max != null"> Rp. {{income.max * input.avg_airplane_transportation_cost/100 | currency}}</span> -->
      </template>
    </card-survey-slider>


    <b-col v-if="step == 3" lg="4" md="6" sm="8">
      <b-card no-body class="shadow-sm mb-3">
        <!-- <iframe class="travel-map-mymap p-0" :src="selectedData.src" style="position:relative; top:-55px; border:none;margin-bottom:-60px"></iframe> -->
        <div class="overflow-hidden mb-1">

          <iframe src="https://www.google.com/maps/d/u/0/embed?mid=16MsgX7c2ga0M16EHZdfdd4vXpZ5BO-DX" class="travel-map-mymap" style="position:relative; top:-55px; border:none;margin-bottom:-60px" allowfullscreen="" loading="lazy"></iframe>
        </div>
        <div class=" d-flex flex-wrap justify-content-center ">
          <small class="px-2">
            <i class="ri-map-pin-5-fill align-middle ri-2x" style="color:RGB(136,14,79)"></i> Singkawang
          </small>
          <small class="px-2">
            <i class="ri-map-pin-5-fill align-middle ri-2x" style="color:RGB(121,85,72)"></i> Bengkayang
          </small>
          <small class="px-2">
            <i class="ri-map-pin-5-fill align-middle ri-2x" style="color:RGB(2,136,209)"></i> Sambas
          </small>
          <small class="px-2">
            <i class="ri-map-pin-5-fill align-middle ri-2x" style="color:RGB(249,168,37)"></i> Mempawah
          </small>
        </div>
        <b-card-body>
          <p>
            <b>Sebelum adanya pandemic Covid 19</b>, pernahkan anda bepergian
            <span v-if="is_singkawang_domicile == 1">dari</span>
            <span v-if="is_singkawang_domicile == 0">menuju</span>
            Kota Singkawang dan sekitarnya (Kabupaten Bengkayang, Sambas dan Mempawah)?
          </p>
          <b-form-radio-group stacked v-model="input.singkawang_related" button-variant="outline-warning" buttons class="btn-block">
            <b-form-radio v-if="is_singkawang_domicile == 1" :value="1">Pernah/sering bepergian dari Singkawang</b-form-radio>
            <b-form-radio v-if="is_singkawang_domicile == 0" :value="2">Pernah/sering bepergian menuju Singkawang</b-form-radio>
            <b-form-radio :value="0">Tidak pernah</b-form-radio>
          </b-form-radio-group>

        </b-card-body>
      </b-card>
      <div>

        <b-btn variant="outline-secondary" @click="backStep(2)">
          <i class="ri-arrow-left-circle-line align-middle"></i> Kembali
        </b-btn>
        <b-btn v-if="input.singkawang_related != null" variant="primary" class="font-weight-bold float-right" @click="nextStep(4)">Lanjut</b-btn>
      </div>
    </b-col>
    <b-col v-if="step == 4" lg="4" md="6" sm="8">
      <b-card no-body class="shadow-sm mb-3">
        <!-- <iframe class="travel-map-mymap p-0" :src="selectedData.src" style="position:relative; top:-55px; border:none;margin-bottom:-60px"></iframe> -->
        <div class="overflow-hidden mb-1">

          <iframe src="https://www.google.com/maps/d/u/0/embed?mid=16MsgX7c2ga0M16EHZdfdd4vXpZ5BO-DX" class="travel-map-mymap" style="position:relative; top:-55px; border:none;margin-bottom:-60px" allowfullscreen="" loading="lazy"></iframe>
        </div>
        <div class=" d-flex flex-wrap justify-content-center ">
          <small class="px-2">
            <i class="ri-map-pin-5-fill align-middle ri-2x" style="color:RGB(136,14,79)"></i> Singkawang
          </small>
          <small class="px-2">
            <i class="ri-map-pin-5-fill align-middle ri-2x" style="color:RGB(121,85,72)"></i> Bengkayang
          </small>
          <small class="px-2">
            <i class="ri-map-pin-5-fill align-middle ri-2x" style="color:RGB(2,136,209)"></i> Sambas
          </small>
          <small class="px-2">
            <i class="ri-map-pin-5-fill align-middle ri-2x" style="color:RGB(249,168,37)"></i> Mempawah
          </small>
        </div>
        <b-card-body>
          <p>
            Di waktu yang mendatang, akankah anda berpotensi bepergian
            <span v-if="is_singkawang_domicile == 1">dari</span>
            <span v-if="is_singkawang_domicile == 0">menuju</span>
            Kota Singkawang dan sekitarnya (Kabupaten Bengkayang, Sambas dan Mempawah)?
          </p>
          <b-form-radio-group stacked v-model="input.singkawang_related_potentially" button-variant="outline-warning" buttons class="btn-block">
            <b-form-radio v-if="is_singkawang_domicile == 1" :value="1">akan bepergian dari Singkawang</b-form-radio>
            <b-form-radio v-if="is_singkawang_domicile == 0" :value="2">akan bepergian menuju Singkawang</b-form-radio>
            <b-form-radio :value="0">Tidak akan</b-form-radio>
          </b-form-radio-group>

        </b-card-body>
      </b-card>
      <div>
        <b-btn variant="outline-secondary" @click="backStep(3)">
          <i class="ri-arrow-left-circle-line align-middle"></i> Kembali
        </b-btn>
        <b-btn v-if="input.singkawang_related_potentially != null" variant="primary" class="font-weight-bold float-right" @click="submit">Lanjut</b-btn>
      </div>
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
  import CardSurveySlider from '../../components/CardSurveySlider.vue'

  export default {
    name: 'ScreeningData',
    mixins: [
      AuthRespondent,
      MethodsSurvey
    ],
    components: {
      CardSurveySlider
    },
    props: ["routerData"],

    data: function() {
      return {
        submitting: false,
        srcMap: "https://www.google.com/maps/d/embed?mid=1htfGOJfkOLbEf-lGl5fMDCPWA0j1krpW&ll=-6.185622133940079%2C106.82362947503714&z=11",
        step: 1,
        sliderTooltipFormat: '{value}%',
        stepProgress: [43, 45, 48],
        currencyTooltipFormatter: v => `Rp.${('' + v).replace(/\B(?=(\d{3})+(?!\d))/g, '.')}`,
        input: {
          travel_frequence: null,
          avg_airplane_transportation_cost: null,
          singkawang_related: null,
          singkawang_related_potentially: null,
        }
      }
    },
    created() {
      this.$emit("childinit", this.routerData);
      this.stepProgress.unshift(this.routerData.progress)

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
      backStep(step) {
        let newStep = step
        if (newStep == 2) {
          this.is_singkawang_domicile == 1 ? null : newStep = 1
        }
        this.step = newStep
        this.emitProgress(newStep)
      },
      nextStep(step) {
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
        this.emitProgress(newStep)
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
