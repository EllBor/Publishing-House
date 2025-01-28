<template>
  <v-container>
    <v-card>
      <v-card-text>
        <v-list>
          <v-list-item v-for="(value, key) in author" :key="key">
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
    ...mapState({ author: (state) => state.authors.authors[0] }),
  },
  methods: {
    ...mapActions({
      viewAuthorApi: "authors/viewAuthor",
    }),
  },

  created() {
    this.viewAuthorApi(this.$route.params.id);
  },
};
</script>
