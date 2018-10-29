<template>
  <div id="app">
    <!--<div id="nav">
      <router-link to="/">Home</router-link> |
      <router-link to="/about">About</router-link>
    </div>-->
    <el-container>
      <el-main id="main">
        <transition
          name="el-fade-in-linear"
          mode="out-in"
        >
          <router-view/>
        </transition>
      </el-main>
    </el-container>
  </div>
</template>
<style lang="stylus">
#main
  min-height: 90vh;
  display: flex;
  align-items: center;
  justify-content: center;

#app
  font-family 'Avenir', Helvetica, Arial, sans-serif
  -webkit-font-smoothing antialiased
  -moz-osx-font-smoothing grayscale

#nav
  padding 30px
  a
    font-weight bold
    color #2c3e50
    &.router-link-exact-active
      color #42b983
</style>
