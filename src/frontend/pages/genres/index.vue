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
                v-model="form.nameOfGenres"
                label="Жанр"
                :error-messages="errors.nameOfGenres"
                required
              ></v-text-field>
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
        :items="genres"
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
              label="Жанр"
              single-line
              hide-details
              v-model="searchGenres"
              @keyup.enter="search"
            ></v-text-field>
          </v-toolbar>
        </template>
      </v-data-table>

      <v-btn color="red" @click="deleteSelectedGenres">Удалить выбранные</v-btn>
    </v-container>
  </v-app>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
  name: "genres",
  data() {
    return {
      dialog: false,
      searchGenres: "",
      perPage: 5,
      page: 1,
      sortBy: [],
      sortDesc: [],
      form: {
        nameOfGenres: "",
      },
      editingId: null,
      selectedEntities: [],
      valid: true,
      errors: {
        nameOfGenres: [],
      },
      headers: [
        {
          text: "Жанр",
          value: "nameOfGenres",
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
      genres: (state) => state.genres.genres,
      totalCount: (state) => state.genres.totalCount,
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
      fetchGenresApi: "genres/fetchGenre",
      createGenreApi: "genres/createGenre",
      updateGenreApi: "genres/updateGenre",
      deleteGenreApi: "genres/deleteGenre",
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
      await this.fetchGenresApi(queryParams);
    },
    async search() {
      try {
        const queryParams = {};
        queryParams.selectedNameOfGenres = this.searchGenres.trim();
        await this.fetchGenresApi(queryParams);
      } catch (error) {
        console.error("Ошибка при выполнении поиска:", error);
      }
    },
    openDialog(entityId) {
      this.editingId = entityId;
      if (!entityId) {
        this.resetForm();
      } else {
        const entity = this.genres.find((genre) => genre.id === entityId);
        if (entity) {
          this.form = { ...entity };
        } else {
          console.error("Сущность не найдена.");
        }
      }
      this.dialog = true;
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
        await this.updateGenreApi({
          genreId: this.editingId,
          updatedGenreData: params,
        });
      } else {
        await this.createGenreApi(params);
      }
      this.close();
      this.fetchGenresApi();
    },
    resetForm() {
      this.form.nameOfGenres = "";
      this.editingId = null;
    },
    deleteSelectedGenres() {
      this.selectedEntities.forEach((genre) => {
        this.deleteGenreApi(genre.id.toString());
      });
    },
    redirectToEntityPage(selectedItem) {
      if (selectedItem.id) {
        const entityId = selectedItem.id;
        this.$router.push({ path: `/genres/${entityId}` });
      }
    },
  },
};
</script>
