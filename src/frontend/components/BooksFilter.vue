<template>
  <v-app>
    <v-container>
      <v-data-table
        :headers="headers"
        :items="booksInfo.dataProvider"
        :page.sync="page"
        :items-per-page.sync="perPage"
        :server-items-length="totalCount"
        :sort-desc.sync="sortDesc"
        :sort-by.sync="sortBy"
        class="with-margin-top"
      >
        <template #top>
          <v-toolbar flat>
            <v-text-field
              append-icon="mdi-magnify"
              label="Автор"
              single-line
              hide-details
              v-model="searchAuthors"
              @keyup.enter="search"
            ></v-text-field>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-text-field
              append-icon="mdi-magnify"
              label="Жанр"
              single-line
              hide-details
              v-model="searchGenres"
              @keyup.enter="search"
            ></v-text-field>
          </v-toolbar>
        </template>
      </v-data-table>
    </v-container>
  </v-app>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
  data() {
    return {
      perPage: 5,
      page: 1,
      searchAuthors: "",
      searchGenres: "",
      sortBy: [],
      sortDesc: [],
      headers: [
        {
          text: "ISBN",
          value: "ISBN",
        },
        {
          text: "Название книги",
          value: "nameBook",
        },
        {
          text: "Количество страниц",
          value: "numberOfPages",
        },
        {
          text: "Дата публикации",
          value: "dateOfPublication",
        },
        {
          text: "Авторы",
          value: "authorNames",
        },
        {
          text: "Жанры",
          value: "genreNames",
        },
      ],
    };
  },
  computed: {
    ...mapState({
      booksInfo: (state) => state.site.main,
      totalCount: (state) => state.site.totalCount,
    }),
  },
  watch: {
    page: "fetchData",
    perPage: "fetchData",
    sortBy: "fetchData",
    sortDesc: "fetchData",
  },
  created() {
    this.fetchData();
  },
  mounted() {
    this.fetchMainApi();
  },
  methods: {
    ...mapActions({
      fetchMainApi: "site/fetchMain",
    }),
    async fetchData() {
      const field = this.sortBy[0];
      const desc = this.sortDesc[0];
      const sortParam = desc ? `-${field}` : field;
      const queryParams = {
        page: this.page - 1,
        per_page: this.perPage,
        sort: sortParam,
      };
      await this.fetchMainApi(queryParams);
    },
    async search() {
      try {
        const queryParams = {};
        queryParams.selectedAuthor = this.searchAuthors.trim();
        queryParams.selectedGenre = this.searchGenres.trim();
        await this.fetchMainApi(queryParams);
      } catch (error) {
        console.error("Ошибка при выполнении поиска:", error);
      }
    },

    async serverItems({ page, perPage, sortBy, sortDesc }) {
      try {
        const ordering = sortDesc[0] ? `-${sortBy[0]}` : sortBy[0];
        const queryParams = {
          page: page,
          per_page: perPage,
          sort: ordering,
        };
        await this.fetchMainApi(queryParams);
      } catch (error) {
        console.error("Ошибка при загрузке данных:", error);
      }
    },

    updateSortBy(sortBy) {
      this.sortBy = sortBy;
      this.serverItems({
        page: this.page,
        perPage: this.perPage,
        sortBy: this.sortBy,
        sortDesc: this.sortDesc,
      });
    },

    updateSortDesc(sortDesc) {
      this.sortDesc = sortDesc;
      this.serverItems({
        page: this.page,
        perPage: this.perPage,
        sortBy: this.sortBy,
        sortDesc: this.sortDesc,
      });
    },
  },
};
</script>

<style>
.v-application--wrap {
  min-height: 40vh;
}
</style>
