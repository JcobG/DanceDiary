-- Wyzwalacz: wysyłanie powiadomienia po dodaniu rezerwacji

CREATE TRIGGER reservation_added
AFTER INSERT ON reservations
FOR EACH ROW
EXECUTE FUNCTION notify_reservation();

-- Wyzwalacz do logowania rezerwacji
CREATE TRIGGER add_reservation_log
AFTER INSERT ON reservations
FOR EACH ROW
EXECUTE FUNCTION log_activity();

-- Wyzwalacz do walidacji daty rezerwacji
CREATE TRIGGER check_reservation_date
BEFORE INSERT OR UPDATE ON reservations
FOR EACH ROW EXECUTE FUNCTION enforce_future_reservations();