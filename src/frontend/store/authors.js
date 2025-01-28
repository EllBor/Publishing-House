export const state = () => ({
  authors: [],
  totalCount: 0,
});

export const getters = {
  allAuthors: (state) => state.authors,
};

export const actions = {
  async fetchAuthors({ commit }, authorData) {
    try {
      const response = await this.$axios.get("/authors", {
        params: authorData,
      });
      const totalCount = response.headers["x-pagination-total-count"];
      commit("setAuthors", response.data);
      commit("setTotalCount", parseInt(totalCount));
    } catch (error) {
      console.error("Ошибка при получении авторов:", error);
      throw error;
    }
  },
  async createAuthor({ dispatch }, authorData) {
    try {
      await this.$axios.$post("/authors/create", authorData);
      dispatch("fetchAuthors");
    } catch (error) {
      console.error("Ошибка при создании автора:", error);
      throw error;
    }
  },
  async viewAuthor({ commit }, authorId) {
    try {
      const response = await this.$axios.$get(`/authors/view?id=${authorId}`);
      commit("setAuthors", [response]);
    } catch (error) {
      console.error("Ошибка при получении автора:", error);
      throw error;
    }
  },
  async updateAuthor({ dispatch }, { authorId, updatedAuthorData }) {
    try {
      await this.$axios.$put(
        `/authors/update?id=${authorId}`,
        updatedAuthorData
      );
      dispatch("fetchAuthors");
    } catch (error) {
      console.error("Ошибка при обновлении автора:", error);
      throw error;
    }
  },
  async deleteAuthor({ dispatch }, authorId) {
    try {
      await this.$axios.$delete(`/authors/delete?id=${authorId}`);
      dispatch("fetchAuthors");
    } catch (error) {
      console.error("Ошибка при удалении автора:", error);
      throw error;
    }
  },
};

export const mutations = {
  setAuthors: (state, authors) => {
    state.authors = authors;
  },
  setTotalCount(state, count) {
    state.totalCount = count;
  },
};
