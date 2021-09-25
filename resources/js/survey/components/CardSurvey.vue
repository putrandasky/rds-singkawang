<template>
  <b-col lg="4" md="6" sm="8">

    <b-card class="shadow-sm my-3">
      <div>
        <p>
          <slot name="question"></slot>
        </p>
        <b-form-radio-group stacked v-model="input" :options="options" button-variant="outline-warning" buttons name="gender" class="btn-block" @change="onChange"></b-form-radio-group>
      </div>
    </b-card>
    <div>
      <b-btn variant="outline-secondary" v-if="step!= 1" @click="onBack">
        <i class="ri-arrow-left-circle-line align-middle"></i> Kembali
      </b-btn>
      <b-btn v-if="input != null" @click="onNext" variant="primary" class="font-weight-bold float-right px-3">{{action}} <i class="ri-arrow-right-circle-line align-middle"></i></b-btn>
    </div>
  </b-col>
</template>
<script>
  export default {
    name: 'CardSurvey',
    props: ['action', 'options', 'step'],
    data: function() {
      return {
        input: null
      }
    },
    created() {},
    methods: {
      onNext() {
        this.$emit('onNext')
        this.input = null
      },
      onChange() {
        this.$emit('onChange', this.input)
      },
      onBack() {
        this.$emit('onBack')
      }
    },
  }
</script>
<style>
</style>
