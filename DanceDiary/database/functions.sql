-- Funkcja: liczenie wolnych miejsc w studiu dla danego terminu
CREATE OR REPLACE FUNCTION count_available_spots(studio_id_input INT, reservation_time TIMESTAMP)
RETURNS INT AS $$
DECLARE
    total_reservations INT;
BEGIN
    SELECT COUNT(*) INTO total_reservations
    FROM reservations
    WHERE studio_id = studio_id_input AND reservation_date = reservation_time;

    RETURN 10 - total_reservations; -- Zakładamy, że studio ma 10 miejsc
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
