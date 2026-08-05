import { createRouter, createWebHistory } from "vue-router";
import MainLayout from "../layouts/MainLayout.vue";
import AdminLayout from "../layouts/AdminLayout.vue";
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
        {
          path: "profile",
          name: "profile",
          component: () => import("../views/ProfileView.vue"),
          meta: { requiresAuth: true },
        },
        {
          path: "addresses",
          name: "addresses",
          component: () => import("../views/AddressBookView.vue"),
          meta: { requiresAuth: true },
        },
        {
          path: "wishlist",
          name: "wishlist",
          component: () => import("../views/WishlistView.vue"),
          meta: { requiresAuth: true },
        },
      ],
    },
    {
      path: "/admin",
      component: AdminLayout,
      meta: { requireAdmin: true },
      children: [
        {
          path: "",
          name: "admin-dashboard",
          component: () => import("../views/AdminDashboardView.vue"),
          meta: { title: "Dashboard" }
        },
        {
          path: "products",
          name: "admin-products",
          component: () => import("../views/AdminProductsView.vue"),
          meta: { title: "Products" }
        },
        {
          path: "orders",
          name: "admin-orders",
          component: () => import("../views/AdminOrdersView.vue"),
          meta: { title: "Orders" }
        }
      ]
    }
  ],
});

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  const isAuthenticated = authStore.isAuthenticated;
  const isAdmin = authStore.isAdmin;

  if (to.meta.guestOnly && isAuthenticated) {
    return next("/");
  }

  if (to.meta.requiresAuth && !isAuthenticated) {
    return next("/login");
  }

  if (to.meta.requireAdmin && (!isAuthenticated || !isAdmin)) {
    return next("/");
  }

  next();
});

export default router;
