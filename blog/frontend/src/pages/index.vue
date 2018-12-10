<template>
  <section class="w-100 h-100">
    <nuxt-link :to="localePath({ name: 'posts-page', params: { page: 1 } })">
      Посты
    </nuxt-link>

    <span class="test">
      123
    </span>
    <span>
      123
    </span>
  </section>
</template>

<script lang="ts">
import Vue from 'vue'
import Component from 'nuxt-class-component'

@Component
class Home extends Vue {
  get aaa () {
    return this.$route.path
  }
}

export default Home
</script>
