<template>
  <v-app>
    <v-card class="mx-auto ma-4">
      <v-card-title>Топ-3 Авторов</v-card-title>
      <v-list>
        <v-list-item
          v-for="author in Authors.topAuthors"
          :key="author.id"
          :value="author.id"
        >
          <v-list-item-content>
            <v-list-item-title>
              {{ author.fullName }}
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-card>
  </v-app>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
  computed: {
    ...mapState({ Authors: (state) => state.site.main }),
  },
  mounted() {
    this.fetchMainApi();
  },
  methods: {
    ...mapActions({
      fetchMainApi: "site/fetchMain",
    }),
  },
};
</script>
