<template>
  <b-container class="mb-3" v-if="!isLoading">
    <b-row class="d-flex justify-content-center align-items-center">
      <b-col lg="4" md="5" sm="6">

        <p class="h4 text-white">
          <b>Terima kasih!</b>
        </p>
        <div class="text-white mb-3">
          <p>Terima kasih sudah meluangkan waktu untuk mengisi surfvei ini,
            Semoga kamu beruntung mendapatkan hadiah dari Tim Konsultan Studi ini ya!
          </p>
          <b>Tetap menerapkan protokol kesehatan 5M dan sehat selalu !</b>
        </div>
        <div class="w-100 text-center">
          <b-btn variant="warning" class="px-5" v-if="!isSubmitted" @click="inputDataModal = true"><b>Sebelum itu, isi data diri dulu disini!</b></b-btn>
        </div>
      </b-col>
      <b-modal v-if="!isSubmitted" :no-close-on-esc="true" :hide-header-close="true" :no-close-on-backdrop="true" title="Mohon isi data diri anda" v-model="inputDataModal" @ok="postData" ok-title="Kirim" cancel-title="Batal" ok-variant="primary">
        <b-form-group label="Nama" :invalid-feedback="errors.name" :state="stateName">
          <b-input autocomplete="off" type="text" class="form-control" placeholder="Masukan nama lengkap" v-model="input.name" :state="stateName" />
        </b-form-group>
        <b-form-group label="Nomor Telepon" :invalid-feedback="errors.phone" :state="statePhone">
          <b-input autocomplete="off" type="number" class="form-control" placeholder="Masukan nomor telepon aktif" v-model="input.phone" :state="statePhone" />
        </b-form-group>
        <b-form-group label="Email" :invalid-feedback="errors.email" :state="stateEmail">
          <b-input autocomplete="off" type="text" class="form-control" placeholder="Masukan alamat email aktif" v-model="input.email" :state="stateEmail" />
        </b-form-group>
        <b-form-group label="Alamat" :invalid-feedback="errors.address" :state="stateAddress">
          <b-input autocomplete="off" type="text" class="form-control" placeholder="Masukan alamat rumah lengkap" v-model="input.address" :state="stateAddress" />
        </b-form-group>

      </b-modal>
      <b-modal v-model="modalComplete" :no-close-on-esc="true" :hide-header-close="true" :no-close-on-backdrop="true" :hide-header="true" ok-only hide-footer centered>
        <lottie :options="defaultOptions" v-on:animCreated="handleAnimation" :height="300" />
        <h5 class="mb-3 text-primary">Data diri anda sudah berhasil tersimpan.</h5>
        <b-btn variant="primary" class="float-right" @click="modalComplete = false">Tutup</b-btn>
      </b-modal>
    </b-row>
  </b-container>
</template>
<script>
  import {
    AuthRespondent
  } from '../../mixins/AuthRespondent.js'
  import animationData from "../../asset/checked_blue_yellow.js";

  export default {

    name: 'Ending',
    mixins: [AuthRespondent],

    data: function() {
      return {
        defaultOptions: {
          animationData: animationData,
          loop: false
        },
        animationSpeed: 1,
        configCalendar: {
          dateFormat: "d-M-y"
        },
        modalComplete: false,
        isLoading: true,
        inputDataModal: false,
        isSubmitted: false,
        input: {
          phone: null,
          name: "",
          email: "",
          address: "",
        },
        errors: {
          phone: null,
          name: "",
          email: "",
          address: "",
        }
      }
    },
    created() {
      this.getData()

    },
    computed: {
      statePhone() {
        return this.errors.phone == "no-error" ?
          true :
          this.errors.phone ?
          false :
          null;
      },
      stateEmail() {
        return this.errors.email == "no-error" ?
          true :
          this.errors.email ?
          false :
          null;
      },
      stateAddress() {
        return this.errors.address == "no-error" ?
          true :
          this.errors.address ?
          false :
          null;
      },
      stateBirthDate() {
        return this.errors.birthdate == "no-error" ?
          true :
          this.errors.birthdate ?
          false :
          null;
      },
      stateName() {
        return this.errors.name == "no-error" ?
          true :
          this.errors.name ?
          false :
          null;
      },
    },
    methods: {
      handleAnimation: function(anim) {
        this.anim = anim;
      },
      getData() {
        let self = this;
        this.isLoading = true;
        axios
          .get(`/respondent/survey/personal-data?token=${this.$route.query.token}`)
          .then(response => {
            console.log("TCL: getData -> social-data", response.data);
            if (response.data.phone) {
              self.isSubmitted = true;
            }
            self.isLoading = false;
          })
          .catch(error => {
            console.log(error);
          });
      },
      postData(e) {
        e.preventDefault();
        let self = this;

        axios
          .post(
            `/respondent/survey/personal-data?token=${this.$route.query.token}`,
            this.input
          )
          .then(response => {
            self.isSubmitted = true;
            this.inputDataModal = false;
            this.modalComplete = true;
            // fbq("track", "CompleteRegistration");
          })
          .catch(error => {
            console.log(error);
            let errors = error.response.data.errors;
            this.errors.name = errors.name ?
              errors.name[0] :
              "no-error";
            this.errors.phone = errors.phone ?
              errors.phone[0] :
              "no-error";
            this.errors.address = errors.address ?
              errors.address[0] :
              "no-error";
            this.errors.email = errors.email ?
              errors.email[0] :
              "no-error";
          });
      }
    }
  }
</script>
<style>
</style>
