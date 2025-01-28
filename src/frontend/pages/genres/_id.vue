<template>
  <v-container>
    <v-card>
      <v-card-text>
        <v-list>
          <v-list-item v-for="(value, key) in genre" :key="key">
            <v-list-item-title>{{ key }}: {{ value }}</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-card-text>
    </v-card>
  </v-container>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
  computed: {
    ...mapState({ genre: (state) => state.genres.genres[0] }),
  },
  methods: {
    ...mapActions({
      viewGenreApi: "genres/viewGenre",
    }),
  },

  created() {
    this.viewGenreApi(this.$route.params.id);
  },
};
</script>
