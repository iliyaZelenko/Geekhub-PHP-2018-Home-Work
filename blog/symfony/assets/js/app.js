import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import '../css/app.css'

import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'

const delimiters = ['${', '}']

Vue.config.delimiters = delimiters
Vue.use(BootstrapVue);

window.vm = init()

function init () {
  const addComponent = (name, params) => {
    Vue.component(name, params)
  }

  addComponent('blog-date', {
    delimiters: ['${', '}'],
    props: ['date', 'time'],
    template: '<time class=".text-muted">${ output }</time>',
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
    created () {
      vueExtend.created()
    }
  });
}
