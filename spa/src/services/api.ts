import type { ApiResponse, Package, PackageWithEvents } from "@/types";
import { ofetch } from "ofetch";

const api = ofetch.create({
  baseURL: import.meta.env.VITE_API_BASE_URL,
  credentials: "include",
  async onResponse({ response }) {
    if (response.status === 401) {
      // TODO: handle unauthenticated requests
    }
  }
});

const fetchAllPackages = async () => {
  return await api<ApiResponse<Package[]>>("/api/packages");
};

const fetchPackage = async (id: number) => {
  return await api<ApiResponse<PackageWithEvents>>(`/api/packages/${id}`);
};

const deletePackage = async (id: number) => {
  return await api<ApiResponse<Package>>(`/api/packages/${id}`, {
    method: "DELETE"
  });
};

const trackPackage = async (payload: {
  name: string;
  description?: string;
  trackingNumber: string;
}) => {
  return await api<ApiResponse<Package>>("/api/packages", {
    method: "POST",
    body: JSON.stringify(payload)
  });
};

export { fetchAllPackages, fetchPackage, deletePackage, trackPackage };
