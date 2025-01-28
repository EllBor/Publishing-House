<template>
  <v-container fluid>
    <v-row justify="center">
      <v-col cols="12" sm="8" md="4">
        <v-card class="elevation-12">
          <v-toolbar dark color="primary">
            <v-toolbar-title>Регистрация</v-toolbar-title>
          </v-toolbar>

          <v-card-text>
            <v-form @submit.prevent="register">
              <v-text-field
                v-model="registration.username"
                label="Логин"
                required
              ></v-text-field>
              <v-text-field
                v-model="registration.password"
                label="Пароль"
                type="password"
                required
              ></v-text-field>
              <v-btn type="submit" color="primary" block
                >Зарегистрироваться</v-btn
              >
              <nuxt-link to="/login">Назад</nuxt-link>
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions } from "vuex";

export default {
  auth: "guest",
  data() {
    return {
      registration: {
        username: "",
        password: "",
      },
    };
  },
  methods: {
    ...mapActions({
      RegisterApi: "auth/registerUser",
    }),

    async register() {
      try {
        const params = new URLSearchParams();
        Object.entries(this.registration).forEach(([key, value]) => {
          params.append(key, value);
        });

        await this.RegisterApi(params);
        this.$router.push("/");
      } catch (error) {
        console.error("Ошибка при регистрации:", error);
      }
    },
  },
};
</script>
