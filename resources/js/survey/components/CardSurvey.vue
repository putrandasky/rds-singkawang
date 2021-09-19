<template>
  <b-col lg="4" md="6" sm="8">
    <b-card class="shadow-sm mb-3">
      <div>
        <p>
          <slot name="question"></slot>
        </p>
        <b-form-radio-group stacked v-model="input" :options="options" button-variant="outline-warning" buttons name="gender" class="btn-block" @change="onChange"></b-form-radio-group>
      </div>
    </b-card>
    <b-btn v-if="input != null" @click="onNext" variant="primary" class="font-weight-bold" block>{{action}}</b-btn>
  </b-col>
</template>
<script>
  export default {
    name: 'CardSurvey',
    props: ['action', 'options'],
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
      }
    },
  }
</script>
<style>
</style>
