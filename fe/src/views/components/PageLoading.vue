<template>
  <div class="overlay-loading">
    <Origami />
    <p class="mt-5">{{msg}}</p>
  </div>
</template>
<script>
  import Origami from 'vue-loading-spinner/src/components/Origami';
  export default {
    props: {
      msg: String
    },
    components: { Origami },
  };
</script>
