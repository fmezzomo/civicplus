const API_BASE_URL = "http://localhost:8000/api";
const eventCache = new Map<string, any>(); // Cache for storing loaded events

/**
 * Fetch all events from the API with optional filters.
 *
 * @param filters Optional filters for the API request.
 * @returns A promise resolving to the list of events.
 */
export const fetchEvents = async (filters: Record<string, any> = {}) => {
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

    queryString = queryString.endsWith("&") ? queryString.slice(0, -1) : queryString;
    const response = await fetch(`${API_BASE_URL}/events?${queryString}`);

    const data = await response.json();
    return data.items;
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
    if (!response.ok) {
      throw new Error(`API responded with status ${response.status}`);
    }
    const event = await response.json();
    eventCache.set(id, event);
    return event;
  } catch (error) {
    throw error;
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
    return await response.json();
  } catch (error) {
    console.error("Failed to create event:", error);
    throw error;
  }
};
