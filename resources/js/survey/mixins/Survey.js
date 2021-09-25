export const MethodsSurvey = {
  data() {
    return {};
  },
  created() {},
  methods: {
    handleNext(token, routeName) {
      this.$router.replace({
        name: routeName,
        query: {
          token: token
        }
      });
    },
    backStep(step) {
      this.step = step
      this.emitProgress(step)


    },
    nextStep(step) {
      this.step = step
      this.emitProgress(step)


    },
    mutateKey(data) {
      let mutateData = data.map(function(item) {
        return {
          value: item.id,
          text: item.range || item.name || item.description,
          state: false
        };
      });
      return mutateData;
    },
    emitProgress(step) {
      this.step = step

      this.$emit("childinit", {
        progress: this.stepProgress[step - 1]
      });
    }
  }
};
