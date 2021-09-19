<template>
  <b-container class="mb-3">
    <b-row>
      <b-col lg="6">
        <p class="text-warning">
          <b>Ikutan surveinya dan menangkan hadiah menarik!</b>
        </p>
        <p class="h4 text-white">
          <b>Hai Indonesia!</b>
        </p>
        <div class="text-white">
          <p>Dalam upaya mendukung pergerakan masyarakat nasional yang lebih efektif dan efisien, ketersediaan insfrastruktur transportasi udara dan pengelolaan bandar udara memiliki peran yang sangat penting. Salah satu upaya untuk mewujudkan hal tersebut adalah melalui pembangunan Bandar Udara Singkawang, di Kalimantan Barat..
          </p>
          <p>Kota Singkawang memiliki potensi pariwisata yang cukup besar untuk menarik wisatawan domestik maupun mancanegara. Realisasi pembangunan Bandar Udara ini diharapkan dapat semakin mempermudah aksesibilitas untuk mobilitas penumpang transportasi udara di Indonesia, serta meningkatkan kegiatan ekonomi dan pariwisata di Kota Singkawang dan sekitarnya. Maka dari itu, Apakah kamu sudah siap untuk menjadi bagian dari program pemerintah ini?</p>
          <p>Yuk, ikutan survei permintaan calon penumpang Bandar Udara Singkawang dan raih kesempatan untuk memenangkan berbagai hadiah! </p>
          <p>Hasil survei ini akan menjadi masukan bagi stakeholder agar dapat memberikan pelayanan yang sesuai bagi masyarakat. Untuk itu, silakan mengisi survei ini dengan jujur dan lengkap ya!</p>
          <ul class="pl-3">
            <li>Data yang masuk tidak akan dipublikasikan atau diberikan kepada pihak mana pun dan akan digunakan semata-mata untuk studi internal Bandar Udara Singkawang.
            </li>
            <li>Hadiah dipersembahkan oleh Tim Konsultan Studi.</li>
          </ul>
        </div>
        <div class="w-100 text-center">
          <b-btn variant="warning" class="px-5" @click="handleNext"><b> <i class="ri-send-plane-fill mr-1"></i>Mulai Survei</b></b-btn>
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
    name: 'Home',
    // mixins: [AuthRespondent],

    data: function() {
      return {}
    },
    created() {},
    methods: {
      handleNext() {
        this.$router.replace({
          path: 'survey/social-data',
        })
      }
    },
  }
</script>
<style>
</style>
