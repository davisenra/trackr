import { httpClient } from "@/services/http";

const getCsrfToken = async () => {
  await httpClient("/sanctum/csrf-cookie");
};

const login = async (payload: { email: string; password: string }) => {
  return await httpClient<{
    status: boolean;
    message: string;
  }>("/api/v1/login", {
    method: "POST",
    body: JSON.stringify(payload),
    ignoreResponseError: true
  });
};

const hasValidSession = async () => {
  try {
    await httpClient<Promise<void>>("/api/v1/me");
    return true;
  } catch (err) {
    return false;
  }
};

const logout = async () => {
  return await httpClient<Promise<void>>("/api/v1/logout", {
    method: "POST"
  });
};

export { getCsrfToken, login, hasValidSession, logout };
