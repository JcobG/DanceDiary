const API_BASE_URL = "http://localhost:8000";

// Funkcja logowania
async function loginUser(email, password) {
    const response = await fetch(`${API_BASE_URL}/users?action=login`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, password }),
    });

    return await response.json();
}

// Funkcja rejestracji
async function registerUser(userData) {
    const response = await fetch(`${API_BASE_URL}/users?action=register`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(userData),
    });

    return await response.json();
}
// Pobieranie rezerwacji użytkownika
async function getUserReservations(userId) {
    const response = await fetch(`${API_BASE_URL}/reservations?user_id=${userId}`);
    return await response.json();
}

// Funkcja dodawania rezerwacji
async function addReservation(userId, trainerId, studioId, reservationDate) {
    const response = await fetch(`${API_BASE_URL}/reservations`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ user_id: userId, trainer_id: trainerId, studio_id: studioId, reservation_date: reservationDate }),
    });

    return await response.json();
}
// Funkcja wyszukiwania trenerów
async function searchTrainers(name, studio) {
    const response = await fetch(`${API_BASE_URL}/trainers?name=${name}&studio=${studio}`);
    return await response.json();
}
