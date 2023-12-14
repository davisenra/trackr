import { ofetch, type $Fetch } from "ofetch";
import { useAuthStore } from "@/stores/auth";

const getCsrfCookie = () => {
  if (!document.cookie) {
    return null;
  }

  const xsrfCookies = document.cookie
    .split(";")
    .map((c) => c.trim())
    .filter((c) => c.startsWith("XSRF-TOKEN" + "="));

  if (xsrfCookies.length === 0) {
    return null;
  }

  return decodeURIComponent(xsrfCookies[0].split("=")[1]);
};

const httpClient: $Fetch = ofetch.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  credentials: "include",
  async onRequest({ options }) {
    options.headers = {
      ...options.headers,
      "Content-Type": "application/json",
      Accept: "application/json",
      Referer: import.meta.env.VITE_APP_DOMAIN,
      Origin: import.meta.env.VITE_APP_DOMAIN,
      credentials: "include",
      "X-XSRF-TOKEN": getCsrfCookie() as string
    };
  },
  async onResponse({ response }) {
    if (response.status === 401) {
      const authStore = useAuthStore();
      await authStore.logout();
    }
  }
});

export { httpClient };
