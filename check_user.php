<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check user
$user = DB::table('users')->where('email', 'h.marmol3@gmail.com')->first();
echo "=== USER ===\n";
echo "User ID: " . $user->id . "\n";
echo "User Email: " . $user->email . "\n";

// Raw database query
$employee = DB::table('employee')->where('email', 'h.marmol3@gmail.com')->first();
echo "\n=== EMPLOYEE (RAW DB) ===\n";
echo "Employee ID: " . $employee->id . "\n";
echo "Name: " . $employee->firstname . "\n";
echo "Email: " . $employee->email . "\n";
echo "Vacation Balance: " . $employee->vacation_leave_balance . "\n";
echo "Sick Balance: " . $employee->sick_leave_balance . "\n";

// Using Eloquent Model
$empModel = App\Models\Employee::where('email', 'h.marmol3@gmail.com')->first();
echo "\n=== ELOQUENT MODEL ===\n";
echo "Vacation Balance: " . $empModel->vacation_leave_balance . "\n";
echo "Sick Balance: " . $empModel->sick_leave_balance . "\n";

// Through User relationship
$userModel = App\Models\User::where('email', 'h.marmol3@gmail.com')->first();
echo "\n=== THROUGH USER->EMPLOYEE ===\n";
if ($userModel->Employee) {
    echo "Vacation Balance: " . $userModel->Employee->vacation_leave_balance . "\n";
    echo "Sick Balance: " . $userModel->Employee->sick_leave_balance . "\n";
} else {
    echo "No Employee relationship found!\n";
}
