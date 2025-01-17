const state = {
  user: {
    token: sessionStorage.getItem("TOKEN"),
    data: {},
  },
  products: {
    laoding: true,
    data: [],
    links: [],
    from: null,
    to: null,
    page: 1,
    limit: null,
    total: null,
  },
  orders: {
    laoding: false,
    data: [],
    links: [],
    from: null,
    to: null,
    page: 1,
    limit: null,
    total: null,
  },
  users: {
    laoding: false,
    data: [],
    links: [],
    from: null,
    to: null,
    page: 1,
    limit: null,
    total: null,
  },
};

export default state;
