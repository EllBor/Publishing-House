export const state = () => ({
  genres: [],
  totalCount: 0,
});

export const getters = {
  allGenres: (state) => state.genres,
};

export const actions = {
  async fetchGenre({ commit }, genreData) {
    try {
      const response = await this.$axios.get("/genres", { params: genreData });
      const totalCount = response.headers["x-pagination-total-count"];
      commit("setGenres", response.data);
      commit("setTotalCount", parseInt(totalCount));
    } catch (error) {
      console.error("Ошибка при получении жанров:", error);
      throw error;
    }
  },
  async createGenre({ dispatch }, genreData) {
    try {
      await this.$axios.$post("/genres/create", genreData);
      dispatch("fetchGenre");
    } catch (error) {
      console.error("Ошибка при создании жанра:", error);
      throw error;
    }
  },
  async viewGenre({ commit }, genreId) {
    try {
      const response = await this.$axios.$get(`/genres/view?id=${genreId}`);
      commit("setGenres", [response]);
    } catch (error) {
      console.error("Ошибка при получении жанра:", error);
      throw error;
    }
  },
  async updateGenre({ dispatch }, { genreId, updatedGenreData }) {
    try {
      await this.$axios.$put(`/genres/update?id=${genreId}`, updatedGenreData);
      dispatch("fetchGenre");
    } catch (error) {
      console.error("Ошибка при обновлении жанра:", error);
      throw error;
    }
  },
  async deleteGenre({ dispatch }, genreId) {
    try {
      await this.$axios.$delete(`/genres/delete?id=${genreId}`);
      dispatch("fetchGenre");
    } catch (error) {
      console.error("Ошибка при удалении жанра:", error);
      throw error;
    }
  },
};

export const mutations = {
  setGenres: (state, genres) => (state.genres = genres),
  setTotalCount(state, count) {
    state.totalCount = count;
  },
};
