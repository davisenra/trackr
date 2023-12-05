type Package = {
  id: number;
  name: string;
  description: string | null;
  trackingCode: string;
  status: PackageStatus;
  lastTrackedAt: string | null;
  createdAt: string;
  updatedAt: string;
};

type PackageEvent = {
  id: number;
  packageId: number;
  status: string;
  statusHash: string;
  location: string | null;
  destination: string | null;
  eventedAt: string;
  createdAt: string;
  updatedAt: string;
};

enum PackageStatus {
  Draft = "draft",
  Posted = "posted",
  InTransit = "in_transit",
  OutForDelivery = "out_for_delivery",
  Delivered = "delivered"
}

type PackageWithEvents = Package & {
  events: PackageEvent[];
};

type ApiResponse<T> = {
  data: T;
};

export { PackageStatus };
export type { Package, PackageEvent, PackageWithEvents, ApiResponse };
