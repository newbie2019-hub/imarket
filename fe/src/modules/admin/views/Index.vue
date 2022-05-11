<template>
  <div>
    <Navigation />
    <div :class="{ 'ml-9 mt-5 mr-5': $vuetify.breakpoint.md | $vuetify.breakpoint.lg | $vuetify.breakpoint.sm }">
      <router-view />
    </div>
  </div>
</template>
<script>
  import Navigation from './components/Navigation.vue';
  export default {
    components: { Navigation },
    data: () => ({}),
    async mounted() {},
    methods: {},
    computed: {},
  };
</script>
