<template>
  <b-container class="my-3">
    <b-row>
      <b-col lg="6">
        <b-row>
          <b-col lg="6" sm="6" xs="12">
            <b-card class="mb-4 shadow-sm" title="Valid Responden">
              <div class="d-flex justify-content-between align-items-center">
                <h2>{{ data.stats_respondent.valid }}</h2>
                <i class="la la-arrow-right text-primary la-lg rotate-315" />
              </div>
              <div class="d-flex flex-wrap justify-content-between">
                <div class="mt">
                  <h6>{{ data.stats_respondent.total }}</h6>
                  <p class="text-muted mb-0 mr">
                    <small>Total</small>
                  </p>
                </div>
                <div class="mt">
                  <h6>{{ data.stats_respondent.target }}</h6>
                  <p class="text-muted mb-0">
                    <small>Target</small>
                  </p>
                </div>
                <div class="mt">
                  <h6>{{ data.stats_respondent.remaining }}</h6>
                  <p class="text-muted mb-0 mr">
                    <small>Remaining</small>
                  </p>
                </div>
              </div>
            </b-card>
          </b-col>
          <b-col lg="6" sm="6" xs="12">
            <b-card class="mb-4 shadow-sm" title="Tujuan">
              <pie-chart :height="180" :label="['Dari Singkawang', 'Ke Singkawang']" :data="data.respondent_destination.map(data => data.respondent_count )" />
            </b-card>
          </b-col>
          <b-col lg="12" sm="12">
            <b-card class="mb-4 shadow-sm" title="Responden Stats">
              <b-card-body class="p-0">
                <respondent-mix-overview :height="180" :chartDatasets="data.chart" :chartLabel="data.chart.date" />
              </b-card-body>
            </b-card>
          </b-col>
        </b-row>
      </b-col>
      <b-col lg="6" sm="12" class="mb-4">
        <b-card class="shadow-sm h-100">
          <h4 name="title">
            Demografi Respondents
            <!-- Respondents By
            <span v-for="(v,i) in demography" :key="i" v-show="isDemography == v.id">{{v.title}}</span> -->
          </h4>
          <b-card-body class="h-100 pt-0 text-right">
            <div class="w-100" style="overflow-x:auto">
              <b-button-group>
                <b-button v-for="(v,i) in demography" :key="i" size="sm" :pressed="isDemography == v.id" variant="outline-secondary" @click="handleDemographyCard(v.id)">{{v.title}}</b-button>
              </b-button-group>
            </div>
            <horizontal-bar v-if="isDemography == 1" :height="300" :labelData="data.gender.map(data => data.description)" :data="data.gender.map(data => data.respondents_count)" label="Total Responden" />
            <horizontal-bar v-if="isDemography == 2" :height="300" :labelData="data.age.map(data => data.description)" :data="data.age.map(data => data.respondents_count)" label="Total Responden" />
            <horizontal-bar v-if="isDemography == 3" :height="300" :labelData="data.job.map(data => data.description)" :data="data.job.map(data => data.respondents_count)" label="Total Responden" />
            <horizontal-bar v-if="isDemography == 4" :height="300" :labelData="data.income.map(data => data.description)" :data="data.income.map(data => data.respondents_count)" label="Total Responden" />
            <horizontal-bar v-if="isDemography == 6" :height="300" :labelData=" data.travel_purpose.map(data => data.description) " :data="data.travel_purpose.map( data => data.respondents_count ) " label="Total Responden" />
            <horizontal-bar v-if="isDemography == 5" :height="300" :labelData=" data.vehicle.map(data => data.description) " :data="data.vehicle.map( data => data.respondents_count ) " label="Total Responden" />
          </b-card-body>
        </b-card>
      </b-col>
      <!-- <b-col lg="12">
        <b-card class="mb-4 shadow-sm" title="Kota">
          <horizontal-bar :height="300" :labelData="data.respondent_by_city.map(data => data.description)" :data="data.respondent_by_city.map(data => data.respondents_count)" label="Total Respondent" />


        </b-card>
      </b-col> -->
      <b-col lg="12">
        <b-card class="mb-4 shadow-sm" title="Kota Tujuan/Asal berdasarkan Kategori">
          <multi-bar :height="300" :chartLabel=" data.respondent_by_city_category.label.Kategori " :chartDatasets="data.respondent_by_city_category.data_set " label="Total Responden" xLabel="Kategori" />


        </b-card>
      </b-col>
      <b-col lg="6" class="mb-4">
        <b-card title="Kota Tujuan / Asal" class="shadow-sm">
          <b-card-body class="p-0">
            <div class="list-group-small">
              <div v-for="(v, i) in data.respondent_by_city" :key="i" class="d-flex border-bottom px-3">
                <div>
                  <span>{{ v.description }}</span>
                </div>
                <div class="ml-auto text-right" style="width:200px">

                  <b-badge variant="secondary" style="width:25px">{{ v.target }}</b-badge>
                  <b-badge variant="primary" style="width:25px">{{ v.respondents_count }}</b-badge>
                  <b-badge :variant="v.respondents_count - v.target < 0 ?  'danger':'success'" style="width:25px">{{ v.respondents_count - v.target  }}</b-badge>
                </div>
              </div>
            </div>
          </b-card-body>
        </b-card>
      </b-col>
      <b-col lg="6">
        <b-card class="mb-4 shadow-sm" title="Kategori Responden">
          <vertical-bar :height="300" :labelData="data.respondent_by_category.map(data => data.description)" :data="data.respondent_by_category.map(data => data.respondents_count)" scaleLabel="Responden" label="Total Responden" />


        </b-card>
      </b-col>


    </b-row>

  </b-container>
</template>
<script>
  import Loading from "vue-loading-overlay";

  import GenderPie from "../components/GenderPie.vue";
  import PieChart from "../components/PieChart.vue";
  import HorizontalBar from "../components/HorizontalBar.vue";
  import VerticalBar from "../components/VerticalBar.vue";
  import RespondentMixOverview from "../components/RespondentMixOverview.vue";
  import MultiBar from "../components/MultiBar.vue";
  export default {
    name: 'Dashboard',
    components: {
      Loading,
      GenderPie,
      RespondentMixOverview,
      HorizontalBar,
      VerticalBar,
      PieChart,
      MultiBar
    },
    data: function() {
      return {
        isLoading: true,
        data: {},
        isDemography: 1,
        demography: [{
            id: 1,
            title: "Jenis Kelamin"
          },
          {
            id: 2,
            title: "Umur"
          },
          {
            id: 3,
            title: "Pekerjaan"
          },
          {
            id: 4,
            title: "Pendapatan"
          },
          {
            id: 5,
            title: "Kendaraan"
          },
          {
            id: 6,
            title: "Tujuan Perjalanan"
          }
        ],
      }
    },
    created() {
      this.getData()
    },
    methods: {
      handleDemographyCard(i) {
        if (i == this.isDemography) {
          return;
        }
        this.isDemography = 0;
        setTimeout(() => {
          this.isDemography = i;
        }, 1);
      },
      getData() {
        axios
          .get(`/dashboard/index`)
          .then(response => {
            this.data = response.data;
            this.isLoading = false;

          })
          .catch(error => {
            console.log(error);
          });
      },
    },
  }
</script>
<style>
</style>
