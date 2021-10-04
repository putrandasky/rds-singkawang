<template>
  <b-container class="mb-3">
    <b-row>
      <b-col lg="6">
        <p class="text-warning h4">
          <b>Ikutan surveinya dan menangkan hadiah menarik!</b>
        </p>
        <p class="h5 text-white">
          <b>Hai Indonesia!</b>
        </p>
        <div class="text-white">
          <p>Dalam upaya mendukung pergerakan masyarakat nasional yang lebih efektif dan efisien, ketersediaan insfrastruktur transportasi udara dan pengelolaan bandar udara memegang peran yang sangat penting. Salah satu caranya adalah melalui pembangunan Bandar Udara Singkawang, di Kalimantan Barat.
          </p>
          <p>Apakah Anda sudah siap untuk menjadi bagian dari program pemerintah ini?</p>
          <p>Yuk, ikutan survei ini untuk membantu pemerintah memberikan pelayanan Bandara Singkawang yang memuaskan dan raih kesempatan untuk memenangkan berbagai hadiah! </p>
          <small>
            <i>
              <ul class="pl-3">
                <li>Data yang masuk tidak akan dipublikasikan atau diberikan kepada pihak mana pun dan akan digunakan semata-mata untuk studi internal Bandar Udara Singkawang.
                </li>
                <li>Hadiah dipersembahkan oleh Tim Konsultan Studi bagi responden terpilih yang mengisi survei sampai akhir termasuk data social ekonomi dan identitas pribadi.</li>
              </ul>
            </i>
          </small>
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
    created() {
      this.getNewToken()
    },
    methods: {
      getNewToken() {
        axios
          .get(`/respondent/survey/auth-respondent`)
          .then(response => {
            console.log("get new token from server");
            // console.log(response.data);
            localStorage.token_respondent = response.data.token;


            this.$router.replace({
              query: Object.assign({}, this.$route.query, {
                token: response.data.token
              })
            });
            this.isChecking = false;
          })
          .catch(error => {
            console.log(error);
          });
      },
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
