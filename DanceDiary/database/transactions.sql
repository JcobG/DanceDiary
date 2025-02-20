-- Transakcja: rezerwacja miejsca
BEGIN TRANSACTION ISOLATION LEVEL SERIALIZABLE;
BEGIN;
INSERT INTO reservations (user_id, trainer_id, studio_id, reservation_date)
VALUES (1, 2, 1, '2025-02-01 15:00:00');

-- Sprawdzenie dostępności miejsc
DO $$
    DECLARE available INT;
    BEGIN
        SELECT count_available_spots(1, '2025-02-01 15:00:00') INTO available;
        IF available < 1 THEN
            RAISE EXCEPTION 'Brak wolnych miejsc!';
        END IF;
    END $$;

COMMIT;


