<template>
  <div class="d-flex justify-content-center py-3">
    <b-col v-if="step == 1" lg="4" md="6" sm="8">
      <b-card class="shadow-sm mb-3">
        <div>
          <p>
            <span v-if="singkawang_related == 1 || singkawang_related_potentially == 1">Kota apa yang paling sering anda kunjungi sebagai destinasi di luar kota Singkawang dan sekitarnya? (anda hanya bisa memilih 1 kota, harap jawab kota terdekat dengan pilihan berikut)</span>
            <span v-if="singkawang_related == 2 || singkawang_related_potentially == 2">Darimana asal kota anda sebelum bepergian menuju Kota Singkawang dan sekitarnya? (harap jawab kota terdekat dengan pilihan berikut)</span>
          </p>
          <b-form-select stacked :options="options.city" v-model="input.city" button-variant="outline-warning" buttons class="btn-block">
            <template slot="first">
              <option :value="null" disabled>-- Pilih Kota --</option>
            </template>
          </b-form-select>
        </div>
      </b-card>
      <div>
        <b-btn variant="primary" class="font-weight-bold float-right" @click="nextStep(2)" v-if="input.city != null">Lanjut</b-btn>
      </div>
    </b-col>
    <b-col v-if="step == 2" lg="4" md="6" sm="8">
      <b-card class="shadow-sm mb-3">
        <div>
          <p>
            Apa tujuan perjalanan tersebut?
          </p>
          <b-form-radio-group stacked :options="options.travel_purpose" v-model="input.travel_purpose" button-variant="outline-warning" buttons class="btn-block">
          </b-form-radio-group>
          <b-form-group v-if="input.travel_purpose == 7" label="Tulis tujuan anda" label-for="travel_purpose_other">
            <b-form-input id="travel_purpose_other" v-model="input.travel_purpose_other" trim></b-form-input>
          </b-form-group>
        </div>
      </b-card>
      <div>
        <b-btn variant="outline-secondary" @click="backStep(1)">
          <i class="ri-arrow-left-circle-line align-middle"></i> Kembali
        </b-btn>
        <b-btn v-if="input.travel_purpose != null" variant="primary" class="font-weight-bold float-right" @click="nextStep(3)">Lanjut</b-btn>
      </div>
    </b-col>
    <card-survey-slider v-if="step == 3" :step="step" action="Lanjut" :height="6" :min="0" :max="100" :interval="1" :input="input.travel_city_frequence" @onChange="input.travel_city_frequence = $event" @onBack="backStep(2)" @onNext="nextStep(4)">
      <template v-slot:question>
        <p v-if="singkawang_related_potentially == 1 || singkawang_related_potentially == 2">
          Dalam satu tahun, berapa kali anda akan melakukan perjalanan pada pertanyaan sebelumnya?
        </p>
        <p v-else>
          Dalam satu tahun, berapa kali anda melakukan perjalanan pada pertanyaan sebelumnya?
        </p>
      </template>
      <template v-slot:info>
        <div v-show="input.travel_city_frequence != null">
          <div v-if="input.travel_city_frequence == 0">
            Tidak pernah melakukan perjalanan tersebut dalam setahun
          </div>
          <div v-else>
            Melakukan perjalanan tersebut {{input.travel_city_frequence}} kali dalam setahun
          </div>
        </div>
      </template>
    </card-survey-slider>
    <b-col v-if="step == 4" lg="4" md="6" sm="8">
      <b-card class="shadow-sm mb-3">
        <div>
          <p v-if="singkawang_related_potentially == 1 || singkawang_related_potentially == 2">
            Berapakah harga tiket rata-rata per orang per rute yang akan anda keluarkan untuk melakukan perjalanan di pertanyaan sebelumnya?
          </p>
          <p v-else>
            Berapakah harga tiket rata-rata per orang per rute yang anda keluarkan untuk melakukan perjalanan di pertanyaan sebelumnya?
          </p>
          <vue-slider tooltip="none" v-model="input.avg_trip_cost" :height="6" :min="0" :max="10000000" :interval="200000" />
          <div class="text-center">
            Rp. {{input.avg_trip_cost | currency}}
          </div>
        </div>
      </b-card>
      <div>

        <b-btn variant="outline-secondary" @click="backStep(3)">
          <i class="ri-arrow-left-circle-line align-middle"></i> Kembali
        </b-btn>
        <b-btn v-if="input.avg_trip_cost != null" variant="primary" class="font-weight-bold float-right" @click="nextStep(5)">Lanjut</b-btn>
      </div>
    </b-col>
    <b-col v-if="step == 5" :col="12">
      <b-row class="justify-content-center">
        <b-col v-if="step == 5" lg="4" md="6" sm="8">
          <b-card no-body class="shadow-sm w-100 px-0  overflow-hidden">
            <div class="card-body" style="max-height:300px;overflow-y:auto">
              <ul class="timeline">

                <li v-for="(v,i) in input.multi_trip" :key="i">
                  <div class="tl-icon">{{i}}</div>
                  <strong v-if="i == 0">Asal : Rumah</strong>
                  <strong v-if="i !== 0">Pemberhentian ke-{{i}} : {{getDestination(i-1)}}</strong>
                  <div class="mt-2">
                    <div>Moda : {{findModeTransport(v.transportation_mode_id)}}</div>
                    <div>Biaya perjalanan : Rp. {{v.cost | currency}}</div>
                    <div>Waktu perjalanan : <span v-if="v.duration_hours">{{v.duration_hours}} Jam</span> <span v-if="v.duration_minutes">{{v.duration_minutes}} Menit</span> </div>
                  </div>
                </li>
                <li>
                  <div class="tl-icon">{{input.multi_trip.length }}</div>
                  <strong>Tujuan : {{getDestination(input.multi_trip.length-1)}}</strong>
                </li>
              </ul>
            </div>
            <div class="card-body">
              <small>
                <em>
                  nb: Tekan "Kirim", jika pemberhentian ke {{input.multi_trip.length }} adalah akhir tujuan anda ({{destination()}})
                </em>
              </small>
            </div>
          </b-card>
        </b-col>
        <b-col v-if="step == 5" lg="4" md="6" sm="8">
          <b-card class="shadow-sm mb-3">
            <div>
              <p class="mb-1">
                Bagaimana biasanya anda melakukan perjalanan dari <strong>{{origin()}}</strong> ke <strong>{{destination()}}</strong> ?
              </p>
              <p class="mb-1">
                Mohon detailkan perjalanan anda dari titik asal sampai titik tujuan, beserta kendaraan yang digunakan, biaya dan waktu yang dikeluarkan.
              </p>
              <!-- <small>Contoh: Biasanya sebelum sampai bandara, calon penumpang menggunakan moda transportasi darat terlebih dahulu seperti (bus, mobil, atau motor)</small> -->
              <div v-for="(v,i) in input.multi_trip" :key="i" v-show="input.multi_trip.length == (i + 1)">
                <!-- <div class="text-center mb-2">
              <b v-if="i == 0">
                Dari Tempat Asal menuju Tujuan ke-{{i+1}}
              </b>
              <b v-if="i >0">
                Dari Tujuan ke-{{i}} menuju Tujuan ke-{{i+1}}
              </b>
            </div> -->
                <b-form-group :label="`Titik pemberhentian ke-${i+1}`">

                  <b-form-input v-model="v.destination" trim placeholder="Bandara Supadio, Pelabuhan Pontianak, Terminal Kp Melayu"></b-form-input>
                </b-form-group>

                <b-row>
                  <b-col :cols="12">
                    <b-form-group label="Moda transportasi">
                      <b-form-select stacked :options="options.transportation_mode" v-model="v.transportation_mode_id" button-variant="outline-warning" buttons class="btn-block" @change="transportationModeTriggered(i,v.transportation_mode_id)">
                        <option :value="null" disabled>-- Pilih Moda Transportasi --</option>
                      </b-form-select>
                    </b-form-group>
                    <b-form-group v-if="v.transportation_mode_id == 6" label="Tulis moda transportasi lainnya" label-for="transportation_mode_others">
                      <b-form-input id="transportation_mode_others" v-model="v.transportation_mode_others" trim></b-form-input>
                    </b-form-group>
                    <b-form-group label="Biaya Perjalanan">
                      <vue-slider :disabled="v.transportation_mode_id == null" v-model="v.cost" :height="6" :tooltip-formatter="currencyTooltipFormatter" :min="0" :max="maxTransportationCost" :interval="10000" />
                    </b-form-group>
                    <b-form-group label="Durasi Perjalanan">

                      <b-input-group>
                        <b-form-select plain v-model="v.duration_hours">
                          <option :value="null" disabled>Pilih Jam</option>
                          <option v-for="n in 25" :value="n-1" :key="`duration_hours_${n}`">{{n-1}} jam</option>
                        </b-form-select>
                        <b-input-group-prepend is-text>
                          dan
                        </b-input-group-prepend>
                        <b-form-select plain v-model="v.duration_minutes">
                          <option :value="null" disabled>Pilih Menit</option>
                          <option v-for="n in 61" :value="n-1" :key="`duration_minutes_${n}`">
                            {{n-1}} menit
                          </option>
                        </b-form-select>
                      </b-input-group>
                    </b-form-group>
                  </b-col>
                </b-row>


                <div class="text-center">
                  <b-button variant="outline-secondary" @click="back(i)">Kembali</b-button>
                  <span v-if="v.destination != '' &&v.transportation_mode_id >0 && v.cost > 0 && v.duration_minutes != null && v.duration_hours != null ">
                    <b-button variant="warning" @click="addTripDetail">Tambah Pemberhentian</b-button>
                    <b-button variant="primary" @click="submit">Kirim</b-button>
                  </span>
                </div>
              </div>
            </div>
          </b-card>
        </b-col>
      </b-row>
    </b-col>
    <multi-trip-example ref="tripExample" />
  </div>
