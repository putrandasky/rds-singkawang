<template>
  <b-modal no-close-on-backdrop no-close-on-esc hide-footer hide-header size="lg" hide-header-close centered v-model="modalOpen" body-class="px-5 pb-5 pt-4">
    <div class="w-100 text-center h4">

      <lottie :options="defaultOptions" v-on:animCreated="handleAnimation" :height="80" :width="80" />
      <div class="mb-3">

        <b>
          Petunjuk Pengisian Detail Perjalanan
        </b>
      </div>
    </div>
    <b-row>
      <b-col lg="6">
        <div class="mt-0 mt-lg-3">

          <p>Pada bagian ini anda diminta untuk mendetailkan perjalanan dari asal sampai dengan tujuan perjalan.</p>
          <p>
            Mohon sebutkan dan detailkan kendaraan apa saja yang anda gunakan tersebut termasuk biaya perjalanan yang dikeluarkan dan waktu perjalanannya.
          </p>
        </div>
      </b-col>
      <b-col lg="6">
        <b>CONTOH :</b>
        <ul class="timeline">
          <li v-for="(v,i) in multi_trip" :key="i">
            <div class="tl-icon">{{i}}</div>
            <strong v-if="i == 0">Asal : Rumah</strong>
            <strong v-if="i !== 0">Pemberhentian ke-{{i}} : {{getDestination(i-1)}}</strong>
            <div class="mt-2">

              <div>Waktu perjalanan : <span v-if="v.duration_hours">{{v.duration_hours}} Jam</span> <span v-if="v.duration_minutes">{{v.duration_minutes}} Menit</span> </div>
              <div>Biaya perjalanan : Rp. {{v.cost | currency}}</div>
              <div>Moda : {{v.transportation_mode}}</div>
            </div>
          </li>
          <li>
            <div class="tl-icon">{{multi_trip.length }}</div>
            <strong>Tujuan : {{getDestination(multi_trip.length-1)}}</strong>
          </li>
        </ul>
      </b-col>
    </b-row>
    <b-button class="mt-3" block variant="primary" @click="handleClick(false)">Lanjut</b-button>
  </b-modal>
</template>
<script>
  import animationData from "../asset/light_bulb.js";
  export default {
    name: 'MultiTripExample',
    data: function() {
      return {
        modalOpen: false,
        defaultOptions: {
          animationData: animationData,
          loop: true
        },
        multi_trip: [{
            transportation_mode: 'Bus',
            duration_hours: 0,
            duration_minutes: 60,
            destination: 'Bandara Soekarno Hatta,Jakarta',
            cost: 50000
          },
          {
            transportation_mode: 'Pesawat',
            duration_hours: 1,
            duration_minutes: 30,
            destination: 'Bandara Supadio, Pontianak',
            cost: 800000
          },
          {
            transportation_mode: 'Bus',
            duration_hours: 0,
            duration_minutes: 60,
            destination: 'Singkawang',
            cost: 100000
          }
        ]
      }
    },
    created() {},
    methods: {
      handleClick(data) {
        this.modalOpen = data
      },
      handleAnimation: function(anim) {
        this.anim = anim;
      },
      getDestination(i) {
        return this.multi_trip[i].destination
      }
    },
  }
</script>
<style>
</style>
