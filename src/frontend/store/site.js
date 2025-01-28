export const state = () => ({
  main: [],
  totalCount: 0,
});

export const getters = {
  allMain: (state) => state.main,
};

export const actions = {
  async fetchMain({ commit }, filterData) {
    try {
      const response = await this.$axios.get("/site", { params: filterData });
      const totalCount = response.headers["x-pagination-total-count"];
      commit("setMain", response.data);
      commit("setTotalCount", parseInt(totalCount));
    } catch (error) {
      console.error("Ошибка при получении:", error);
    }
  },
};

export const mutations = {
  setMain: (state, main) => {
    state.main = main;
  },
  setTotalCount(state, count) {
    state.totalCount = count;
  },
};
