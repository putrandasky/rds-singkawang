<template>
  <div>
    <b-overlay variant="dark" :show="$store.state.isLoading" blur="" fixed no-wrap></b-overlay>

    <!-- <loading class="text-center" :active.sync="$store.state.isLoading" :can-cancel="false" :opacity="0.9" :height="60" loader="dots" transition="fade" background-color="rgba(0,0,0,.85)" color="rgba(255,255,255,.9)" :is-full-page="true">
      <div class="text-center" slot="after" style="color:rgba(255,255,255,.9)">
        Mohon Tunggu...
      </div>
    </loading> -->
    <b-container class="text-center py-3">
      <b-img fluid src="/img/icon-kemenhub-ptpii.svg"></b-img>
    </b-container>
    <b-container fluid class="text-center py-3 bg-primary text-white">
      <div class="h4 ">
        <b> Real Demand Survey</b>
      </div>
      <div>
        Rencana Pembangunan Bandara Singkawang
      </div>
    </b-container>
    <b-container fluid class="bg-platinum text-center py-3 d-flex justify-content-center">
      <b-col lg="2" md="4" sm="6" cols="8">
        <b-progress max="100" variant="primary" height="0.5rem">
          <b-progress-bar :value="progress_new">
            <span class="position-absolute text-dark" style="right:-15px;">
              <b>
                <countTo :startVal='progress' :endVal='progress_new' :duration='500'></countTo>%
              </b>
            </span>
          </b-progress-bar>
        </b-progress>
      </b-col>
    </b-container>
    <router-view v-on:childinit="onChildInit"></router-view>

  </div>
</template>
<script>
  import {
    AuthRespondent
  } from '../../mixins/AuthRespondent.js'
  import countTo from "vue-count-to";

  export default {
    name: 'Survey',
    components: {
      countTo
    },

    mixins: [AuthRespondent],

    data: function() {
      return {
        progress: 0,
        progress_new: 0,

      }
    },
    created() {},
    methods: {
      onChildInit(value) {
        this.progress = this.progress_new;
        this.progress_new = value.progress;
      },
    },
  }
</script>
<style>
</style>
