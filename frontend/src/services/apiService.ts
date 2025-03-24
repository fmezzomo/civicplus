import Toastify from "toastify-js";
import "toastify-js/src/toastify.css";
import { showNotification } from "../utils/notificationUtil"; // Import the utility

const API_BASE_URL = "http://localhost:8000/api";
const eventCache = new Map<string, Event>(); // Cache for storing loaded events


/**
 * Fetch all events from the API with optional filters.
 *
 * @param filters Optional filters for the API request.
 * @returns A promise resolving to the list of events.
 */
export const fetchEvents = async (
  filters: FetchEventsFilters = {}
): Promise<FetchEventsResponse> => {
  try {
    let queryString = "";

    const filterClauses: string[] = [];
    if (filters.title) filterClauses.push(`contains(title, '${filters.title}')`);
    if (filters.startDate) filterClauses.push(`startDate ge ${filters.startDate}`);
    if (filters.endDate) filterClauses.push(`endDate le ${filters.endDate}`);

    if (filterClauses.length > 0) {
      queryString += `filter=${filterClauses.join(" and ")}&`;
    }

    if (filters.orderBy) {
      const orderBy = `${filters.orderBy.field} ${filters.orderBy.direction}`;
      queryString += `orderby=${orderBy}&`;
    }

    if (filters.top) {
      queryString += `top=${filters.top}&`;
    }

    if (filters.skip) {
      queryString += `skip=${filters.skip}&`;
    }

    queryString = queryString.endsWith("&") ? queryString.slice(0, -1) : queryString;
    const response = await fetch(`${API_BASE_URL}/events?${queryString}`);
    const data = await response.json();

    if (!data.success) {
      showNotification(data.message || "Failed to fetch events", "error");
      throw new Error(data.message || "Failed to fetch events");
    }

    return {
      events: data.data.items || [],
      total: data.data.total || 0,
    };
  } catch (error: any) {
    console.error("Failed to fetch events:", error.message);
    showNotification(error.message || "An error occurred while fetching events.", "error");
    throw new Error(error.message || "An error occurred while fetching events.");
  }
};

/**
 * Fetch details of a specific event by ID.
 *
 * @param id The ID of the event.
 * @returns A promise resolving to the event details.
 */
export const fetchEventById = async (id: string) => {
  if (eventCache.has(id)) {
    return eventCache.get(id); // Return cached event if available
  }

  try {
    const response = await fetch(`${API_BASE_URL}/event/detail?id=${id}`);
    const data = await response.json();

    if (!data.success) {
      showNotification(data.message || "Failed to fetch event details", "error");
      throw new Error(data.message || "Failed to fetch event details");
    }

    eventCache.set(id, data.data);
    return data.data;
  } catch (error: any) {
    console.error("Failed to fetch event details:", error.message);
    showNotification(error.message || "An error occurred while fetching event details.", "error");
    throw new Error(error.message || "An error occurred while fetching event details.");
  }
};

/**
 * Create a new event.
 *
 * @param eventData The data for the new event.
 * @returns A promise resolving to the created event.
 */
export const createEvent = async (eventData: Record<string, any>) => {
  try {
    const response = await fetch(`${API_BASE_URL}/event`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(eventData),
    });
    const data = await response.json();

    if (!data.success) {
      showNotification(data.message || "Failed to create event", "error");
      throw new Error(data.message || "Failed to create event");
    }

    showNotification("Event created successfully!", "success");
    return data.data;
  } catch (error: any) {
    console.error("Failed to create event:", error.message);
    showNotification(error.message || "An error occurred while creating the event.", "error");
    throw new Error(error.message || "An error occurred while creating the event.");
  }
};
