-- Widok: nadchodzące lekcje dla użytkownika
CREATE VIEW user_upcoming_reservations AS
SELECT 
    u.first_name || ' ' || u.last_name AS user_name,
    t.first_name || ' ' || t.last_name AS trainer_name,
    s.name AS studio_name,
    r.reservation_date
FROM reservations r
JOIN users u ON r.user_id = u.user_id
JOIN users t ON r.trainer_id = t.user_id
JOIN studios s ON r.studio_id = s.studio_id
WHERE r.reservation_date > NOW();

-- Widok: użytkownicy i ich notatki
CREATE VIEW user_notes AS
SELECT 
    u.user_id, 
    u.first_name || ' ' || u.last_name AS user_name, 
    n.content, 
    n.created_at
FROM notes n
JOIN users u ON n.user_id = u.user_id;

-- Widok: wyszukiwanie trenerów po imieniu, nazwisku lub nazwie studia
CREATE OR REPLACE VIEW trainer_search_view AS
SELECT 
    u.user_id, 
    u.first_name || ' ' || u.last_name AS full_name, 
    s.name AS studio_name
FROM users u
LEFT JOIN studios s ON u.studio_id = s.studio_id
WHERE u.role = 'trener';
