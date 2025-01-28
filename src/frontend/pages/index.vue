<template>
  <v-container>
    <v-col cols="12">
      <BooksFilter />
    </v-col>
    <v-row>
      <v-col cols="6">
        <TopGenres />
      </v-col>
      <v-col cols="6">
        <TopAuthors />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import BooksFilter from "@/components/BooksFilter";
import TopGenres from "@/components/TopGenres";
import TopAuthors from "@/components/TopAuthors";

export default {
  components: {
    TopGenres,
    TopAuthors,
    BooksFilter,
  },
};
</script>
