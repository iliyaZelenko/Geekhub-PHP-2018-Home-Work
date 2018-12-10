<template>
  <!--fixed-->
  <v-toolbar
    color="primary"
    height="64px"
    clipped-left
    dark
    app
  >
    <v-toolbar-side-icon @click="$emit('navigation-drawer')" />

    <!--<img
      class="app-toolbar__logo hidden-xs-only ml-3 mr-2"
      src="http://www.google.com.ua/doodle4google/images/splashes/featured.png"
      style="height: 55px;"
      @click="$router.push({ name: 'index' })"
    >-->
    <v-toolbar-title class="mr-4">
      {{ $t('blog_title') }}
    </v-toolbar-title>

    <v-toolbar-items class="hidden-sm-and-down">
      <v-btn
        :to="localePath({ name: 'index' })"
        exact
        flat
      >
        {{ $t('app_toolbar_navigation_home') }}
      </v-btn>

      <v-btn
        :to="localePath({ name: 'routes' })"
        flat
      >
        {{ $t('app_toolbar_navigation_routes') }}
      </v-btn>
    </v-toolbar-items>

    <v-spacer/>
    <!--
    &lt;!&ndash;solo-inverted&ndash;&gt;
    <v-text-field
      append-icon="mic"
      prepend-inner-icon="search"
      class="hidden-sm-and-down mx-3"
      label="Поиск"
      hide-details
      solo
      flat
      dense
      @click:append="() => {}"
    />
    -->

    <template v-if="$auth.loggedIn">
      <the-toolbar-user-notifications />

      <v-divider
        vertical
        inset
      />

      <the-toolbar-user-menu />
    </template>

    <template v-else>
      <!--
      <v-toolbar-items class="hidden-sm-and-down">
        <v-text-field
          v-validate="'required|' + $formValidator.rules.email"
          v-model="form.email"
          :error-messages="errors.collect('email')"
          class="x-3 mt-2"
          data-vv-name="email"
          label="Email"
          prepend-inner-icon="email"
          solo
          flat
        />

        <v-text-field
          v-validate="$formValidator.rules.password"
          v-model="form.password"
          :error-messages="errors.collect('password')"
          data-vv-name="password"
          class="ml-3 mr-1 mt-2"
          label="Password"
          prepend-inner-icon="lock"
          solo
          flat
        />
      </v-toolbar-items>

      <v-btn
        :loading="loading"
        round
        @click.prevent="submit"
      >
        Sign in
      </v-btn>

      <span class="ml-1">
        or
        <router-link :to="{ name: 'auth-signup' }">
          Sign up
        </router-link>
      </span>-->
    </template>

    <locale-switcher />
  </v-toolbar>
</template>

<script>
// import { mapActions } from 'vuex'
import TheToolbarUserNotifications from '~/components/layouts/main/TheToolbarUserNotifications'
import TheToolbarUserMenu from '~/components/layouts/main/TheToolbarUserMenu'
import LocaleSwitcher from '~/components/LocaleSwitcher'

export default {
  components: { TheToolbarUserNotifications, TheToolbarUserMenu, LocaleSwitcher },
  data: () => ({
    appName: 'my-app'
    // form: {
    //   email: 'test@test.com',
    //   password: 'password'
    // },
    // loading: false,
    // loadingLogout: false
  })
  // methods: {
  //   async submit () {
  //     await this.$actionWithLoading(this.signin, 'loading', this.form)
  //   },
  //   async resendEmailVerification () {
  //     await this.$actionWithLoading(this.resend, 'resendLoading')
  //   },
  //   ...mapActions('auth', ['signin', 'logout']),
  //   ...mapActions('auth/emailVerification', ['resend'])
  // }
}
</script>

<style>
/*.app-toolbar {*/
  /*background-color: #202124;*/
  /*!* height: 150px; *!*/
/*}*/

/* .app-toolbar .v-toolbar__extension {
  height: 60px !important;
} */

.app-toolbar__logo {
  cursor: pointer;
  /* background-color: white;*/
  /* padding: 3px; */
  margin-left: 30px;
  /* border-radius: 14px; */
}
</style>
