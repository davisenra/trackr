import { defineStore } from "pinia";
import { reactive } from "vue";
import { getCsrfToken, login } from "@/services/auth";
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

  return {
    auth,
    attemptLogin
  };
});
