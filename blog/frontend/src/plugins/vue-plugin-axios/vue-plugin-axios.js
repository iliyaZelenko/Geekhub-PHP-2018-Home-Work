import Vue from 'vue'
import axios from 'axios'
import VueAxios from 'vue-plugin-axios'

export default ({ store, app }, inject) => {
  VueAxios.install(Vue, {
    axios,
    config: {
      baseURL: process.env.FULL_API_URL,
      headers: {
        // so laravel (and maybe symfony) will understand that this is ajax $request->ajax()
        'X-Requested-With': 'XMLHttpRequest'
      }
    },
    nuxtInject: inject
  })
}
