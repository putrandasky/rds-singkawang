<template>
  <div class="full-bg-survey bg-survey ">

    <b-container class="text-center py-3">
      <b-img fluid src="/img/icon-ptpii-kemenhub.svg"></b-img>
    </b-container>
    <b-container fluid class="text-center py-3 bg-primary text-white">
      <div class="h4 ">
        <b> Dashboard</b>
      </div>
    </b-container>

    <transition name="fade" mode="out-in">
      <router-view></router-view>
    </transition>

  </div>
</template>

<style>
  .slide-shrink-fade-enter-active {
    transition: all 0.1s ease;
  }

  .slide-shrink-fade-leave-active {
    transition: all 0.1s cubic-bezier(1, 0.5, 0.8, 1);
  }

  .slide-shrink-fade-enter,
  .slide-shrink-fade-leave-to {
    /* transform: translateX(10px); */
    opacity: 0;
    /* height: 0%; */
    font-size: 99%;
  }
</style>
