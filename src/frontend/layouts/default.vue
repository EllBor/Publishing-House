<template>
  <v-app>
    <Navbar v-if="isAuthenticated" />
    <main>
      <v-container fluid>
        <Nuxt />
      </v-container>
    </main>
  </v-app>
</template>

<script>
import Navbar from '@/components/Navbar.vue';
export default {
  components: {
    Navbar
  },
  computed: {
    isAuthenticated() {
      return this.$auth.loggedIn;
    }
  }
}
</script>
