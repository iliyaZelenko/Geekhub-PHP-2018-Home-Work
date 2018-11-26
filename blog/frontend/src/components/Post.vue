<template>
  <div
    class="post"
  >
    <v-card>
      <v-img
        src="https://cdn.vuetifyjs.com/images/cards/desert.jpg"
        aspect-ratio="2.75"
      />

      <v-card-title primary-title>
        <div>
          <h3 class="headline mb-0">
            {{ post.title }}
          </h3>
          <div>
            {{ post.text }}
          </div>
        </div>
      </v-card-title>

      <v-card-actions>
        <v-btn
          flat
          color="orange"
        >
          Поделится
        </v-btn>
        <v-btn
          flat
          color="orange"
        >
          Открыть
        </v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script lang="ts">
import Vue from 'vue'
import Component from 'nuxt-class-component'
import { Prop } from 'vue-property-decorator'

@Component
class Post extends Vue {
  @Prop({}) post!: {}
  // @Prop(String) title!: string
  // @Prop({
  //   type: String,
  //   default: 'default value'
  // }) text!: string
  // @Prop([String, Boolean]) propC: string | boolean
}

export default Post
</script>
