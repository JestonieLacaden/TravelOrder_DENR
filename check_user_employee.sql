SELECT u.id, u.email, e.id as emp_id, e.firstname, e.email as emp_email, e.vacation_leave_balance
FROM users u
LEFT JOIN employee e ON u.email = e.email
WHERE e.firstname LIKE '%Henry%' OR u.email LIKE '%marmol%';
