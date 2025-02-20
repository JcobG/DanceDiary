-- Tabela użytkowników
CREATE TABLE users (
    user_id SERIAL PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(20) CHECK (role IN ('tancerz', 'trener')) NOT NULL,
    studio_id INT REFERENCES studios(studio_id) ON DELETE SET NULL
);

-- Tabela studiów tanecznych
CREATE TABLE studios (
    studio_id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    description TEXT,
    latitude DECIMAL(9, 6),
    longitude DECIMAL(9, 6),
    capacity INT NOT NULL CHECK (capacity > 0)
);

-- Tabela rezerwacji
CREATE TABLE reservations (
    reservation_id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(user_id) ON DELETE CASCADE,
    trainer_id INT REFERENCES users(user_id) ON DELETE CASCADE,
    studio_id INT REFERENCES studios(studio_id) ON DELETE CASCADE,
    reservation_date TIMESTAMP NOT NULL,
    status VARCHAR(20) DEFAULT 'confirmed' CHECK (status IN ('confirmed', 'cancelled'))
);

-- Tabela notatek użytkownika
CREATE TABLE notes (
    note_id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(user_id) ON DELETE CASCADE,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Tabela logów aktywności
CREATE TABLE activity_logs (
    log_id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(user_id) ON DELETE SET NULL,
    activity TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Tabela specjalizacji trenerów
CREATE TABLE trainer_specializations (
    trainer_id INT REFERENCES users(user_id) ON DELETE CASCADE,
    specialization VARCHAR(100) NOT NULL,
    PRIMARY KEY (trainer_id, specialization)
);

-- Tabela profili użytkowników
CREATE TABLE user_profiles (
    user_id INT PRIMARY KEY REFERENCES users(user_id) ON DELETE CASCADE,
    phone_number VARCHAR(20) UNIQUE,
    birthdate DATE,
    bio TEXT
);

-- Indeksy dla optymalizacji
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_reservations_date ON reservations(reservation_date);

-- Dodanie kolumny dla tokenu resetu
ALTER TABLE users ADD COLUMN reset_token VARCHAR(255);


-- Ograniczenie: jeden użytkownik nie może zarezerwować wielu miejsc na ten sam termin
ALTER TABLE reservations
    ADD CONSTRAINT unique_user_reservation_per_time UNIQUE (user_id, reservation_date);
