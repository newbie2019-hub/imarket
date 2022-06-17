<template>
  <div class="mb-10">
    <v-layout justify-end>
      <v-col sm="5" md="4" lg="4">
        <v-text-field v-model="search" outlined dense append-icon="mdi-magnify" class="mb-5" label="Search" hide-details></v-text-field>
      </v-col>
    </v-layout>
    <v-data-table :headers="headers" :items="data" :items-per-page="10" :search="search" class="elevation-1">
      <template v-slot:item.buyer.full_name="{ item }">
        <v-layout align-center class="mt-3">
          <p class="text-no-wrap">{{ item.buyer.full_name }}</p>
        </v-layout>
      </template>
      <template v-slot:item.products="{ item }">
        <p class="text-no-wrap">{{ item.content.length }}</p>
      </template>
      <template v-slot:item.subtotal="{ item }">
        <p class="text-no-wrap">{{ formatCurrency(item.subtotal) }}</p>
      </template>
      <template v-slot:item.delivery_fee="{ item }">
        <p class="text-no-wrap">{{ formatCurrency(item.delivery_fee) }}</p>
      </template>
      <template v-slot:item.total="{ item }">
        <p class="text-no-wrap">{{ formatCurrency(item.total) }}</p>
      </template>
      <template v-slot:item.status="{ item }">
        <v-chip class="ma-2" color="red" text-color="white" small> {{ item.status }} </v-chip>
      </template>
    </v-data-table>
  </div>
</template>
<script>
  import { formatCurrency } from '@/assets/js/utilities';
  export default {
    mixins: [formatCurrency],
    props: ['data'],
    data: () => ({
      search: '',
      headers: [
        {
          text: 'Transaction ID',
          sortable: false,
          value: 'transaction_id',
        },
        { text: 'Buyer', value: 'buyer.full_name' },
        { text: 'Products', value: 'products' },
        { text: 'Status', value: 'status' },
        { text: 'Sub Total', value: 'subtotal' },
        { text: 'Delivery Fee', value: 'delivery_fee' },
        { text: 'Total', value: 'total' },
        { text: 'Date', value: 'created_at' },
      ],
    }),
    async mounted() {},
    methods: {},
    watch: {},
  };
</script>
