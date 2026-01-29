SELECT id, firstname, email, vacation_leave_balance, sick_leave_balance
FROM employee
WHERE firstname LIKE '%HENRY%' OR email LIKE '%marmol%';
