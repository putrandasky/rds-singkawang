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
      <!-- udh ga pake percentage slider lagi -->
      <b-col v-if="step == 5" lg="4" md="6" sm="8">
        <b-card class="shadow-sm mb-3">
          <div>
            <p>
              Berapa persentase rata – rata yang dialokasikan untuk biaya transportasi per bulan (tanpa transportasi udara) anda terhadap pendapatan per bulan? ​
            </p>
            <b-form-select :options="options.avg_transportation_cost" v-model="input.avg_transportation_cost" button-variant="outline-warning" buttons class="btn-block">
              <template slot="first">
                <option :value="null" disabled>-- Pilih Opsi--</option>
              </template>
            </b-form-select>
          </div>
        </b-card>
        <div>
          <b-btn variant="outline-secondary" @click="backStep(5)">
            <i class="ri-arrow-left-circle-line align-middle"></i> Kembali
          </b-btn>
          <b-btn v-if="input.avg_transportation_cost != null" variant="primary" class="font-weight-bold float-right px-4" @click="nextStep(6)">Lanjut <i class="ri-arrow-right-circle-line align-middle"></i></b-btn>
        </div>
      </b-col>


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
              <b-form-select @input="getRegencies($event)" stacked :options="getProvinces" v-model="input.domicile_province" button-variant="outline-warning" buttons class="btn-block">
                <template slot="first">
                  <option :value="null" disabled>-- Pilih Provinsi --</option>
                </template>
              </b-form-select>
            </b-form-group>
            <b-form-group label="Kota">
              <b-form-select stacked :options="getRegency" v-model="input.domicile_city" button-variant="outline-warning" buttons class="btn-block">
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
          avg_transportation_cost: [],
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
      getProvinces() {
        if (this.input.is_singkawang_domicile == 1) {
          return this.options.domicile_province.filter(data => data.value == 61)
        }
        return this.options.domicile_province
      },
      getRegency() {
        if (this.input.is_singkawang_domicile == 1) {
          return this.options.domicile_city.filter(data => {
            return data.value == 6101 || data.value == 6102 || data.value == 6104 || data.value == 6172
          })
        }
        return this.options.domicile_city
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
            this.options.avg_transportation_cost = this.mutateKey(response.data.avg_transportation_cost_to_income);
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
