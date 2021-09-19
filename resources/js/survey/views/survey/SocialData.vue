<template>
  <div class="full-bg-survey bg-survey">
    <div v-if="!isLoading" class=" d-flex justify-content-center py-3">

      <card-survey v-if="step == 1" action="Lanjut" :options="options.gender" @onNext="nextStep(2,5)" @onChange="input.gender = $event">
        <template v-slot:question>
          Jenis kelamin
        </template>
      </card-survey>
      <card-survey v-if="step == 2" action="Lanjut" :options="options.age" @onNext="nextStep(3,10)" @onChange="input.age = $event">
        <template v-slot:question>
          Umur
        </template>
      </card-survey>
      <card-survey v-if="step == 3" action="Lanjut" :options="options.job" @onNext="nextStep(4,15)" @onChange="input.job = $event">
        <template v-slot:question>
          Pekerjaan
        </template>
      </card-survey>
      <card-survey v-if="step == 4" action="Lanjut" :options="options.income" @onNext="nextStep(5,20)" @onChange="input.income = $event">
        <template v-slot:question>
          Pendapatan per bulan
        </template>
      </card-survey>
      <b-col v-if="step == 5" lg="4" md="6" sm="8">
        <b-card class="shadow-sm mb-3">
          <div>
            <p>
              Berapa alokasi rata-rata biaya transportasi kamu per bulan?
            </p>
            <vue-slider v-model="input.avg_transportation_cost" :height="6" :min="0" :max="100" :interval="1" :tooltip-formatter="sliderTooltipFormat">
              <!-- <template v-slot:mark="{ pos, label }">
              <div class="custom-mark" :style="{ left: `${pos}%` }">
                {{ label }}
              </div>
            </template> -->
              <!-- <template v-slot:step="{ active, value }">
              <div :class="['vue-slider-mark-label', 'custom-label', { active }]">{{ value }}</div>
            </template> -->
              <!-- <template v-slot:label="{ active, value }">
              <div :class="['vue-slider-mark-label', 'custom-label', { active }]">{{ value }}</div>
            </template> -->
            </vue-slider>
            <div class="text-center">
              <span v-if="income.selected[0].max == null">≤</span>
              <span v-if="income.selected[0].min != null"> Rp. {{income.selected[0].min * input.avg_transportation_cost/100 | currency}}</span>

              <span v-if="income.selected[0].min != null && income.selected[0].max != null"> - </span>
              <span v-if="income.selected[0].min == null">></span>

              <span v-if="income.selected[0].max != null"> Rp. {{income.selected[0].max * input.avg_transportation_cost/100 | currency}}</span>
            </div>
          </div>
        </b-card>
        <b-btn variant="primary" class="font-weight-bold" block @click="nextStep(6,25)">Lanjut</b-btn>
      </b-col>
      <card-survey v-if="step == 6" action="Lanjut" :options="options.vehicle" @onNext="nextStep(7,30)" @onChange="input.vehicle = $event">
        <template v-slot:question>
          Apa kendaraan yang kamu punya?
        </template>
      </card-survey>
      <card-survey v-if="step == 7" action="Lanjut" :options="options.is_singkawang_domicile" @onNext="nextStep(8,35)" @onChange="input.is_singkawang_domicile = $event">
        <template v-slot:question>
          Apakah kamu berdomisili di Singkawang dan sekitarnya?
        </template>
      </card-survey>
      <b-col v-if="step == 8" lg="4" md="6" sm="8">
        <b-card class="shadow-sm mb-3">
          <div>
            <p>
              Dimana kota domisili kamu saat ini?
            </p>
            <label for="domicile_city">Kota</label>
            <b-input v-model="input.domicile_city"></b-input>
            <label class="mt-2" for="provice">Provinsi</label>
            <b-input v-model="input.domicile_province"></b-input>
          </div>
        </b-card>
        <b-btn v-if="input.domicile_city != '' && input.domicile_province != ''" variant="primary" class="font-weight-bold" block @click="submit">Kirim</b-btn>
      </b-col>

    </div>

    <b-modal no-close-on-backdrop no-close-on-esc hide-footer hide-header hide-header-close centered v-model="modalWelcome" body-class="px-5 pb-5 pt-4">

      <div class="w-100 text-center h4">

        <lottie :options="defaultOptions" v-on:animCreated="handleAnimation" :height="80" :width="80" />
        <div class="mb-3">

          <b>
            Petunjuk Pengisian Survei
          </b>
        </div>
      </div>
      <p>
        Pada survei ini diharapkan Responden untuk mengisi informasi tentang kondisi sosial ekonomi responden.
        Lengkapi survei ini hingga akhir untuk memperoleh kesempatan mendapatkan souvenir menarik berupa e-Wallet senilai Rp. 200.000 dari Tim Konsultan Studi untuk responden terpilih dan telah memasukan informasi data personal di akhir survei ini
      </p>
      <p>
        <b>
          data personal yang dimasukan dijamin kerahasiaannya
        </b>
      </p>
      <b-button block variant="primary" @click="modalWelcomeButton">Saya Bersedia</b-button>
    </b-modal>
  </div>
