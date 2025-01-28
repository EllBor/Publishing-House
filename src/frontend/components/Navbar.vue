<template>
  <v-container fluid>
    <v-app-bar color="#1E90FF" dark>
      <nuxt-link to="/">
        <v-btn outlined class="ml-10"> Главная </v-btn>
      </nuxt-link>

      <nuxt-link to="/books">
        <v-btn outlined class="ml-10"> Книги </v-btn>
      </nuxt-link>

      <nuxt-link to="/authors">
        <v-btn outlined class="ml-10"> Авторы </v-btn>
      </nuxt-link>

      <nuxt-link to="/genres">
        <v-btn outlined class="ml-10"> Жанры </v-btn>
      </nuxt-link>
      <v-btn outlined class="ml-10" @click="logout"> Выход </v-btn>
    </v-app-bar>
  </v-container>
</template>

<script>
export default {
  methods: {
    async logout() {
      try {
        await this.$auth.logout();
        this.$router.push('/login'); 
      } catch (error) {
        console.error('Ошибка при выходе из аккаунта:', error);
      }
    }
  }
}
</script>
