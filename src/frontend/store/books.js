export const state = () => ({
  books: [],
  authorsList: [],
  genresList: [],
  totalCount: 0,
});

export const getters = {
  allBooks: (state) => state.books,
};

export const actions = {
  async fetchBooks({ commit }, bookData) {
    try {
      const response = await this.$axios.get("/books", { params: bookData });
      const totalCount = response.headers["x-pagination-total-count"];
      commit("setBooks", response.data);
      commit("setTotalCount", parseInt(totalCount));
    } catch (error) {
      console.error("Ошибка при получении книг:", error);
      throw error;
    }
  },
  async viewBook({ commit }, bookId) {
    try {
      const response = await this.$axios.$get(`/books/view?id=${bookId}`);
      commit("setBooks", [response]);
    } catch (error) {
      console.error("Ошибка при получении книги:", error);
      throw error;
    }
  },
  async createBook({ dispatch }, bookData) {
    try {
      await this.$axios.$post("/books/create", bookData);
      dispatch("fetchBooks");
    } catch (error) {
      console.error("Ошибка при создании книги:", error);
      throw error;
    }
  },
  async updateBook({ dispatch }, { bookId, updatedBookData }) {
    try {
      await this.$axios.$put(`/books/update?id=${bookId}`, updatedBookData);
      dispatch("fetchBooks");
    } catch (error) {
      console.error("Ошибка при обновлении книги:", error);
      throw error;
    }
  },
  async deleteBook({ dispatch }, bookId) {
    try {
      await this.$axios.$delete(`/books/delete?id=${bookId}`);
      dispatch("fetchBooks");
    } catch (error) {
      console.error("Ошибка при удалении книги:", error);
      throw error;
    }
  },
  async fetchAuthorsList({ commit }) {
    try {
      const response = await this.$axios.$get("/authors");
      commit("setAuthorsList", response);
    } catch (error) {
      console.error("Ошибка при получении списка авторов:", error);
      throw error;
    }
  },
  async fetchGenresList({ commit }) {
    try {
      const response = await this.$axios.$get("/genres");
      commit("setGenresList", response);
    } catch (error) {
      console.error("Ошибка при получении списка жанров:", error);
      throw error;
    }
  },
};

export const mutations = {
  setBooks: (state, books) => (state.books = books),
  setAuthorsList: (state, authorsList) => (state.authorsList = authorsList),
  setGenresList: (state, genresList) => (state.genresList = genresList),
  setTotalCount(state, count) {
    state.totalCount = count;
  },
};
