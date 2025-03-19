const API_BASE_URL = "http://localhost:8000/api";

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