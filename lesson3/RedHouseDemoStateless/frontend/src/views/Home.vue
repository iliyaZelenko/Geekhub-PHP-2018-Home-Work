<template>
  <div>
    <h3 style="text-align: center;">
      Как пожелаете войти?
    </h3>
    <el-row>
      <el-button
        @click="signin('google')"
      >
        Google
      </el-button>
      <el-button
        type="primary"
        @click="signin('facebook')"
      >
        Facebook
      </el-button>
      <el-button
        type="info"
        @click="signin('github')"
      >
        Github
      </el-button>
      <!--
      <el-button type="success">
        Facebook
      </el-button>
      <el-button type="warning">Warning</el-button>
      <el-button type="danger">Danger</el-button>
      -->
    </el-row>
  </div>
</template>

<script>
export default {
  name: 'Home',
  data: () => ({
    //
  }),
  methods: {
    async signin (provider) {
      location.href = await this.$get('oauth/get-auth-url/' + provider)
    }
  }
}
</script>
