import { ofetch, type $Fetch } from "ofetch";

const httpClient: $Fetch = ofetch.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  credentials: "include",
  async onRequest({ options }) {
    options.headers = {
      ...options.headers,
      "Content-Type": "application/json",
      Accept: "application/json",
      Referer: import.meta.env.VITE_API_BASE_URL,
      Origin: import.meta.env.VITE_API_BASE_URL,
      credentials: "include"
    };
  },
  async onResponse({ response }) {
    if (response.status === 401) {
      // TODO: handle unauthenticated requests
    }
  }
});

export { httpClient };
