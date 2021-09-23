<template>
  <div class="full-bg d-flex flex-wrap" :class="routerData.background">

    <div class="gradient-white ">
      <b-container>
        <b-row>
          <b-col sm="12" class="text-md-left text-center pt-2 ">
            <b-img fluid src="/img/icon-ptpii-kemenhub.svg"></b-img>

          </b-col>
        </b-row>
      </b-container>
    </div>

    <router-view></router-view>

    <div class="bg-warning w-100 d-flex align-items-center mt-auto" style="height:60px">
      <b-container>
        <div class="text-md-right text-center ">
          <b-img fluid src="/img/icon-aecom-kpmg.svg"></b-img>

        </div>
      </b-container>
    </div>

  </div>
</template>
<script>
  import {
    AuthRespondent
  } from '../../mixins/AuthRespondent.js'
  export default {
    name: 'Home',
    // mixins: [AuthRespondent],

    props: ['routerData'],
    data: function() {
      return {}
    },
    created() {},
    methods: {},
  }
</script>
<style>
</style>
