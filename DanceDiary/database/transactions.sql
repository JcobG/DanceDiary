-- Transakcja: rezerwacja miejsca

BEGIN;


    -- Wstawienie rezerwacji
    INSERT INTO reservations (user_id, trainer_id, studio_id, reservation_date)
    VALUES (1, 2, 1, '2025-02-01 15:00:00');

    -- Sprawdzenie liczbe wolnych miejsc
    SELECT count_available_spots(1, '2025-02-01 15:00:00');

    COMMIT;

