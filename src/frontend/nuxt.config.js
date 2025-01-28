export default {
  // Global page headers: https://go.nuxtjs.dev/config-head
  head: {
    title: 'Publishing House',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' }
    ],
  },

  axios: {
    baseURL: "http://localhost:8000",
  },

  router: {
    middleware: ['auth'],
  },

  auth: {
    strategies: {
      local: {
        token: {
          property: 'token',
          global: true,
          required: true,
          type: 'Bearer'
        },
        refreshToken: {
          property: 'refresh_token',
          data: 'refresh_token',
          maxAge: 60 * 60 * 24 * 30
        },
        user: {
          property: false
        },
        endpoints: {
          login: { url: '/auth/login', method: 'post'},
          register: { url: '/auth/register', method: 'post' },
          refresh: { url: '/auth/refresh', method: 'post'},
          user: {url: '/auth/user', method: 'get'},
          logout: {url: '/auth/logout', method: 'get'},
        }
      }
    },
    redirect: {
      login: '/login',
      logout: '/login',
      register: '/register',
      home: '/'
    },
  },

  css: [
    './assets/styles.css'
  ],

  plugins: [
  ],

  components: true,

  buildModules: [
    '@nuxtjs/vuetify',
  ],

  modules: [
   '@nuxtjs/axios',
   '@nuxtjs/auth-next'
  ],

  build: {
  }
}
