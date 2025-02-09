-- Funkcja: liczenie wolnych miejsc w studiu dla danego terminu
CREATE OR REPLACE FUNCTION count_available_spots(studio_id_input INT, reservation_time TIMESTAMP)
RETURNS INT AS $$
DECLARE
    total_reservations INT;
    studio_capacity INT;
BEGIN
    -- Pobranie całkowitej liczby miejsc w studiu
    SELECT capacity INTO studio_capacity
    FROM studios
    WHERE studio_id = studio_id_input;

    -- Liczba już dokonanych rezerwacji
    SELECT COUNT(*) INTO total_reservations
    FROM reservations
    WHERE studio_id = studio_id_input AND reservation_date = reservation_time;

    -- Obliczenie dostępnych miejsc
    RETURN studio_capacity - total_reservations;
END;
$$ LANGUAGE plpgsql;

-- Funkcja powiadomienia o rezerwacji
CREATE OR REPLACE FUNCTION notify_reservation()
RETURNS TRIGGER AS $$
BEGIN
    RAISE NOTICE 'Reservation added for user_id: %, trainer_id: %, on: %', 
        NEW.user_id, NEW.trainer_id, NEW.reservation_date;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Funkcja logowania aktywności
CREATE OR REPLACE FUNCTION log_activity()
RETURNS TRIGGER AS $$
BEGIN
    INSERT INTO activity_logs (user_id, activity)
    VALUES (NEW.user_id, 'Added a reservation');
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;


-- Funkcja generowania tokenu resetu hasła
CREATE OR REPLACE FUNCTION generate_reset_token(user_email VARCHAR)
RETURNS VARCHAR AS $$
DECLARE
    token VARCHAR := gen_random_uuid()::text;
BEGIN
    UPDATE users
    SET reset_token = token
    WHERE email = user_email;
    RETURN token;
END;
$$ LANGUAGE plpgsql;

-- Funkcja sprawdzająca poprawność daty rezerwacji
CREATE OR REPLACE FUNCTION enforce_future_reservations()
    RETURNS TRIGGER AS $$
BEGIN
    IF NEW.reservation_date <= NOW() THEN
        RAISE EXCEPTION 'Reservation date must be in the future';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;