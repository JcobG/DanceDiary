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
