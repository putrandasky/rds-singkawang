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
    nextStep(step, progressData) {
      this.step = step
      let data = {
        progress: progressData
      }
      this.$emit("childinit", data);

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
    }
  }
};
