<template>
  <v-app>
    <v-card class="mx-auto ma-4">
      <v-card-title>Топ-3 Жанров</v-card-title>
      <v-list>
        <v-list-item
          v-for="genre in Genres.topGenres"
          :key="genre.id"
          :value="genre.id"
        >
          <v-list-item-content>
            <v-list-item-title>
              {{ genre.nameOfGenres }}
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
    ...mapState({ Genres: (state) => state.site.main }),
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
