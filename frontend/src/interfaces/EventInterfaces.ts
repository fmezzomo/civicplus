
export interface Event {
  id: string;
  title: string;
  description: string;
  startDate: string;
  endDate: string;
}

export interface Filters {
  title: string;
  startDate: string;
  endDate: string;
  orderBy: {
    field: "startDate" | "endDate" | "title";
    direction: "asc" | "desc";
  };
}

export interface Pagination {
  page: number;
  limit: number;
  total: number;
}

export interface FetchEventsResponse {
  events: Event[];
  total: number;
}

export interface FetchEventsFilters {
  title?: string;
  startDate?: string;
  endDate?: string;
  orderBy?: {
    field: "startDate" | "endDate" | "title";
    direction: "asc" | "desc";
  };
  top?: number;
  skip?: number;
}
