import axiosClient from "../axios";

export function getUser({ commit }, data) {
  return axiosClient.get("/user", data).then(({ data }) => {
    commit("setUser", data);

    return data;
  });
}

export function login({ commit }, data) {
  return axiosClient.post("/login", data).then(({ data }) => {
    commit("setUser", data.user);
    commit("setToken", data.token);
    return data;
  });
}

export function logout({ commit }) {
  return axiosClient.post("/logout").then((response) => {
    commit("setToken", null);

    return response;
  });
}

export function getProducts(
  { commit },
  { url = null, sort_field, sort_direction, search = "", perPage = 10 } = {}
) {
  commit("setProducts", [true]);
  url = url || "/products";
  return axiosClient
    .get(url, {
      params: {
        search,
        sort_field,
        sort_direction,
        per_page: perPage,
      },
    })
    .then((res) => {
      commit("setProducts", [false, res.data]);
    })
    .catch(() => {
      commit("setProducts", [false]);
    });
}

export function getArchivedProducts(
  { commit },
  { url = null, sort_field, sort_direction, search = "", perPage = 10 } = {}
) {
  commit("setProducts", [true]);
  url = url || "/products/archived";
  return axiosClient
    .get(url, {
      params: {
        search,
        sort_field,
        sort_direction,
        per_page: perPage,
      },
    })
    .then((res) => {
      commit("setProducts", [false, res.data]);
    })
    .catch(() => {
      commit("setProducts", [false]);
    });
}

export function getProduct({}, id) {
  return axiosClient.get(`products/${id}`);
}

export function createProduct({ commit }, product) {
  if (product.image instanceof File) {
    const form = new FormData();
    form.append("title", product.title);
    form.append("image", product.image);
    form.append("description", product.description);
    form.append("price", product.price);
    form.append("stock", product.stock);
    form.append("status", product.status);

    product = form;
  }
  return axiosClient.post("/products", product);
}

export function updateProduct({ commit }, product) {
  const id = product.id;
  if (product.image instanceof File) {
    const form = new FormData();
    form.append("title", product.title);
    form.append("image", product.image);
    form.append("description", product.description);
    form.append("price", product.price);
    form.append("stock", product.stock);
    form.append("status", product.status);
    form.append("_method", "PUT");

    product = form;
  } else {
    product._method = "PUT";
  }

  return axiosClient.post(`/products/${id}`, product);
}

export function deleteProduct({ commit }, id) {
  return axiosClient.delete(`/products/${id}`);
}

export function getOrders(
  { commit },
  { url = null, sort_field, sort_direction, search = "", perPage = 10 } = {}
) {
  commit("setOrders", [true]);
  url = url || "/orders";
  return axiosClient
    .get(url, {
      params: {
        search,
        sort_field,
        sort_direction,
        per_page: perPage,
      },
    })
    .then((res) => {
      commit("setOrders", [false, res.data]);
    })
    .catch(() => {
      commit("setOrders", [false]);
    });
}

export function getOrder({}, id) {
  return axiosClient.get(`/orders/${id}`);
}

export function getUsers(
  { commit },
  { url = null, sort_field, sort_direction, search = "", perPage = 10 } = {}
) {
  commit("setUsers", [true]);
  url = url || "/users";
  return axiosClient
    .get(url, {
      params: {
        search,
        sort_field,
        sort_direction,
        per_page: perPage,
      },
    })
    .then((res) => {
      commit("setUsers", [false, res.data]);
    })
    .catch(() => {
      commit("setUsers", [false]);
    });
}

export function getUserById({}, id) {
  return axiosClient.get(`/users/${id}`);
}

export function createUser({ commit }, user) {
  return axiosClient.post("/users", user);
}

export function updateUser({ commit }, user) {
  return axiosClient.put(`/users/${user.id}`, user);
}

export function deleteUser({ commit }, id) {
  return axiosClient.delete(`/users/${id}`);
}

export function getCustomers(
  { commit },
  { url = null, sort_field, sort_direction, search = "", perPage = 10 } = {}
) {
  commit("setCustomers", [true]);
  url = url || "/customers";
  return axiosClient
    .get(url, {
      params: {
        search,
        sort_field,
        sort_direction,
        per_page: perPage,
      },
    })
    .then((res) => {
      commit("setCustomers", [false, res.data]);
    })
    .catch(() => {
      commit("setCustomers", [false]);
    });
}

export function getCustomer({}, id) {
  return axiosClient.get(`/customers/${id}`);
}

export function updateCustomer({ commit }, customer) {
  return axiosClient.put(`/customers/${customer.id}`, customer);
}

export function deleteCustomer({ commit }, id) {
  return axiosClient.delete(`/customers/${id}`);
}
