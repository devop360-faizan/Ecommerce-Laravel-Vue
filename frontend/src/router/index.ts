import { createRouter, createWebHistory } from "vue-router";
import MainLayout from "../layouts/MainLayout.vue";
import { useAuthStore } from "../stores/auth";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      component: MainLayout,
      children: [
        {
          path: "login",
          name: "login",
          component: () => import("../views/LoginView.vue"),
          meta: { guestOnly: true },
        },
        {
          path: "register",
          name: "register",
          component: () => import("../views/RegisterView.vue"),
          meta: { guestOnly: true },
        },
        {
          path: "",
          name: "home",
          component: () => import("../views/HomeView.vue"),
        },
        {
          path: "shop",
          name: "shop",
          component: () => import("../views/ShopView.vue"),
        },
        {
          path: "product/:slug",
          name: "product-detail",
          component: () => import("../views/ProductDetailView.vue"),
        },
        {
          path: "cart",
          name: "cart",
          component: () => import("../views/CartView.vue"),
        },
      ],
    },
  ],
});

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  const isAuthenticated = authStore.isAuthenticated;

  if (to.meta.guestOnly && isAuthenticated) {
    return next("/");
  }

  if (to.meta.requiresAuth && !isAuthenticated) {
    return next("/login");
  }

  next();
});

export default router;
