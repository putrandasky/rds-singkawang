<template>
  <div class="">
    <div v-if="!isLoading" class=" d-flex justify-content-center py-3">

      <card-survey v-if="step == 1" :step="step" action="Lanjut" :options="options.gender" @onNext="nextStep(2)" @onChange="input.gender = $event">
        <template v-slot:question>
          Jenis kelamin
        </template>
      </card-survey>
      <card-survey v-if="step == 2" action="Lanjut" :options="options.age" @onBack="backStep(1)" @onNext="nextStep(3)" @onChange="input.age = $event">
        <template v-slot:question>
          Umur
        </template>
      </card-survey>
      <card-survey v-if="step == 3" action="Lanjut" :options="options.job" @onBack="backStep(2)" @onNext="nextStep(4)" @onChange="input.job = $event">
        <template v-slot:question>
          Pekerjaan
        </template>
      </card-survey>
      <card-survey v-if="step == 4" action="Lanjut" :options="options.income" @onBack="backStep(3)" @onNext="nextStep(5)" @onChange="input.income = $event">
        <template v-slot:question>
          Pendapatan per bulan
        </template>
      </card-survey>
      <card-survey-slider v-if="step == 5" action="Lanjut" :height="6" :min="0" :max="100" :interval="1" :sliderTooltipFormat="'{value}%'" @onChange="input.avg_transportation_cost = $event" @onBack="backStep(4)" @onNext="nextStep(6)">
        <template v-slot:question>
          Berapa alokasi rata-rata biaya transportasi anda per bulan?
        </template>
        <template v-slot:info>
          <span v-if="income.selected[0].max == null">≤</span>
          <span v-if="income.selected[0].min != null"> Rp. {{income.selected[0].min * input.avg_transportation_cost/100 | currency}}</span>

          <span v-if="income.selected[0].min != null && income.selected[0].max != null"> - </span>
          <span v-if="income.selected[0].min == null">></span>

          <span v-if="income.selected[0].max != null"> Rp. {{income.selected[0].max * input.avg_transportation_cost/100 | currency}}</span> </template>
      </card-survey-slider>
      <card-survey v-if="step == 6" action="Lanjut" :options="options.vehicle" @onNext="nextStep(7)" @onBack="backStep(5)" @onChange="input.vehicle = $event">
        <template v-slot:question>
          Apa kendaraan yang punya?
        </template>
      </card-survey>
      <card-survey v-if="step == 7" action="Lanjut" :options="options.is_singkawang_domicile" @onNext="nextStep(8)" @onBack="backStep(6)" @onChange="input.is_singkawang_domicile = $event">
        <template v-slot:question>
          Apakah anda berdomisili di Singkawang dan sekitarnya (Kabupaten Bengkayang, Sambas dan Mempawah)?
        </template>
      </card-survey>
      <b-col v-if="step == 8" lg="4" md="6" sm="8">
        <b-card class="shadow-sm mb-3">
          <div>
            <p>
              Dimana kota domisili anda saat ini?
            </p>
            <b-form-group label="Provinsi">
              <b-form-select @input="getRegencies($event)" stacked :options="getProvince" v-model="input.domicile_province" button-variant="outline-warning" buttons class="btn-block">
                <template slot="first">
                  <option :value="null" disabled>-- Pilih Provinsi --</option>
                </template>
              </b-form-select>
            </b-form-group>
            <b-form-group label="Kota">
              <b-form-select stacked :options="options.domicile_city" v-model="input.domicile_city" button-variant="outline-warning" buttons class="btn-block">
                <template slot="first">
                  <option :value="null" disabled>-- {{regenciesPlaceholder}} --</option>
                </template>
              </b-form-select>
            </b-form-group>
          </div>
        </b-card>
        <div>
          <b-btn variant="outline-secondary" @click="backStep(7)">
            <i class="ri-arrow-left-circle-line align-middle"></i> Kembali
          </b-btn>
          <b-btn v-if="input.domicile_city != '' && input.domicile_province != ''" variant="primary" class="font-weight-bold float-right px-4" @click="submit">Kirim <i class="ri-send-plane-line align-middle"></i></b-btn>
        </div>
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
  import CardSurveySlider from '../../components/CardSurveySlider.vue'
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
      CardSurvey,
      CardSurveySlider
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
        stepProgress: [5, 10, 15, 20, 25, 30, 35],
        isLoading: false,
        submitting: false,
        animationSpeed: 1,
        step: 1,
        sliderTooltipFormat: '{value}%',
        regenciesPlaceholder: 'Pilih Provinsi Dulu',
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
          domicile_city: [],
          domicile_province: [],
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
          avg_transportation_cost: null,
          is_singkawang_domicile: null,
          domicile_city: null,
          domicile_province: null,
        }
      }
    },
    created() {
      this.getData();
      this.$emit("childinit", this.routerData);
      this.stepProgress.unshift(this.routerData.progress)
    },
    watch: {
      'input.income': function(newVal, oldVal) {
        this.income.selected = this.income.options.filter(income => income.id == newVal)
        console.log(this.income.selected);

      }
    },
    computed: {
      getProvince() {
        if (this.input.is_singkawang_domicile == 1) {
          return this.options.domicile_province.filter(data => data.value == 61)
        }
        return this.options.domicile_province
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
      getRegencies(province_id) {
        axios.get(`/region/province/${province_id}`)
          .then((response) => {
            console.log(response.data)
            this.input.domicile_city = null
            this.regenciesPlaceholder = 'Pilih Kota'
            this.options.domicile_city = this.mutateKey(response.data);
          })
          .catch((error) => {
            console.log(error);
          })
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
            this.options.domicile_province = this.mutateKey(response.data.province);

            this.options.vehicle = this.mutateKey(
              response.data.vehicle
            );
            this.income.options = response.data.income
            // this.income.selected = this.income.options.filter(income => income.id == 6)
            // console.log(this.income.selected);
            console.log(this.options.domicile_province.find(data => data.value == 61));
            setTimeout(() => {
              this.isLoading = false;

              this.$store.dispatch("isLoading", false);
              this.modalWelcome = false
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