</template>
<script>
  import CardSurvey from '../../components/CardSurvey.vue'
  import animationData from "../../asset/light_bulb.js";
  import {
    AuthRespondent
  } from '../../mixins/AuthRespondent.js'
  import {
    MethodsSurvey
  } from '../../mixins/Survey.js'

  export default {
    name: 'SocialData',
    components: {
      CardSurvey
    },
    mixins: [AuthRespondent, MethodsSurvey],
    props: ["routerData"],

    data: function() {
      return {
        modalWelcome: false,
        defaultOptions: {
          animationData: animationData,
          loop: true
        },
        isLoading: false,
        submitting: false,
        animationSpeed: 1,
        step: 1,
        sliderTooltipFormat: '{value}%',
        income: {
          options: [],
          selected: []
        },
        options: {
          gender: [],
          age: [],
          job: [],
          income: [],
          vehicle: [],
          is_singkawang_domicile: [{
              value: 1,
              text: 'Ya'
            },
            {
              value: 0,
              text: 'Tidak'
            }
          ]
        },
        input: {
          gender: 0,
          age: null,
          job: null,
          income: null,
          vehicle: null,
          avg_transportation_cost: 0,
          is_singkawang_domicile: null,
          domicile_city: '',
          domicile_province: '',
        }
      }
    },
    created() {
      this.getData();
      this.$emit("childinit", this.routerData);

    },
    watch: {
      'input.income': function(newVal, oldVal) {
        this.income.selected = this.income.options.filter(income => income.id == newVal)
        console.log(this.income.selected);

      }
    },
    methods: {

      submit() {
        // setTimeout(() => {
        // this.$store.dispatch("isLoading", false);
        // this.handleNext("fsfsdf", "TravelData");
        // }, 1000);
        this.$store.dispatch("isLoading", true);

        axios
          .post(
            `/respondent/survey/social-data?token=${this.$route.query.token}`,
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
      handleAnimation: function(anim) {
        this.anim = anim;
      },
      modalWelcomeButton() {
        this.modalWelcome = false
      },
      getData() {
        this.isLoading = true;
        this.$store.dispatch("isLoading", true);
        // let self = this;
        axios
          .get(`/respondent/survey/social-data`)
          .then(response => {
            this.options.gender = this.mutateKey(response.data.gender);
            this.options.age = this.mutateKey(response.data.age);
            this.options.job = this.mutateKey(response.data.job);
            this.options.income = this.mutateKey(response.data.income);
            this.options.vehicle = this.mutateKey(
              response.data.vehicle
            );
            this.income.options = response.data.income
            // this.income.selected = this.income.options.filter(income => income.id == 6)
            // console.log(this.income.selected);
            setTimeout(() => {
              this.isLoading = false;

              this.$store.dispatch("isLoading", false);
              this.modalWelcome = true
            }, 200);
          })
          .catch(error => {
            console.log(error);
          });
      },


    },
  }
</script>
<style>
  .custom-mark {
    position: absolute;
    top: 10px;
    transform: translateX(-50%);
    white-space: nowrap;
  }
</style>
