export const state = () => ({
  loggedIn: false,
  token: null,
});

export const mutations = {
  setLoggedIn(state, value) {
    state.loggedIn = value;
  },
  setToken(state, token) {
    state.token = token;
  },
};

export const actions = {
  async registerUser({ commit }, params) {
    try {
      const response = await this.$axios.$post("/auth/register", params);
      localStorage.setItem("token", response.token);
      commit("setLoggedIn", true);
    } catch (error) {
      console.error("Ошибка при регистрации:", error);
      throw error;
    }
  },
};
