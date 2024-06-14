const state = {
  user: {
    token: sessionStorage.getItem("TOKEN"),
    data: {},
  },
  products: {
    laoding: false,
    data: [],
  },
};

export default state;
