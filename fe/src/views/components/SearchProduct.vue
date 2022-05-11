<template>
  <div>
    <v-layout column class="mb-8 lh-small">
      <h1 v-if="user.info" class="mb-2">Hi, {{ user.info && user.info.first_name }} {{ user.info && user.info.last_name }}!</h1>
      <h1 v-else class="mb-2">Hi, User!</h1>
      <h2 class="grey--text font-weight-light">I-Market is here to serve you.</h2>
    </v-layout>
    <v-layout>
      <v-text-field prepend-inner-icon="mdi-magnify" v-model="search" class="rounded-lg" label="What would you like to eat?" required filled rounded clearable color="orange darken-2"></v-text-field>
    </v-layout>
  </div>
</template>
<script>
  import { mapState } from 'vuex';
  export default {
    data: () => ({
      search: '',
    }),
    computed: {
      ...mapState('auth', ['user']),
    },
    async mounted(){
     await this.searchRecipe()
    },
    methods: {
     async searchRecipe(){
      // const res = API(`https://panlasangpinoy.com/?s=${this.search}`)
      // const browser  = await puppeteer.launch()
      // const page = await browser.newPage()
      // await page.goto(`https://panlasangpinoy.com/?s=${this.search}`)

     }
    },
  };
</script>
