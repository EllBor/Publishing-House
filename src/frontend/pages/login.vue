<template>
  <v-container fluid>
    <v-row justify="center">
      <v-col cols="12" sm="8" md="4">
        <v-card class="elevation-12">
          <v-toolbar dark color="primary">
            <v-toolbar-title>Авторизация</v-toolbar-title>
          </v-toolbar>

          <v-card-text>
            <v-form @submit.prevent="userLogin">
              <v-text-field
                v-model="login.username"
                label="Логин"
                required
              ></v-text-field>
              <v-text-field
                v-model="login.password"
                label="Пароль"
                type="password"
                required
              ></v-text-field>
              <v-btn type="submit" color="primary" block>Войти</v-btn>
              <nuxt-link to="/register">Зарегистрироваться</nuxt-link>
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>

export default {
  data() {
    return {
      login: {
        username: "",
        password: "",
      },
    };
  },
  methods: {
    async userLogin() {
      try {
        const params = new URLSearchParams();
        Object.entries(this.login).forEach(([key, value]) => {
          params.append(key, value);
        });
        await this.$auth.loginWith("local", {
          data: params,
        });
        this.$router.push("/");
      } catch (err) {
        console.error("Ошибка аутентификации:", err);
        alert("Неверное имя пользователя или пароль");
      }
    },
  },
};
</script>
