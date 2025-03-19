const API_BASE_URL = "http://localhost:8000/api";
const eventCache = new Map<string, any>(); // Cache for storing loaded events

/**
 * Fetch all events from the API.
 *
 * @returns A promise resolving to the list of events.
 */
export const fetchEvents = async () => {
  try {
    const response = await fetch(`${API_BASE_URL}/events`);
    const data = await response.json();
    return data.items; // Return the 'items' array from the response
  } catch (error) {
    console.error("Failed to fetch events:", error);
    throw error;
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
    const event = await response.json();
    eventCache.set(id, event);
    return event;
  } catch (error) {
    console.error(`Failed to fetch event with ID ${id}:`, error);
    throw error;
  }
};