</template>
<script>
  import MultiTripExample from '../../components/MultiTripExample.vue'
  import CardSurvey from '../../components/CardSurvey.vue'
  import CardSurveySlider from '../../components/CardSurveySlider.vue'
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
    name: 'TravelData',
    components: {
      CardSurvey,
      CardSurveySlider,
      MultiTripExample


    },
    mixins: [
      AuthRespondent,
      MethodsSurvey
    ],
    props: ["routerData"],
    data: function() {
      return {
        step: 1,
        currencyTooltipFormatter: v => `Rp.${('' + v).replace(/\B(?=(\d{3})+(?!\d))/g, '.')}`,
        options: {
          city: [],
          travel_purpose: [],
          transportation_mode: []
        },
        stepProgress: [55, 60, 65, 70],
        maxTransportationCost: 0,
        input: {
          city: null,
          travel_purpose: null,
          travel_purpose_other: '',
          avg_trip_cost: null,
          travel_city_frequence: null,
          multi_trip: [{
            transportation_mode_id: null,
            transportation_mode_others: '',
            duration_hours: null,
            duration_minutes: null,
            destination: '',
            cost: null
          }]
        }
      }
    },
    created() {
      this.getData()
      this.$emit("childinit", this.routerData);
      this.stepProgress.unshift(this.routerData.progress)
      //   this.$refs.tripExample.handleClick(true)
    },
    mounted() {
      //   this.$refs.tripExample.handleClick(true)

    },
    watch: {

      'input.travel_purpose': function(newVal, oldVal) {
        this.input.travel_purpose_other = ''
      },
      step(newVal, oldVal) {
        if (newVal == 5) {
          this.$refs.tripExample.handleClick(true)
        }
      }
    },
    computed: {
      ...mapState({
        is_singkawang_domicile: state => state.respondent.is_singkawang_domicile,
        singkawang_related: state => state.respondent.singkawang_related,
        singkawang_related_potentially: state => state.respondent.singkawang_related_potentially,


      })
    },
    methods: {
      origin() {
        return (this.singkawang_related == 1 || this.singkawang_related_potentially == 1) ? 'Singkawang dan Sekitarnya' :
          (this.singkawang_related == 2 || this.singkawang_related_potentially == 2) ? this.findCityName() : null
      },
      destination() {
        return (this.singkawang_related == 1 || this.singkawang_related_potentially == 1) ? this.findCityName() :
          (this.singkawang_related == 2 || this.singkawang_related_potentially == 2) ? 'Singkawang dan Sekitarnya' : null
      },
      getDestination(i) {
        return this.input.multi_trip[i].destination
      },
      findCityName() {
        let getCityData = this.options.city.find(city => city.value == this.input.city)
        return getCityData.text
      },
      findModeTransport(id) {
        if (id) {

          let mode = this.options.transportation_mode.find(mode => mode.value == id).text
          return mode
        }
      },
      handleNext(token, routeName) {
        this.$router.replace({
          name: routeName,
          query: {
            token: token
          }
        });
      },

      submit() {
        // setTimeout(() => {
        // this.$store.dispatch("isLoading", false);
        // this.handleNext("fsfsdf", "TravelData");
        // }, 1000);
        this.$store.dispatch("isLoading", true);

        axios
          .post(
            `/respondent/survey/travel-data?token=${this.$route.query.token}`,
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
      back(i) {
        if (i == 0) {
          let step = this.step - 1
          this.emitProgress(step)
          this.input.multi_trip = [{
            transportation_mode_id: null,
            transportation_mode_others: '',
            duration_hours: null,
            duration_minutes: null,
            destination: '',
            cost: null
          }]
          return
        }
        this.input.multi_trip.splice(i, 1);
      },
      addTripDetail() {
        this.input.multi_trip.push({
          transportation_mode_id: null,
          transportation_mode_others: '',
          duration_hours: null,
          duration_minutes: null,
          destination: '',
          cost: null
        });
      },
      transportationModeTriggered(i, id) {
        console.log(this.input.multi_trip[i].transportation_mode_others);
        this.input.multi_trip[i].transportation_mode_others = ''
        this.input.multi_trip[i].cost = null
        id == 1 ? this.maxTransportationCost = 10000000 : this.maxTransportationCost = 3000000

      },
      getData() {
        // this.isLoading = true;
        // this.$store.dispatch("isLoading", true);
        // let self = this;
        axios
          .get(`/respondent/survey/travel-data`)
          .then(response => {
            let city = this.mutateKey(response.data.city);
            this.options.city = city;

            // console.log(this.is_singkawang_domicile);
            // if (this.is_singkawang_domicile == 1) {

            //   this.options.city = []
            //   console.log(this.options.city);

            //   for (let i = 0; i < 2; i++) {
            //     this.options.city.push(city[i]);

            //   }
            //   console.log(this.options.city);
            // }
            this.options.transportation_mode = this.mutateKey(response.data.transportation_mode);
            this.options.travel_purpose = this.mutateKey(response.data.travel_purpose);

          })
          .catch(error => {
            console.log(error);
          });
      },
      mutateKey(data) {
        let mutateData = data.map(function(item) {
          return {
            value: item.id,
            text: item.range || item.name || item.description,
            state: false
          };
        });
        return mutateData;
      }
    },
  }
</script>
<style>

</style>
