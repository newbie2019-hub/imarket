<template>
  <div>
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
          {{ distanceMatrix && distanceMatrix && distanceMatrix.rows[0].elements[1].duration.text }}
        </small>
        <small class="mb-0">
          <v-icon small> mdi-map-marker </v-icon>
          {{ distanceMatrix && distanceMatrix && distanceMatrix.rows[0].elements[1].distance.text }}
        </small>
      </v-layout>
    </v-layout>
    <v-layout>
      <v-text-field prepend-inner-icon="mdi-magnify" @keypress.enter="searchProductOrRecipe" v-model="search" class="rounded-lg" label="What would you like to eat?" required filled rounded clearable color="orange darken-2"></v-text-field>
    </v-layout>
    <address-update v-show="showAddressEdit" @close="showAddressEdit = false" :showModal="showAddressEdit" />
  </div>
</template>
<script>
  import { mapState } from 'vuex';
  import AddressUpdate from './AddressUpdate.vue';
  import API from '@/store/base/base'
  export default {
    data: () => ({
      search: '',
      showAddressEdit: false,
    }),
    computed: {
      ...mapState('auth', ['user', 'distanceMatrix']),
    },
    async mounted() {
    },
    methods: {
      async searchProductOrRecipe() {
        const {status, data} = await API.post(`http://localhost:8082/search?s=${this.search}`)
        console.log(status)
      },
    },
    components: { AddressUpdate },
  };
</script>
