import { createRouter, createWebHistory } from "vue-router";
import DashboardView from "@/views/DashboardView.vue";
import { useAuthStore } from "@/stores/auth";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "dashboard",
      component: DashboardView
    },
    {
      path: "/packages/track",
      name: "Track new package",
      component: () => import("@/views/TrackNewPackageView.vue")
    },
    {
      path: "/packages/:id",
      name: "packages",
      component: () => import("@/views/PackageView.vue")
    },
    {
      path: "/login",
      name: "login",
      component: () => import("@/views/LoginView.vue")
    },
    {
      path: "/register",
      name: "register",
      component: () => import("@/views/RegisterView.vue")
    }
  ]
});

const guestRoutes = ["login", "register"];

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();

  if (!guestRoutes.includes(to.name as string) && authStore.auth.isAuthenticated === false) {
    next({ name: "login" });
  } else {
    next();
  }
});

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();

  if (guestRoutes.includes(to.name as string) && authStore.auth.isAuthenticated === true) {
    next({ name: "dashboard" });
  } else {
    next();
  }
});

export default router;
