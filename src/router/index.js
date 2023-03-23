import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";
import requireAuthUser from '../services/requireAuthUser.js';
import requireAuthAdmin from '../services/requireAuthAdmin.js';
import logout from '../services/logout.js';


const routes = [
  {
    path: "/",
    name: "Home",
    beforeEnter: logout,
    component: HomeView,
  },
  {
    path: "/collection",
    name: "Collection",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/CollectionView.vue"),
  },
  {
    path: "/register",
    name: "Register/Login",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/RegisterLoginView.vue"),
  },
  {
    path: "/adminLogin",
    name: "Be Admin",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/BeAdminView.vue"),
  },
  {
    path: "/user",
    name: "History",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/UserView.vue"),
  },
  {
    path: "/user/issue",
    name: "Issue Book",
    beforeEnter: requireAuthUser,
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/UserBookView.vue"),
  },
  {
    path: "/user/profile",
    name: "Profile Info",
    beforeEnter: requireAuthUser,
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/UserProfileView.vue"),
  },
  {
    path: "/admin",
    name: "Dashboard",
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/AdminView.vue"),
  },
  {
    path: "/admin/categories",
    name: "Categories",
    beforeEnter: requireAuthAdmin,
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/AdminCategoriesView.vue"),
  },
  {
    path: "/admin/languages",
    name: "Languages",
    beforeEnter: requireAuthAdmin,
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/AdminLanguagesView.vue"),
  },
  {
    path: "/admin/authors",
    name: "Authors",
    beforeEnter: requireAuthAdmin,
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/AdminAuthorsView.vue"),
  },
  {
    path: "/admin/books",
    name: "Books",
    beforeEnter: requireAuthAdmin,
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/AdminBooksView.vue"),
  },
  {
    path: "/admin/users",
    name: "Users",
    beforeEnter: requireAuthAdmin,
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/AdminUsersView.vue"),
  },
  {
    path: "/admin/issues",
    name: "Issued Books",
    beforeEnter: requireAuthAdmin,
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () =>
      import(/* webpackChunkName: "about" */ "../views/AdminIssuesView.vue"),
  },
  {
    path: "/logout",
    name: "Log out",
    redirect: { name: 'Home' },
  }
];

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes,
});

export default router;
