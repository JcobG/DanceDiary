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
    longitude DECIMAL(9, 6)
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
