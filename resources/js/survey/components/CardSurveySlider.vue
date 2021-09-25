<template>
  <b-col lg="4" md="6" sm="8">

    <b-card class="shadow-sm my-3">
      <div>
        <p>
          <slot name="question"></slot>
        </p>
        <vue-slider @change="onChange($event)" v-model="inputData" :height="height" :min="min" :max="max" :interval="interval" :tooltip="tooltip" :tooltip-formatter="sliderTooltipFormat">
        </vue-slider>
        <div class="text-center">
          <slot name="info"></slot>
        </div>
      </div>
    </b-card>
    <div>
      <b-btn variant="outline-secondary" v-if="step!= 1" @click="onBack">
        <i class="ri-arrow-left-circle-line align-middle"></i> Kembali
      </b-btn>
      <b-btn v-if="inputData != null" @click="onNext" variant="primary" class="font-weight-bold float-right px-3">{{action}} <i class="ri-arrow-right-circle-line align-middle"></i></b-btn>
    </div>
  </b-col>
</template>
<script>
  export default {
    name: 'CardSurvey',
    props: {
      action: String,
      step: Number,
      min: Number,
      height: Number,
      max: Number,
      input: {
        type: Number,
        default: null
      },
      interval: Number,
      sliderTooltipFormat: {
        type: String,
        default: null
      }
    },
    data: function() {
      return {
        tooltip: 'none',
        inputData: null
      }
    },
    mounted() {

      if (this.sliderTooltipFormat != null) {
        this.tooltip = 'active'
      }
      this.inputData = this.input
      console.log(this.input);
    },
    computed: {},
    methods: {

      onNext() {
        this.$emit('onNext')
        this.inputData = null
      },
      onChange(e) {
        this.$emit('onChange', e)

      },
      onBack() {
        this.$emit('onBack')
      }
    },
  }
</script>
<style>
</style>
