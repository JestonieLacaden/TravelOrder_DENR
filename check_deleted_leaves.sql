SELECT id, leaveid, daterange, vacation_balance, is_approve1, is_approve2, is_approve3
FROM `leave`
WHERE employeeid = (SELECT employeeid FROM employee WHERE id = 24)
ORDER BY id DESC
LIMIT 5;
