<template>
  <div id="chart">
    <apexchart id="apexchart" width="650" type="area" :options="chartOptions" :series="series"></apexchart>
  </div>
</template>
<script>
  import VueApexCharts from 'vue-apexcharts';
  import { mapState } from 'vuex';
  export default {
    props: ['data'],
    components: {
      apexchart: VueApexCharts,
    },
    data: () => ({
      series: [
      ],
      chartOptions: {
        chart: {
          width: 650,
          type: 'area',
          zoom: {
            enabled: false,
          },
        },
        dataLabels: {
          enabled: false,
        },
        stroke: {
          curve: 'smooth',
        },
        responsive: [
          {
            breakpoint: '720px',
            options: {
              chart: {
                width: '100%',
                height: 650,
                type: 'bar',
              },
            },
          },
        ],
        title: {
          text: 'Revenue Statistics',
          align: 'left',
        },
        subtitle: {
          text: 'Sold Products',
          align: 'left',
        },
        labels: [],
        xaxis: {
          type: 'datetime',
        },
        yaxis: {
          opposite: true,
        },
        legend: {
          horizontalAlign: 'left',
        },
      },
    }),
    async mounted() {
      console.log(this.data);
    },
    methods: {
      setData() {
        let series = []
        this.data.map((data) => {
          series.push(data.total)
          this.chartOptions.labels.push(data.date);
        });

        this.series = [{name: 'Product Sales', data: series}]
      },
    },
    computed: {},
    watch: {
      data() {
        this.setData();
      },
    },
  };
</script>
