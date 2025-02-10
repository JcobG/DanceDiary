const API_BASE_URL = "http://localhost:8000";

// Funkcja logowania z obsługą błędów
async function loginUser(email, password) {
    try {
        const response = await fetch(`${API_BASE_URL}/users?action=login`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ email, password }),
        });

        if (!response.ok) throw new Error("Błąd serwera");

        return await response.json();
    } catch (error) {
        console.error("Błąd logowania:", error);
        return { success: false, message: "Błąd logowania. Sprawdź połączenie z serwerem." };
    }
}

// Funkcja rejestracji z obsługą błędów
async function registerUser(userData) {
    try {
        const response = await fetch(`${API_BASE_URL}/users?action=register`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(userData),
        });

        if (!response.ok) throw new Error("Błąd serwera");

        return await response.json();
    } catch (error) {
        console.error("Błąd rejestracji:", error);
        return { success: false, message: "Błąd rejestracji. Sprawdź połączenie z serwerem." };
    }
}

// Funkcja wyszukiwania trenerów z obsługą błędów
async function searchTrainers(name, studio) {
    try {
        const response = await fetch(`${API_BASE_URL}/trainers?name=${name}&studio=${studio}`);
        if (!response.ok) throw new Error("Błąd serwera");

        return await response.json();
    } catch (error) {
        console.error("Błąd wyszukiwania trenerów:", error);
        return [];
    }
}

// Funkcja pobierania rezerwacji użytkownika z obsługą błędów
async function getUserReservations(userId) {
    try {
        const response = await fetch(`${API_BASE_URL}/reservations?user_id=${userId}`);
        if (!response.ok) throw new Error("Błąd serwera");

        return await response.json();
    } catch (error) {
        console.error("Błąd pobierania rezerwacji:", error);
        return [];
    }
}
// Funkcja dodawania rezerwacji z obsługą błędów
async function addReservation(userId, trainerId, studioId, reservationDate) {
    try {
        const response = await fetch(`${API_BASE_URL}/reservations`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ user_id: userId, trainer_id: trainerId, studio_id: studioId, reservation_date: reservationDate }),
        });

        if (!response.ok) {
            throw new Error("Błąd serwera podczas dodawania rezerwacji");
        }

        return await response.json();
    } catch (error) {
        console.error("Błąd dodawania rezerwacji:", error);
        return { success: false, message: "Nie udało się dodać rezerwacji. Sprawdź połączenie z serwerem." };
    }
}
// Sprawdza, czy użytkownik jest zalogowany
function checkUserSession() {
    const user = JSON.parse(localStorage.getItem("user"));
    return user ? user : null;
}

// Obsługa wylogowania
function logoutUser() {
    localStorage.removeItem("user");
    window.location.href = "index.html"; // Przekierowanie na stronę główną po wylogowaniu
}

// Automatycznie ukrywa/pokazuje przyciski logowania i rejestracji
document.addEventListener("DOMContentLoaded", () => {
    const user = checkUserSession();
    if (user) {
        document.getElementById("login-link")?.classList.add("hidden");
        document.getElementById("register-link")?.classList.add("hidden");
        document.getElementById("logout-link")?.classList.remove("hidden");
    } else {
        document.getElementById("logout-link")?.classList.add("hidden");
    }

    // Obsługa kliknięcia w Logout
    document.getElementById("logout-link")?.addEventListener("click", logoutUser);
});

