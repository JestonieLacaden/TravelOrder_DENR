SELECT id, firstname, email, vacation_leave_balance, sick_leave_balance
FROM employee
WHERE id = 24;

UPDATE employee SET vacation_leave_balance = 24.500 WHERE id = 24;

SELECT id, firstname, email, vacation_leave_balance, sick_leave_balance
FROM employee
WHERE id = 24;
