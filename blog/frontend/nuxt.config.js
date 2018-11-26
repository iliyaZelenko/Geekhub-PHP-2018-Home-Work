import i18nMessages from './src/i18n/messages.js'
// import { join } from 'path'

const SRC_DIR = 'src'
const BASE_URL = process.env.BASE_URL || 'http://localhost:3000'
const BASE_API_URL = 'http://blog-symfony.test' // 'http://localhost:8000'
const DEFAULT_LOCALE = 'ru'
const FALLBACK_LOCALE = 'en'
const LOCALES = [
  {
    code: 'en',
    iso: 'en-US',
    name: 'English',
    flag: 'us'
  },
  {
    code: 'ru',
    iso: 'ru-RU',
    name: 'Русский',
    flag: 'ru'
  }
]

module.exports = {
  mode: 'universal',

  env: {
    BASE_API_URL,
    LOCALES,
    BASE_URL,
    FULL_API_URL: BASE_API_URL // + '/api/'
  },

  /*
  ** Set source directory
  */
  srcDir: SRC_DIR,

  css: [
    { src: '~styles/main.styl', lang: 'stylus' }
    // '~assets/css/main.css'
  ],

  plugins: [
    '~/plugins/vuetify.js',
    '~/plugins/vue-plugin-axios/vue-plugin-axios.js',
    // '~/plugins/vuex-persistedstate.js',
    '~/plugins/auth.js',
    '~/plugins/actionWithLoading',
    { src: '~/plugins/notifications', ssr: false },
    '~/plugins/validator'
  ],

  modules: [
    '~/modules/typescript.js',
    '@nuxtjs/style-resources',
    'cookie-universal-nuxt',
    ['nuxt-i18n', {
      parsePages: false, // отключает acorn
      locales: LOCALES,
      defaultLocale: DEFAULT_LOCALE,
      baseUrl: BASE_URL,
      strategy: 'prefix_except_default',
      vueI18n: {
        fallbackLocale: FALLBACK_LOCALE,
        messages: i18nMessages
      }
    }]
  ],

  styleResources: {
    stylus: '~/styles/global/*.styl'
    // options: {
    //   // See https://github.com/yenshih/style-resources-loader#options
    //   // Except `patterns` property
    // }
  },

  /*
  ** Headers of the page
  */
  head: {
    title: 'frontend',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: 'Nuxt.js project' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      {
        rel: 'stylesheet',
        href: 'https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons'
      }
    ]
  },

  /*
  ** Customize the progress bar color
  */
  loading: { color: '#3B8070' },

  /*
  ** Build configuration
  */
  build: {
    babel: {
      plugins: [
        // в babel 7 используется этот пакет, a не "transform-runtime"
        // '@babel/plugin-transform-runtime'
      ],
      presets ({ isServer }) {
        return [
          ['@nuxtjs/babel-preset-app', {
            useBuiltIns: 'usage', //  | 'entry' | false
            modules: false,
            targets:
              // isServer
              // ? { node: '9.0.0' }
              // :
              { browsers: ['defaults'] }
          }],
          ['@babel/preset-typescript']
        ]
      }
    },

    /*
    ** Run ESLint on save
    */
    extend (config, { isDev, isClient }) {
      // config.resolve.alias['@'] = join(__dirname, SRC_DIR)
      // config.resolve.alias['@'] = SRC_DIR
      // config.resolve.extensions.push('.ts')

      if (isDev && isClient) {
        config.module.rules.push({
          enforce: 'pre',
          test: /\.(js|vue)$/,
          loader: 'eslint-loader',
          exclude: /(node_modules)/
        }
        )
      }
    }
  }
}
