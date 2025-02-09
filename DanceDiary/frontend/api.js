const API_BASE_URL = "http://localhost:8000";

// Funkcja logowania
async function loginUser(email, password) {
    const response = await fetch(`${API_BASE_URL}/users?action=login`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, password }),
    });

    const data = await response.json();
    return data;
}

// Funkcja rejestracji
async function registerUser(userData) {
    const response = await fetch(`${API_BASE_URL}/users?action=register`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(userData),
    });

    const data = await response.json();
    return data;
}

// Funkcja wyszukiwania trenerów
async function searchTrainers(name, studio) {
    const response = await fetch(`${API_BASE_URL}/trainers?name=${name}&studio=${studio}`);
    return await response.json();
}
