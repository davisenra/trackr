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

export { getCsrfToken, login };
