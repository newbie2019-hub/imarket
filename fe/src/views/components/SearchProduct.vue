<template>
  <div>
    <page-loading v-show="isSearching" :msg="'This may take a while. Please wait ...'" />
    <v-layout column class="mb-8 lh-small">
      <h1 v-if="user.info" class="mb-2">Hi, {{ user.info && user.info.first_name }} {{ user.info && user.info.last_name }}!</h1>
      <h1 v-else class="mb-2">Hi, User!</h1>
      <h2 class="grey--text font-weight-light">I-Market is here to serve you.</h2>
      <p class="mb-1 mt-8 grey--text">Delivery Address</p>
      <v-layout justify-space-between>
        <p class="text-h5 font-weight-bold lh-small">{{ user.address.formatted_address ? user.address.formatted_address : 'Please set your delivery address' }}</p>
        <v-btn @click.prevent="showAddressEdit = true" icon class="ml-5 blue--text">
          <v-icon> mdi-pencil </v-icon>
        </v-btn>
      </v-layout>
      <v-layout d-flex>
        <small class="mb-0 mr-4">
          <v-icon small> mdi-clock </v-icon>
          <!-- {{ distanceMatrix && distanceMatrix.rows[0].elements[1].duration.text }} -->
          {{user.address.eta}}
        </small>
        <small class="mb-0">
          <v-icon small> mdi-map-marker </v-icon>
          <!-- {{ distanceMatrix && distanceMatrix.rows[0].elements[1].distance.text }} -->
          {{user.address.total_distance}}
        </small>
      </v-layout>
    </v-layout>
    <v-layout>
      <v-text-field
        prepend-inner-icon="mdi-magnify"
        @keypress.enter="searchProductOrRecipe"
        append-outer-icon="mdi-cog"
        @click:append-outer="searchCustomDialog = true"
        v-model="search"
        class="rounded-lg"
        label="What would you like to eat?"
        required
        filled
        rounded
        clearable
        color="orange darken-2"
      ></v-text-field>
    </v-layout>
    <address-update v-show="showAddressEdit" @close="showAddressEdit = false" :showModal="showAddressEdit" />

    <v-dialog v-model="searchCustomDialog" max-width="450">
      <v-card>
        <v-card-title class="text-h5"> Search Filter </v-card-title>
        <v-card-text> Customize the behavior of the search filter to get exactly what you need. </v-card-text>
        <v-layout class="pl-5">
          <!-- <p class="mb-0">Please select the</p> -->
          <v-col>
            <v-checkbox class="mt-0" v-model="searchOptions.scrape" label="Recipe Scraping" color="blue darken-2" hide-details></v-checkbox>
          </v-col>
          <v-col>
            <v-checkbox class="mt-0" v-model="searchOptions.products" label="Market Products" color="blue darken-2" hide-details></v-checkbox>
          </v-col>
        </v-layout>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn color="green darken-1" plain @click="searchCustomDialog = false"> Close </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
  import { mapState } from 'vuex';
  import AddressUpdate from './AddressUpdate.vue';
  import API from '@/store/base/base';
  import PageLoading from './PageLoading.vue';
  export default {
    data: () => ({
      search: '',
      showAddressEdit: false,
      isSearching: false,
      searchCustomDialog: false,
    }),
    computed: {
      ...mapState('auth', ['user', 'distanceMatrix']),
      ...mapState('market', ['searchOptions'])
    },
    async mounted() {},
    methods: {
      async searchProductOrRecipe() {
        this.isSearching = true;
        if (this.searchOptions.scrape) {
          const { status, data } = await API.post(`http://localhost:8082/search?s=${this.search}`);
          this.$store.commit('market/SET_SCRAPE', data.result)
          console.log(status);
        }

        //Redirect to search result page
        if (this.$route.path != '/imarket/search') {
          this.$router.push(`/imarket/search?s=${this.search}`);
        }
        this.isSearching = false;
      },
    },
    components: { AddressUpdate, PageLoading },
  };
</script>
