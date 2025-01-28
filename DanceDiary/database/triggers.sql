-- Wyzwalacz: wysyłanie powiadomienia po dodaniu rezerwacji
CREATE OR REPLACE FUNCTION notify_reservation()
RETURNS TRIGGER AS $$
BEGIN
    RAISE NOTICE 'Reservation added for user_id: %, trainer_id: %, on: %', 
        NEW.user_id, NEW.trainer_id, NEW.reservation_date;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER reservation_added
AFTER INSERT ON reservations
FOR EACH ROW
EXECUTE FUNCTION notify_reservation();

-- Wyzwalacz do logowania rezerwacji
CREATE TRIGGER add_reservation_log
AFTER INSERT ON reservations
FOR EACH ROW
EXECUTE FUNCTION log_activity();
