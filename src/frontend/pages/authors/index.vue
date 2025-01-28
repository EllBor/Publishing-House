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
                v-model="form.firstName"
                label="Имя"
                :error-messages="errors.firstName"
                required
              ></v-text-field>
              <v-text-field
                v-model="form.lastName"
                label="Фамилия"
                :error-messages="errors.lastName"
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
        :items="authors"
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
              label="Имя"
              single-line
              hide-details
              v-model="searchFirstName"
              @keyup.enter="search"
            ></v-text-field>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-text-field
              append-icon="mdi-magnify"
              label="Фамилия"
              single-line
              hide-details
              v-model="searchLastName"
              @keyup.enter="search"
            ></v-text-field>
          </v-toolbar>
        </template>
      </v-data-table>

      <v-btn color="red" @click="deleteSelectedAuthors"
        >Удалить выбранные</v-btn
      >
    </v-container>
  </v-app>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
  name: "authors",
  data() {
    return {
      dialog: false,
      searchFirstName: "",
      searchLastName: "",
      perPage: 5,
      page: 1,
      sortBy: [],
      sortDesc: [],
      form: {
        firstName: "",
        lastName: "",
      },
      editingId: null,
      selectedEntities: [],
      valid: true,
      errors: {
        firstName: [],
        lastName: [],
      },
      headers: [
        {
          text: "Имя",
          value: "firstName",
        },
        {
          text: "Фамилия",
          value: "lastName",
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
      authors: (state) => state.authors.authors,
      totalCount: (state) => state.authors.totalCount,
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
      fetchAuthorsApi: "authors/fetchAuthors",
      createAuthorApi: "authors/createAuthor",
      updateAuthorApi: "authors/updateAuthor",
      deleteAuthorApi: "authors/deleteAuthor",
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
      await this.fetchAuthorsApi(queryParams);
    },
    async search() {
      try {
        const queryParams = {};
        queryParams.selectedFirst = this.searchFirstName.trim();
        queryParams.selectedLast = this.searchLastName.trim();
        await this.fetchAuthorsApi(queryParams);
      } catch (error) {
        console.error("Ошибка при выполнении поиска:", error);
      }
    },
    openDialog(entityId) {
      this.editingId = entityId;
      if (!entityId) {
        this.resetForm();
      } else {
        const entity = this.authors.find((author) => author.id === entityId);
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
        await this.updateAuthorApi({
          authorId: this.editingId,
          updatedAuthorData: params,
        });
      } else {
        await this.createAuthorApi(params);
      }
      this.close();
      this.fetchAuthorsApi();
    },
    resetForm() {
      this.form.firstName = "";
      this.form.lastName = "";
      this.editingId = null;
    },
    deleteSelectedAuthors() {
      this.selectedEntities.forEach((author) => {
        this.deleteAuthorApi(author.id.toString());
      });
    },

    redirectToEntityPage(selectedItem) {
      console.log("view");
      if (selectedItem.id) {
        const entityId = selectedItem.id;
        this.$router.push({ path: `/authors/${entityId}` });
      }
    },
  },
};
</script>
