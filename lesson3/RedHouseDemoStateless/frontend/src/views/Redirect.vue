<template>
  <div style="height: 100vh; display: flex; align-items: center; justify-content: center;">
    <h1>Получаем данные о пользователе...</h1>
  </div>
</template>

<script>
import { setAuthData } from '@/helpers'

export default {
  props: {
    provider: {
      type: String,
      required: true
    }
  },
  async created () {
    const error = this.$route.query.error
    const errors = { // https://www.oauth.com/oauth2-servers/authorization/the-authorization-response/
      access_denied: 'Отказано в доступе.',
      invalid_request: 'В запросе содержится не правильный параметр или параметр повторяется.',
      invalid_scope: 'Запрашиваемый scope не действителен или не известен.',
      server_error: 'Ошибка (код 500) на сайте сервиса.',
      temporarily_unavailable: 'Сервер проходит техническое обслуживание'
    }

    if (error) {
      this.$message.error(errors[error])
      this.$router.replace('/')
      return
    }

    const data = await this.$get('oauth/get-user/' + this.provider + window.location.search)

    setAuthData(data, this.provider)
    this.$router.replace({ name: 'profile' })
  }
}
</script>
