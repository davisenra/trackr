import type { ApiResponse, Package, PackageWithEvents } from "@/types";
import { httpClient } from "@/services/http";

const fetchAllPackages = async () => {
  return await httpClient<ApiResponse<Package[]>>("/api/packages");
};

const fetchPackage = async (id: number) => {
  return await httpClient<ApiResponse<PackageWithEvents>>(`/api/packages/${id}`);
};

const deletePackage = async (id: number) => {
  return await httpClient<ApiResponse<Package>>(`/api/packages/${id}`, {
    method: "DELETE"
  });
};

const trackPackage = async (payload: {
  name: string;
  description?: string;
  trackingNumber: string;
}) => {
  return await httpClient<ApiResponse<Package>>("/api/packages", {
    method: "POST",
    body: JSON.stringify(payload)
  });
};

export { fetchAllPackages, fetchPackage, deletePackage, trackPackage };
