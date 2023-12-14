import { defineStore } from "pinia";
import { reactive } from "vue";
import { getCsrfToken, login, logout as logoutFromServer, hasValidSession } from "@/services/auth";
import router from "@/router";

export const useAuthStore = defineStore("auth", () => {
  const auth = reactive({
    isAuthenticated: false
  });

  const attemptLogin = async (payload: { email: string; password: string }) => {
    await getCsrfToken();
    const response = await login(payload);

    if (response.status) {
      auth.isAuthenticated = true;
      router.push("/");
    }

    return response;
  };

  const logout = async () => {
    await logoutFromServer().catch((e) => console.log(e));
    auth.isAuthenticated = false;
  };

  const checkSession = async () => {
    auth.isAuthenticated = await hasValidSession();
  };

  return {
    auth,
    attemptLogin,
    checkSession,
    logout
  };
});
