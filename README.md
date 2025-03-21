# Project

This project is an event management application that allows users to view, filter, and create events. This repository contains both the frontend and backend of the application, organized into separate folders.

## Features

- Display a list of events with details.
- Filter events by title, start date, end date, and sorting options.
- Create new events.
- View details of specific events.

## Project Structure

The project is divided into two main parts:

- **`frontend`**: Contains the Vue.js application for the user interface.
- **`backend`**: Contains the PHP application for the API and server-side logic.

## Prerequisites

- Node.js (version 16 or higher) for the frontend.
- PHP (version 7.4 or higher) for the backend.
- npm package manager.

## Installation

### Frontend Setup

1. Navigate to the `frontend` folder:
   ```bash
   cd frontend
   ```

2. Install dependencies:
   ```bash
   npm install
   ```

3. Start the development server:
   ```bash
   npm run dev
   ```

4. Open the application in your browser at [http://localhost:5173](http://localhost:5173).

### Backend Setup

1. Navigate to the `backend` folder:
   ```bash
   cd backend
   ```

2. Copy the `config/example.env` file to `config/.env`:
   ```bash
   cp config/example.env config/.env
   ```

3. Configure the environment variables in the `config/.env` file.

4. Create the `cache` folder with write permissions:
   ```bash
   mkdir -p cache && chmod 777 cache
   ```

5. Start the PHP server:
   ```bash
   php -S localhost:8000 -t public
   ```

6. Ensure the backend is running at [http://localhost:8000](http://localhost:8000).

### Notes

- The default ports for the frontend and backend are `5173` and `8000`, respectively. These can be changed if needed.
- Ensure both the frontend and backend are running simultaneously for the application to function correctly.

## API

The application communicates with an API hosted at `http://localhost:8000/api`. Ensure the backend is running before using the frontend.

### Key Endpoints

- **`GET /events`**: Retrieves a list of events with optional filters.
- **`GET /event/detail`**: Retrieves details of a specific event.
- **`POST /event`**: Creates a new event.