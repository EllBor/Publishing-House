<template>
  <v-container>
    <v-card>
      <v-card-text>
        <v-list>
          <v-list-item v-for="(value, key) in book" :key="key">
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
    ...mapState({ book: (state) => state.books.books[0] }),
  },
  methods: {
    ...mapActions({
      viewBookApi: "books/viewBook",
    }),
  },

  created() {
    this.viewBookApi(this.$route.params.id);
  },
};
</script>
