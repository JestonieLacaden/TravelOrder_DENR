SELECT id, leaveid, daterange, vacation_earned, vacation_this_app, vacation_balance, sick_earned, sick_this_app, sick_balance, is_approve1, is_approve2, is_approve3
FROM `leave`
WHERE employeeid = (SELECT employeeid FROM employee WHERE id = 24)
ORDER BY id DESC
LIMIT 5;
