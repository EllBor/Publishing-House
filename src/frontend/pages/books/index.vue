<template>
  <v-app>
    <v-container>
      <v-dialog v-model="dialog" max-width="600">
        <v-card>
          <v-card-title>
            {{ editingId ? "Редактировать сущность" : "Создать сущность" }}
          </v-card-title>
          <v-card-text>
            <v-form ref="form" v-model="valid">
              <v-text-field
                v-model="form.ISBN"
                label="ISBN"
                :error-messages="errors.ISBN"
                required
              ></v-text-field>
              <v-text-field
                v-model="form.nameBook"
                label="Название книги"
                :error-messages="errors.nameBook"
                required
              ></v-text-field>
              <v-text-field
                v-model="form.numberOfPages"
                label="Количество страниц"
                :error-messages="errors.numberOfPages"
                required
              ></v-text-field>
              <v-text-field
                v-model="form.dateOfPublication"
                label="Дата публикации"
                :error-messages="errors.dateOfPublication"
                required
              ></v-text-field>
              <v-select
                v-model="form.authors"
                :items="authorsList"
                label="Автор"
                multiple
                item-value="id"
                item-text="lastName"
                :error-messages="errors.authors"
              ></v-select>
              <v-select
                v-model="form.genres"
                :items="genresList"
                label="Жанр"
                multiple
                item-value="id"
                item-text="nameOfGenres"
                :error-messages="errors.genres"
              ></v-select>
            </v-form>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="blue darken-1" text @click="close">Отмена</v-btn>
            <v-btn color="blue darken-1" text @click="save">Сохранить</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>

      <v-btn
        color="primary"
        class="with-margin-bottom"
        @click="openDialog(null)"
        >Создать сущность</v-btn
      >

      <v-data-table
        :headers="headers"
        :items="books"
        :page.sync="page"
        :items-per-page.sync="perPage"
        show-select
        v-model="selectedEntities"
        class="with-margin-top"
        @click:row="redirectToEntityPage"
        :server-items-length="totalCount"
        :sort-desc.sync="sortDesc"
        :sort-by.sync="sortBy"
      >
        <template #[`item.actions`]="{ item }">
          <v-btn icon @click.stop="openDialog(item.id)">
            <v-icon>mdi-pencil</v-icon>
          </v-btn>
        </template>

        <template #top>
          <v-toolbar flat>
            <v-text-field
              append-icon="mdi-magnify"
              label="ISBN"
              single-line
              hide-details
              v-model="searchISBN"
              @keyup.enter="search"
            ></v-text-field>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-text-field
              append-icon="mdi-magnify"
              label="Название книги"
              single-line
              hide-details
              v-model="searchName"
              @keyup.enter="search"
            ></v-text-field>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-text-field
              append-icon="mdi-magnify"
              label="Количество страниц"
              single-line
              hide-details
              v-model="searchPages"
              @keyup.enter="search"
            ></v-text-field>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-text-field
              append-icon="mdi-magnify"
              label="Дата публикации"
              single-line
              hide-details
              v-model="searchDate"
              @keyup.enter="search"
            ></v-text-field>
          </v-toolbar>
        </template>
      </v-data-table>

      <v-btn color="red" @click="deleteSelectedBooks">Удалить выбранные</v-btn>
    </v-container>
  </v-app>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
  name: "Books",
  data() {
    return {
      dialog: false,
      searchISBN: "",
      searchName: "",
      searchPages: "",
      searchDate: "",
      perPage: 5,
      page: 1,
      sortBy: [],
      sortDesc: [],
      form: {
        ISBN: "",
        nameBook: "",
        numberOfPages: 0,
        dateOfPublication: "",
        authors: [],
        genres: [],
      },
      editingId: null,
      selectedEntities: [],
      valid: true,
      errors: {
        ISBN: [],
        nameBook: [],
        numberOfPages: [],
        dateOfPublication: [],
        authors: [],
        genres: [],
      },
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
          text: "Редактировать",
          value: "actions",
        },
      ],
    };
  },
  computed: {
    ...mapState({
      books: (state) => state.books.books,
      authorsList: (state) => state.books.authorsList,
      genresList: (state) => state.books.genresList,
      totalCount: (state) => state.books.totalCount,
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
  methods: {
    ...mapActions({
      fetchBooksApi: "books/fetchBooks",
      createBookApi: "books/createBook",
      updateBookApi: "books/updateBook",
      deleteBookApi: "books/deleteBook",
      fetchAuthorsList: "books/fetchAuthorsList",
      fetchGenresList: "books/fetchGenresList",
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
      await this.fetchBooksApi(queryParams);
    },
    async search() {
      try {
        const queryParams = {};
        queryParams.selectedISBN = this.searchISBN.trim();
        queryParams.selectedNameBook = this.searchName.trim();
        queryParams.selectedNumberOfPages = this.searchPages.trim();
        queryParams.selectedDateOfPublication = this.searchDate.trim();
        await this.fetchBooksApi(queryParams);
      } catch (error) {
        console.error("Ошибка при выполнении поиска:", error);
      }
    },
    async openDialog(entityId) {
      try {
        await Promise.all([this.fetchAuthorsList(), this.fetchGenresList()]);
        this.editingId = entityId;
        if (!entityId) {
          this.resetForm();
        } else {
          const entity = this.books.find((book) => book.id === entityId);
          if (entity) {
            this.form = { ...entity };
          } else {
            console.error("Сущность не найдена.");
          }
        }
        this.dialog = true;
      } catch (error) {
        console.error("Ошибка при открытии диалогового окна:", error);
      }
    },
    close() {
      this.dialog = false;
    },
    async save() {
      const params = new URLSearchParams();
      Object.entries(this.form).forEach(([key, value]) => {
        if (key !== "id") {
          params.append(key, value);
        }
      });
      if (this.editingId) {
        await this.updateBookApi({
          bookId: this.editingId,
          updatedBookData: params,
        });
      } else {
        await this.createBookApi(params);
      }
      this.close();
      this.fetchBooksApi();
    },

    resetForm() {
      this.form.ISBN = "",
      this.form.nameBook = "",
      this.form.numberOfPages = 0,
      this.form.dateOfPublication = "",
      this.form.authors = [],
      this.form.genres = [],
      this.editingId = null;
    },
    deleteSelectedBooks() {
      this.selectedEntities.forEach((book) => {
        this.deleteBookApi(book.id.toString());
      });
    },
    redirectToEntityPage(selectedItem) {
      if (selectedItem.id) {
        const entityId = selectedItem.id;
        this.$router.push({ path: `/books/${entityId}` });
      }
    },
  },
};
</script>
