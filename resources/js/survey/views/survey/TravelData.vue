<template>
  <div class="full-bg-survey bg-survey d-flex justify-content-center py-3">
    <b-col v-if="step == 1" lg="4" md="6" sm="8">
      <b-card class="shadow-sm mb-3">
        <div>
          <p>
            <span v-if="singkawang_related == 1">Sebelum adanya pandemic Covid 19, Kota apa yang paling sering kamu kunjungi sebagai destinasi di luar kota Singkawang dan sekitarnya? (kamu hanya bisa memilih 1 kota, harap jawab kota terdekat dengan pilihan berikut)</span>
            <span v-if="singkawang_related == 2">Sebelum adanya pandemic Covid 19, Darimana asal kota kamu sebelum bepergian menuju Kota Singkawang dan sekitarnya? (harap jawab kota terdekat dengan pilihan berikut)</span>
          </p>
          <b-form-select stacked :options="options.city" v-model="input.city" button-variant="outline-warning" buttons class="btn-block">
            <template slot="first">
              <option :value="null" disabled>-- Pilih Kota --</option>
            </template>
          </b-form-select>
        </div>
      </b-card>
      <b-btn variant="primary" class="font-weight-bold" block @click="nextStep(2,55)" v-if="input.city != null">Lanjut</b-btn>

    </b-col>
    <b-col v-if="step == 2" lg="4" md="6" sm="8">
      <b-card class="shadow-sm mb-3">
        <div>
          <p>
            Apa tujuan perjalanan tersebut?
          </p>
          <b-form-radio-group stacked :options="options.travel_purpose" v-model="input.travel_purpose" button-variant="outline-warning" buttons class="btn-block">
          </b-form-radio-group>
          <b-form-group v-if="input.travel_purpose == 7" label="Tulis tujuan kamu" label-for="travel_purpose_other">
            <b-form-input id="travel_purpose_other" v-model="input.travel_purpose_other" trim></b-form-input>
          </b-form-group>
        </div>
      </b-card>
      <b-btn v-if="input.travel_purpose != null" variant="primary" class="font-weight-bold" block @click="nextStep(3,60)">Lanjut</b-btn>
    </b-col>
    <b-col v-if="step == 3" lg="4" md="6" sm="8">
      <b-card class="shadow-sm mb-3">
        <div>
          <p>
            Bagaimana biasanya kamu melakukan perjalanan tersebut? Sebutkan kendaraan apa saja yang kamu gunakan tersebut termasuk biaya perjalanan yang dikeluarkan dan waktu perjalanannya
          </p>
          <div v-for="(v,i) in input.multi_trip" :key="i" v-show="input.multi_trip.length == (i + 1)">
            <div class="text-center mb-2">
              <b v-if="i == 0">
                Dari Tempat Asal menuju Tujuan ke-{{i+1}}
              </b>
              <b v-if="i >0">
                Dari Tujuan ke-{{i}} menuju Tujuan ke-{{i+1}}
              </b>
            </div>
            <b-form-group :label="`Tujuan ke-${i+1}`">

              <b-form-input v-model="v.destination" trim placeholder="Bandara Supadio, Pelabuhan Pontianak, Terminal Kp Melayu"></b-form-input>
            </b-form-group>

            <b-row>
              <b-col cols="12">
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
            <b-form-group label="Biaya Perjalanan">
              <vue-slider v-model="v.cost" :height="6" :tooltip-formatter="currencyTooltipFormatter" :min="0" :max="3000000" :interval="10000" />

            </b-form-group>
            <b-form-group label="Moda transportasi">

              <b-form-select stacked :options="options.transportation_mode" v-model="v.transportation_mode_id" button-variant="outline-warning" buttons class="btn-block" @change="transportationModeTriggered(i)">
                <option :value="null" disabled>-- Pilih Moda Transportasi --</option>

              </b-form-select>
            </b-form-group>
            <b-form-group v-if="v.transportation_mode_id == 6" label="Tulis moda transportasi lainnya" label-for="transportation_mode_others">
              <b-form-input id="transportation_mode_others" v-model="v.transportation_mode_others" trim></b-form-input>
            </b-form-group>

            <div class="text-center">

              <b-button variant="secondary" @click="back(i)" v-if=" i > 0">Kembali</b-button>
              <span v-if="v.destination != '' &&v.transportation_mode_id >0 && v.cost > 0 && v.duration_minutes != null && v.duration_hours != null ">
                <b-button variant="warning" @click="addTripDetail">Tambah Tujuan</b-button>
                <b-button variant="primary" @click="nextStep(4,65)">Lanjut</b-button>
              </span>
            </div>
          </div>

        </div>
      </b-card>

    </b-col>
    <b-col v-if="step == 4" lg="4" md="6" sm="8">
      <b-card class="shadow-sm mb-3">
        <div>
          <p>
            Berapakah harga tiket rata-rata per orang per rute yang kamu keluarkan untuk melakukan perjalanan di pertanyaan sebelumnya?
          </p>
          <vue-slider tooltip="none" v-model="input.avg_trip_cost" :height="6" :min="100000" :max="16000000" :interval="100000" />
          <div class="text-center">
            Rp. {{input.avg_trip_cost | currency}}
          </div>
        </div>
      </b-card>
      <b-btn v-if="input.avg_trip_cost != null" variant="primary" class="font-weight-bold" block @click="submit">Kirim</b-btn>
    </b-col>
  </div>
</template>
<script>
  import CardSurvey from '../../components/CardSurvey.vue'
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
      CardSurvey
    },
    mixins: [
      //   AuthRespondent,
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
        input: {
          city: null,
          travel_purpose: null,
          travel_purpose_other: '',
          avg_trip_cost: null,
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

    },
    watch: {
      'input.travel_purpose': function(newVal, oldVal) {
        this.input.travel_purpose_other = ''
      }
    },
    computed: {
      ...mapState({
        singkawang_related: state => state.respondent.singkawang_related


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
      transportationModeTriggered(i) {
        console.log(this.input.multi_trip[i].transportation_mode_others);
        this.input.multi_trip[i].transportation_mode_others = ''
      },
      getData() {
        // this.isLoading = true;
        // this.$store.dispatch("isLoading", true);
        // let self = this;
        axios
          .get(`/respondent/survey/travel-data`)
          .then(response => {
            this.options.city = this.mutateKey(response.data.city);
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
