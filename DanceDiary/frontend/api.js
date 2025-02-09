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
