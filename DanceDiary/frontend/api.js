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
// Pobiera profil użytkownika
async function getUserProfile(userId) {
    try {
        const response = await fetch(`${API_BASE_URL}/users/profile/${userId}`);
        if (!response.ok) throw new Error("Błąd pobierania profilu użytkownika");

        return await response.json();
    } catch (error) {
        console.error("Błąd pobierania profilu:", error);
        return null;
    }
}

// Aktualizuje profil użytkownika
async function updateUserProfile(userId, profileData) {
    try {
        const response = await fetch(`${API_BASE_URL}/users/profile/${userId}`, {
            method: "PUT",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(profileData),
        });

        if (!response.ok) throw new Error("Błąd aktualizacji profilu");

        return await response.json();
    } catch (error) {
        console.error("Błąd aktualizacji profilu:", error);
        return { success: false, message: "Nie udało się zaktualizować profilu" };
    }
}
// Pobiera specjalizacje danego trenera
async function getTrainerSpecializations(trainerId) {
    try {
        const response = await fetch(`${API_BASE_URL}/trainers/specializations/${trainerId}`);
        if (!response.ok) throw new Error("Błąd pobierania specjalizacji");

        return await response.json();
    } catch (error) {
        console.error("Błąd pobierania specjalizacji:", error);
        return [];
    }
}

// Dodaje nową specjalizację dla trenera
async function addTrainerSpecialization(trainerId, specialization) {
    try {
        const response = await fetch(`${API_BASE_URL}/trainers/specializations/${trainerId}`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ specialization }),
        });

        if (!response.ok) throw new Error("Błąd dodawania specjalizacji");

        return await response.json();
    } catch (error) {
        console.error("Błąd dodawania specjalizacji:", error);
        return { success: false, message: "Nie udało się dodać specjalizacji" };
    }
}

// Usuwa specjalizację trenera
async function deleteTrainerSpecialization(trainerId, specialization) {
    try {
        const response = await fetch(`${API_BASE_URL}/trainers/specializations/${trainerId}/${encodeURIComponent(specialization)}`, {
            method: "DELETE",
        });

        if (!response.ok) throw new Error("Błąd usuwania specjalizacji");

        return await response.json();
    } catch (error) {
        console.error("Błąd usuwania specjalizacji:", error);
        return { success: false, message: "Nie udało się usunąć specjalizacji" };
    }
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

