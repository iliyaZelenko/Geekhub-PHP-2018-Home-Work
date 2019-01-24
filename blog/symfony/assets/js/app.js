
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import '../css/app.sass'

import Vue from 'vue'
import InstantSearch from 'vue-instantsearch'
import BootstrapVue from 'bootstrap-vue'
import Editor from './components/Editor.vue'
import CreateComment from './components/CreateComment'
import Search from './components/Search'

const delimiters = ['${', '}']

Vue.config.delimiters = delimiters
Vue.use(BootstrapVue)
Vue.use(InstantSearch)

window.vm = init()

function init () {
  const addComponent = (name, params) => {
    Vue.component(name, params)
  }

  addComponent('editor', Editor)
  addComponent('create-comment', CreateComment)
  addComponent('search', Search)

  addComponent('blog-date', {
    delimiters: ['${', '}'],
    props: ['date', 'time'],
    template: '<small>🕒 <time class=".text-muted">${ output }</time></small>',
    computed: {
      output: {
        cached: false,
        get () {
          // const method = this.time ? 'toLocaleTimeString' : 'toLocaleDateString'
          // в JS не Unix, а в милисекундах
          return new Date(+this.date * 1000).toLocaleString();
        }
      }
    }
  })

  return initVue()
}

function initVue () {
  new Vue({
    el: '#app',
    delimiters: delimiters,
    data: () => ({
      ...vueExtend.data
    }),
    methods: {
      ...vueExtend.methods
    },
    watch: {
      ...vueExtend.watch
    },
    created () {
      vueExtend.created()
    }
  });
}
