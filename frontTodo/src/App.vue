<template>
    <notifications />
    <router-view v-slot="{ Component }">
      <transition name="route" mode="out-in">
        <component :is="Component"></component>
      </transition>
    </router-view>
</template>

<script>
export default {
  name: 'App',
};
</script>

<style>
  #app {
    font-family: Avenir, Helvetica, Arial, sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    text-align: center;
    color: #2c3e50;
    margin-top: 60px;
  }

  /* route transitions */
  .route-enter-from {
    opacity: 0;
    transform: translateX(100px);
  }
  .route-enter-active {
    transition: all 0.3s ease-out; 
  }
  .route-leave-to {
    opacity: 0;
    transform: translateX(-100px);
  }
  .route-leave-active {
    transition: all 0.3s ease-in; 
  }
</style>
