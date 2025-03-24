import Toastify from "toastify-js";
import "toastify-js/src/toastify.css";

/**
 * Display a notification to the user.
 *
 * @param message The message to display.
 * @param type The type of notification ("success" or "error").
 */
export const showNotification = (message: string, type: "success" | "error") => {
  Toastify({
    text: message,
    duration: 5000,
    close: true,
    gravity: "top",
    position: "right",
    backgroundColor: type === "success" ? "green" : "red",
    stopOnFocus: true,
  }).showToast();
};
