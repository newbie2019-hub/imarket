<template>
  <v-app>
    <v-main>
      <!-- <vue-page-transition name="overlay-up-full"> -->
      <router-view />
      <!-- </vue-page-transition> -->
    </v-main>
  </v-app>
</template>

<script>
  export default {
    name: 'App',

    data: () => ({
      //
    }),
  };
</script>
