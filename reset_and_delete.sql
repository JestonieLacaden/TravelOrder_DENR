SELECT id, vacation_earned, vacation_this_app, vacation_balance FROM `leave` WHERE id = 81;

-- Reset employee balance
UPDATE employee SET vacation_leave_balance = 24.500 WHERE id = 24;

-- Delete the problematic leave application
DELETE FROM `leave` WHERE id = 81;

SELECT id, vacation_leave_balance FROM employee WHERE id = 24;
