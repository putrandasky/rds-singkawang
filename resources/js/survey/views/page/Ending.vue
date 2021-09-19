<template>
  <b-container class="mb-3">
    <b-row class="d-flex justify-content-center align-items-center">
      <b-col lg="4" md="5" sm="6">

        <p class="h4 text-white">
          <b>Terima kasih!</b>
        </p>
        <div class="text-white mb-3">
          <p>Terima kasih sudah berpartisipasi pada survei kali ini. Semoga lain kali kamu berkesempatan untuk mengunjungi Kota Singkawang.
          </p>
          <b>Tetap menerapkan protokol kesehatan 5M dan sehat selalu !</b>
        </div>
      </b-col>
    </b-row>
  </b-container>
</template>
<script>
  import {
    AuthRespondent
  } from '../../mixins/AuthRespondent.js'

  export default {

    name: 'Ending',
    mixins: [AuthRespondent],

    data: function() {
      return {}
    },
    created() {},
    methods: {},
  }
</script>
<style>
</style>
