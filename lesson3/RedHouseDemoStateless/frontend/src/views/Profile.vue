<template>
  <div>
    <div
      v-if="user"
      class="profile"
    >
      <img
        :src="user.avatar"
        class="avatar"
      >

      <h2>{{ user.name }}</h2>

      {{ user.email }}
      <br><br>

      <div>
        <el-button
          :loading="fetchUserLoading"
          type="success"
          @click="fetchUser"
        >
          Fetch user
        </el-button>

        <el-button
          type="danger"
          @click="logout"
        >
          Logout
        </el-button>
      </div>
    </div>

    <div v-loading="fetchUserLoading">
      <b>User:</b><br><br>
      <json :data="user" />
      <br>
      <b>Original user:</b><br><br>
      <json :data="originalUser" />
      <br>
      <b>Access token:</b><br><br>
      <json :data="token" />
    </div>
  </div>
</template>

<script>
import Json from 'vue-json-pretty'
import { setAuthData } from '@/helpers'

export default {
  components: { Json },
  data: () => ({
    fetchUserLoading: false,
    user: JSON.parse(localStorage.getItem('user')),
    originalUser: JSON.parse(localStorage.getItem('originalUser')),
    token: localStorage.getItem('token'),
    provider: localStorage.getItem('provider')
  }),
  methods: {
    async fetchUser () {
      this.fetchUserLoading = true

      const data = await this.$get('oauth/get-user-by-token', {
        params: {
          provider: this.provider,
          token: this.token
        }
      })
      setAuthData(data, this.provider)

      this.fetchUserLoading = false
    },
    logout () {
      for (let name of ['user', 'originalUser', 'token', 'provider']) {
        localStorage.removeItem(name)
      }

      this.$router.push('/')
    }
  }
}
</script>

<style lang="stylus">
.profile
  text-align: center;

.avatar
  margin: 0 auto;
  background: #fff;
  width: 5em;
  padding: 0.25em;
  border-radius: .4em;
  box-shadow: 0 0 0.1em rgba(0, 0, 0, 0.35);
</style>
