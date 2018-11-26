<template>
  <section class="w-100 h-100">
    <v-layout
      justify-center
      class="ma-auto"
      style="max-width: 500px;"
    >
      <v-pagination
        v-model="page"
        :length="pages"
        color="blue"
      />
    </v-layout>

    <v-layout
      v-if="loading"
      class="ma-5"
      justify-center
    >
      <v-progress-circular
        :size="50"
        color="blue"
        indeterminate
      />
    </v-layout>

    <v-container
      v-if="!loading"
      fluid
      grid-list-xl
    >
      <v-layout
        row
        wrap
        justify-center
      >
        <v-flex
          v-for="post in posts"
          :key="post.id"
          sm3
        >
          <post
            :post="post"
          />
        </v-flex>
      </v-layout>
    </v-container>

    <v-layout
      v-if="!loading"
      justify-center
    >
      <v-pagination
        v-model="page"
        :length="pages"
        color="blue"
      />
    </v-layout>
  </section>
</template>

<script lang="ts">
import Vue from 'vue'
import Component from 'nuxt-class-component'
import * as BackendRoutes from '../../store/modules/BackendRoutes'
import Post from '../../components/Post.vue'
// , State, Getter
import { namespace } from 'vuex-class'
import { Watch } from 'vue-property-decorator'
// import * as people from '~/store/modules/people'

const BackendRoutesModule = namespace(BackendRoutes.NAME)

@Component({
  components: {
    Post
  }
})
class Posts extends Vue {
  async asyncData ({ app, params: { page } }) {
    page = +page

    return {
      ...await getByPage(page, app),
      page
    }
  }

  posts = [{
    title: 'Заголовок',
    text: 'Описание'
  }]

  loading = false
  page = null
  pages = null

  // (people.name)
  @BackendRoutesModule.State routes

  @Watch('page')
  async onPageChange (page: number) {
    // TODO ActionWithLoading
    this.loading = true
    const { posts } = await getByPage(page, this)

    this.posts = posts
    this.loading = false
  }
}

async function getByPage (page: number = 1, context = this) {
  const { posts, pages } = await context.$get('posts/' + page)

  return { posts, pages }
}

export default Posts
</script>
