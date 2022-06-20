<template>
  <div class="mt-15">
    <div class="mt-15 pt-5">
      <p class="text-h5 font-weight-bold custom-primary-color mb-0">Overall Summary</p>
      <p>Good Day! Here is your summary for IMarket</p>
    </div>
    <v-row>
      <v-col cols="12" sm="6" md="4" lg="4" xl="4">
        <v-card elevation="2" color="blue darken-1" class="pa-3" rounded="lg" dark link to="/admin/users">
          <div class="d-flex flex-no-wrap justify-space-between align-end mb-2">
            <div>
              <div class="fit-content">
                <v-icon large class=""> mdi-car-brake-retarder </v-icon>
              </div>
              <v-card-title class="text-h5 font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Users</v-card-title>
            </div>
            <div>
              <p class="text-h1 pr-5 font-weight-bold mb-0">{{ dashboardSummary.userCount }}</p>
            </div>
          </div>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="4" lg="4" xl="4">
        <v-card elevation="2" color="deep-purple" class="pa-3" rounded="lg" dark link to="/admin/stores">
          <div class="d-flex flex-no-wrap justify-space-between align-end mb-2">
            <div>
              <div class="fit-content">
                <v-icon large class=""> mdi-facebook-workplace </v-icon>
              </div>
              <v-card-title class="text-h5 font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Stores</v-card-title>
            </div>
            <div>
              <p class="text-h1 pr-5 font-weight-bold mb-0">{{ dashboardSummary.storeCount }}</p>
            </div>
          </div>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="4" lg="4" xl="4">
        <v-card elevation="2" color="blue-grey" class="pa-3" rounded="lg" dark link to="/admin/products">
          <div class="d-flex flex-no-wrap justify-space-between align-end mb-2">
            <div>
              <div class="fit-content">
                <v-icon large class=""> mdi-bookmark-multiple </v-icon>
              </div>
              <v-card-title class="text-h5 font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Products</v-card-title>
            </div>
            <div>
              <p class="text-h1 pr-5 font-weight-bold mb-0">{{ dashboardSummary.productCount }}</p>
            </div>
          </div>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="4" lg="4" xl="4">
        <v-card elevation="2" color="teal" class="pa-3" rounded="lg" dark>
          <div class="d-flex flex-no-wrap justify-space-between align-end mb-2">
            <div>
              <div class="fit-content">
                <v-icon large class=""> mdi-package </v-icon>
              </div>
              <v-card-title class="text-h5 font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Orders Today</v-card-title>
            </div>
            <div>
              <p class="text-h1 pr-5 font-weight-bold mb-0">{{ formatNumeric(dashboardSummary.todaysOrderCount) }}</p>
            </div>
          </div>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="4" lg="4" xl="4">
        <v-card elevation="2" color="green " class="pa-3" rounded="lg" dark link to="/admin/riders">
          <div class="d-flex flex-no-wrap justify-space-between align-end mb-2">
            <div>
              <div class="fit-content">
                <v-icon large class=""> mdi-account </v-icon>
              </div>
              <v-card-title class="text-h5 font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Riders</v-card-title>
            </div>
            <div>
              <p class="text-h1 pr-5 font-weight-bold mb-0">{{dashboardSummary.ridersCount}}</p>
            </div>
          </div>
        </v-card>
      </v-col>
      <v-col cols="12" sm="6" md="4" lg="4" xl="4">
        <v-card elevation="2" color="deep-orange lighten-1 " class="pa-3" rounded="lg" dark link to="/admin/categories">
          <div class="d-flex flex-no-wrap justify-space-between align-end mb-2">
            <div>
              <div class="fit-content">
                <v-icon large class=""> mdi-source-branch </v-icon>
              </div>
              <v-card-title class="text-h5 font-weight-bold mb-0 pa-0 pl-4 pb-2 pt-4">Categories</v-card-title>
            </div>
            <div>
              <p class="text-h1 pr-5 font-weight-bold mb-0">{{ formatNumeric(dashboardSummary.categoryCount) }}</p>
            </div>
          </div>
        </v-card>
      </v-col>
    </v-row>
    <div class="mt-14">
      <div>
        <p class="text-h5 font-weight-bold custom-primary-color mt-8 mb-0">Products Summary</p>
        <p>Here is your product summary as of today</p>
      </div>
      <v-row align="strech">
        <v-col cols="12" sm="12" md="6" lg="5" xl="5">
          <v-card elevation="1" class="pa-5">
            <v-data-table :footer-props="{ disableItemsPerPage: true }" :headers="headers" :items="latestProducts" :items-per-page="5" class="elevation-0">
              <template v-slot:item.product_info.name="{ item }">
                <v-layout align-center class="pt-3 pb-3">
                  <v-avatar class="ma-0" size="45" color="primary" tile>
                    <img class="cursor-pointer" v-if="item.product_info.image" :src="`http://127.0.0.1:8000/images/products/${item.product_info.image}`" />
                    <p v-else class="white--text font-weight-bold mb-0">{{ item.product_info.name[0] }}</p>
                  </v-avatar>
                  <p class="mb-0 ml-3">{{ item.product_info.name }}</p>
                </v-layout>
              </template>
              <template v-slot:item.orders_sum_quantity="{ item }">
                <p>{{ item.orders_sum_quantity ? item.orders_sum_quantity : '0' }}</p>
              </template>
            </v-data-table>
          </v-card>
        </v-col>
        <v-col cols="12" sm="12" md="6" lg="7" xl="7">
          <v-card class="h-100 pa-5" elevation="1">
            <v-row justify="center" align="center" class="h-100">
              <area-chart :data="areaChart"/>
            </v-row>
          </v-card>
        </v-col>
      </v-row>
    </div>
    <div class="mt-15">
      <p class="text-h5 font-weight-bold mt-4 custom-primary-color mb-0">Latest Transaction</p>
      <p>Here are your latest transactions for this day</p>
      <latest-transactions :data="latestTransactions" />
    </div>
  </div>
</template>
<script>
  import { mapState } from 'vuex';
  import { formatNumeric } from '@/assets/js/utilities.js';
  import LatestTransactions from '../components/LatestTransactions.vue';
  import AreaChart from '../components/AreaChart.vue'
  export default {
    mixins: [formatNumeric],
    components: { LatestTransactions, AreaChart },
    data: () => ({
      headers: [
        { text: 'Product', value: 'product_info.name' },
        { text: 'Available', value: 'product_info.quantity' },
        { text: 'Sold', value: 'orders_sum_quantity' },
        // { text: 'Sold', value: 'orders_sum_quantity'},
      ],
    }),
    async mounted() {
      await this.$store.dispatch('adminDashboard/getDashboardSummary');
    },
    methods: {},
    computed: {
      ...mapState('auth', ['user']),
      ...mapState('adminDashboard', ['latestProducts', 'dashboardSummary', 'latestTransactions', 'areaChart']),
    },
  };
</script>
