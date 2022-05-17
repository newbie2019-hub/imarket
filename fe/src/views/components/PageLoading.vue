<template>
  <div class="overlay-loading">
    <Origami />
  </div>
</template>
<script>
  import Origami from 'vue-loading-spinner/src/components/Origami';
  export default {
    components: { Origami },
  };
</script>
