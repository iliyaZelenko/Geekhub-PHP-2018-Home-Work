<template>
  <div>
    <b-alert
      :show="!!error"
      variant="danger"
      dismissible
      fade
    >
      {{ error }}
    </b-alert>

    <b-alert
      :show="!!successMessage"
      variant="success"
    >
      {{ successMessage }}
    </b-alert>

    <editor
      v-model="content"
    ></editor>

    <p>
      Content length (with tags): {{ content.length }}.
    </p>

    <b-button
      class="mt-2"
      variant="primary"
      @click="onSubmit"
    >
      {{ loading ? 'Loading...' : 'Send'  }}
    </b-button>
  </div>
</template>

<script>
export default {
  props: ['apiEndpoint', 'parentCommentId'],
  data: () => ({
    successMessage: null,
    error: null,
    loading: false,
    content: ``
  }),
  methods: {
    async onSubmit () {
      this.successMessage = this.error = null
      this.loading = true

      try {
        const response = await fetch(this.apiEndpoint, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            message: this.content,
            parentCommentId: this.parentCommentId || null
          })
        })
        const res = await response.json()

        if (res.error) {
          this.error = res.error
        } else {
          this.successMessage = res.successMessage
        }
        console.log(res)
      } catch (e) {}

      this.loading = false
    }
  }
}
</script>

