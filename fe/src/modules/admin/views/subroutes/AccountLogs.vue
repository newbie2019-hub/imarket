<template>
  <div>
    <v-layout class="mt-10"></v-layout>
    <v-container class="mt-10">
      <v-row justify="center">
        <!-- <v-col cols="" md="4" lg="4"> </v-col> -->
        <v-col cols="11" md="12" lg="12">
          <v-layout column>
            <h1>Account Logs</h1>
            <p>To ensure account security, your actions are being logged by the system</p>
          </v-layout>
        </v-col>
      </v-row>
      <v-row>
        <v-col>
          <v-layout justify-space-between>
            <p>Account Logs</p>
            <v-col sm="5" md="4" lg="4">
              <v-text-field v-model="search" outlined dense append-icon="mdi-magnify" class="mb-5" label="Search" hide-details></v-text-field>
            </v-col>
          </v-layout>
          <v-data-table :loading="isLoading" :loading-text="'Retrieving your account logs. Please wait...'" :headers="headers" :items="userLogs" :search="search">
            <template v-slot:item.event="{ item }">
              <v-chip :color="getColor(item.event)" dark small>
                {{ item.event }}
              </v-chip>
            </template>
          </v-data-table>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>
<script>
  import { mapState } from 'vuex';
  export default {
    data: () => ({
      isLoading: false,
      search: '',
      headers: [
        {
          text: 'Activity',
          align: 'start',
          sortable: false,
          value: 'name',
        },
        { text: 'Event', value: 'event' },
        { text: 'Description', value: 'description' },
        { text: 'Timestamp', value: 'created_at' },
      ],
    }),
    async mounted() {
      await this.$store.dispatch('auth/getLogs');
    },
    methods: {
      getColor(event) {
        switch (event) {
          case 'delete':
            return 'error';
          case 'login':
            return 'success';
          case 'update':
            return 'green';
          case 'logout':
            return 'error';
        }
      },
    },
    computed: {
      ...mapState('auth', ['user', 'userLogs']),
    },
  };
</script>
