<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Unit;
use App\Models\User;
use App\Models\Office;
use App\Models\Section;
use App\Models\Employee;
use App\Models\UserRole;
use App\Models\Leave_Type;
use App\Models\LeaveSignatory;
use Illuminate\Database\Seeder;
use App\Models\LeaveSignatoryModel;
use App\Models\TravelOrderSignatory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Office::create([
            'id' => '1',
            'office' => 'PENRO - OCCIDENTAL MINDORO',
        ]);
        Office::create([
            'id' => '2',
            'office' => 'CENRO SABLAYAN',
        ]);
        Office::create([
            'id' => '3',
            'office' => 'CENRO SAN JOSE',
        ]); 
       
        Office::create([
            'id' => '4',
            'office' => 'OTHERS',
        ]); 
        
        Section::create([
            'id' => '3',
            'officeid' => '1',
            'section' => 'TECHNICAL SERVICES DIVISION',
        ]);
        Section::create([
            'id' => '2',
            'officeid' => '1',
            'section' => 'MANAGEMENT SERVICES DIVISION',
        ]);
        Section::create([
            'id' => '1',
            'officeid' => '1',
            'section' => 'OFFICE OF THE PENRO',
        ]);

        Section::create([
            'id' => '4',
            'officeid' => '4',
            'section' => 'OTHERS',
        ]);

      

        Unit::create([
            'id' => '1',
            'officeid' => '1',
            'sectionid' => '1',
            'unit' => 'OFFICE OF THE PENRO'

        ]);
        Unit::create([
            'id' => '2',
            'officeid' => '1',
            'sectionid' => '2',
            'unit' => 'PLANNING SECTION'

        ]);
        Unit::create([
            'id' => '3',
            'officeid' => '1',
            'sectionid' => '2',
            'unit' => 'ADMIN SECTION'

        ]);

        Unit::create([
            'id' => '4',
            'officeid' => '1',
            'sectionid' => '2',
            'unit' => 'CASHIER SECTION'

        ]);
        Unit::create([
            'id' => '5',
            'officeid' => '1',
            'sectionid' => '3',
            'unit' => 'MES - MONITORING AND ENFORCEMENT SECTION'

        ]);

        Unit::create([
            'id' => '6',
            'officeid' => '1',
            'sectionid' => '2',
            'unit' => 'OFFICE OF THE MSD CHIEF'

        ]);

        Unit::create([
            'id' => '7',
            'officeid' => '1',
            'sectionid' => '2',
            'unit' => 'RECORDS SECTION'

        ]);

        Unit::create([
            'id' => '8',
            'officeid' => '1',
            'sectionid' => '2',
            'unit' => 'BUDGET SECTION'

        ]);
        Unit::create([
            'id' => '9',
            'officeid' => '1',
            'sectionid' => '2',
            'unit' => 'ACCOUNTING SECTION'

        ]);

        Unit::create([
            'id' => '10',
            'officeid' => '1',
            'sectionid' => '2',
            'unit' => 'SUPPLY SECTION'

        ]);

        Unit::create([
            'id' => '11',
            'officeid' => '1',
            'sectionid' => '2',
            'unit' => 'GENERAL SERVICES SECTION'

        ]);

        Unit::create([
            'id' => '12',
            'officeid' => '1',
            'sectionid' => '3',
            'unit' => 'RPS - REGULATION AND PERMITTING SECTION SECTION'

        ]);

        Unit::create([
            'id' => '13',
            'officeid' => '1',
            'sectionid' => '3',
            'unit' => 'CDS - CONSERVATION AND DEVELOPMENT SECTION'

        ]);

        Unit::create([
            'id' => '14',
            'officeid' => '1',
            'sectionid' => '3',
            'unit' => 'OFFICE OF THE TSD CHIEF'

        ]);
        Unit::create([
            'id' => '15',
            'officeid' => '4',
            'sectionid' => '4',
            'unit' => 'OTHERS'

        ]);

        Unit::create([
            'id' => '16',
            'officeid' => '1',
            'sectionid' => '2',
            'unit' => 'BAC - BIDS AND AWARDS COMMITTEE'

        ]);

        Unit::create([
            'id' => '17',
            'officeid' => '1',
            'sectionid' => '2',
            'unit' => 'BD - CORRIDOR'

        ]);

        Unit::create([
            'id' => '18',
            'officeid' => '1',
            'sectionid' => '2',
            'unit' => 'OJT - ON THE JOB TRAINING'

        ]);

    

        Role::create([
            'id' => '1',
            'rolename' => 'ADMINISTRATOR',
        ]);
        Role::create([
            'id' => '2',
            'rolename' => 'USER',
        ]);
        Role::create([
            'id' => '3',
            'rolename' => 'ACCOUNTING',
        ]);
        Role::create([
            'id' => '4',
            'rolename' => 'PLANNING',
        ]);
        Role::create([
            'id' => '5',
            'rolename' => 'MSD - ENCODER',
        ]);
        Role::create([
            'id' => '6',
            'rolename' => 'MSD - APPROVER',
        ]);
        Role::create([
            'id' => '7',
            'rolename' => 'MSD - EVENTS',
        ]);
        Role::create([
            'id' => '8',
            'rolename' => 'MSD - CHIEF',
        ]);
        Role::create([
            'id' => '9',
            'rolename' => 'RECORDS - ENCODER',
        ]);
        Role::create([
            'id' => '10',
            'rolename' => 'RECORDS - HEAD',
        ]);
        Role::create([
            'id' => '11',
            'rolename' => 'MSD - AO',
        ]);
        Role::create([
            'id' => '12',
            'rolename' => 'PENRO',
        ]);

        Role::create([
            'id' => '13',
            'rolename' => 'FM - RECORDS',
        ]);

        Role::create([
            'id' => '14',
            'rolename' => 'FM - PLANNING',
        ]);

        Role::create([
            'id' => '15',
            'rolename' => 'FM - ACCOUNTING',
        ]);

        Role::create([
            'id' => '16',
            'rolename' => 'FM - BUDGET',
        ]);

        Role::create([
            'id' => '17',
            'rolename' => 'FM - CASHIER',
        ]);

        Role::create([
            'id' => '18',
            'rolename' => 'FM - PENRO',
        ]);

        Role::create([
            'id' => '19',
            'rolename' => 'FM-MSD',
        ]);

        Role::create([
            'id' => '20',
            'rolename' => 'FM-TSD',
        ]);

        Role::create([
            'id' => '21',
            'rolename' => 'FM-OTHERS',
        ]);

        UserRole::create([
            'userid' => '1',
            'roleid' => '1',
        ]);
        // UserRole::create([
        //     'userid' => '2',
        //     'roleid' => '2',
        // ]);

        // UserRole::create([
        //     'userid' => '2',
        //     'roleid' => '5',
        // ]);
    
        // UserRole::create([
        //     'userid' => '3',
        //     'roleid' => '4',
        // ]);

        // UserRole::create([
        //     'userid' => '3',
        //     'roleid' => '2',
        // ]);
    
        // UserRole::create([
        //     'userid' => '4',
        //     'roleid' => '2',
        // ]);
        // UserRole::create([
        //     'userid' => '4',
        //     'roleid' => '6',
        // ]);
        // UserRole::create([
        //     'userid' => '4',
        //     'roleid' => '7',
        // ]);
    
        // UserRole::create([
        //     'userid' => '5',
        //     'roleid' => '2',
        // ]);
        // UserRole::create([
        //     'userid' => '5',
        //     'roleid' => '7',
        // ]);
        // UserRole::create([
        //     'userid' => '5',
        //     'roleid' => '8',
        // ]);
        // UserRole::create([
        //     'userid' => '6',
        //     'roleid' => '2',
        // ]);
        // UserRole::create([
        //     'userid' => '6',
        //     'roleid' => '12',
        // ]);

        // UserRole::create([
        //     'userid' => '7',
        //     'roleid' => '2',
        // ]);
    
        // UserRole::create([
        //     'userid' => '7',
        //     'roleid' => '9',
        // ]);
        // UserRole::create([
        //     'userid' => '7',
        //     'roleid' => '10',
        // ]);
    
    
        User::create([
            'username' => 'administrator',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),   
            'is_verified' =>  true,
            'is_enable' => true,  
            'has_role' => true,
            'is_admin' => true,
          
        ]);
  
        // User::create([
        //     'username' => 'syies',
        //     'email' => 'syries@gmail.com',
        //     'password' => bcrypt('123456'),   
        //     'is_verified' =>  true,
        //     'is_enable' => true,  
            
             
        // ]);
    
        // User::create([
        //     'username' => 'henry',
        //     'email' => 'henry@gmail.com',
        //     'password' => bcrypt('123456'),   
        //     'is_verified' =>  true,
        //     'is_enable' => true,  
             
        // ]);

        // User::create([
        //     'username' => 'vonerika',
        //     'email' => 'vonerika@gmail.com',
        //     'password' => bcrypt('123456'),   
        //     'is_verified' =>  true,
        //     'is_enable' => true,  
             
        // ]);

        // User::create([
        //     'username' => 'abe',
        //     'email' => 'msd@gmail.com',
        //     'password' => bcrypt('123456'),   
        //     'is_verified' =>  true,
        //     'is_enable' => true,  
             
        // ]);
        // User::create([
        //     'username' => 'ernesto',
        //     'email' => 'penro@gmail.com',
        //     'password' => bcrypt('123456'),   
        //     'is_verified' =>  true,
        //     'is_enable' => true,  
             
        // ]);

        // User::create([
        //     'username' => 'annchie',
        //     'email' => 'records@gmail.com',
        //     'password' => bcrypt('123456'),   
        //     'is_verified' =>  true,
        //     'is_enable' => true,  
             
        // ]);


     
        // Employee::create([
        //     'id' => '1',
        //     'employeeid' => 'P-000001',
        //     'firstname' => 'JOHN SYRIES',
        //     'middlename' => 'R.',   
        //     'lastname' => 'RAGMAT',
        //     'birthdate' => '2002-02-02',
        //     'contactnumber' => '09261112341',
        //     'email' => 'syries@gmail.com',
        //     'address' => 'MAMBURAO',
        //     'officeid' => '1',
        //     'sectionid' => '2',
        //     'unitid' => '3',
        //     'position' => 'ADMINISTRATIVE ASSISTANT',
        //     'datehired' => '2002-01-01',
        //     'empstatus' => 'PERMANENT',
        //     'officesectionunit' => '1,2,3',
        //     'has_account' => true,
             
        // ]);

        Employee::create([
            'id' => '2',
            'employeeid' => 'P-000002',
            'firstname' => 'HENRY',
            'middlename' => 'A.',   
            'lastname' => 'MARMOL',
            'birthdate' => '2002-02-02',
            'contactnumber' => '09261112341',
            'email' => 'henry@gmail.com',
            'address' => 'MAMBURAO',
            'officeid' => '1',
            'sectionid' => '2',
            'unitid' => '2',
            'position' => 'ISA II',
            'datehired' => '2002-01-01',
            'empstatus' => 'PERMANENT',
            'officesectionunit' => '1,2,2',
            'has_account' => FALSE,
 
             
        ]);
        // Employee::create([
        //     'id' => '3',
        //     'employeeid' => 'P-000003',
        //     'firstname' => 'VON ERIKA',
        //     'middlename' => 'S.',   
        //     'lastname' => 'CAUSAPIN',
        //     'birthdate' => '2002-02-02',
        //     'contactnumber' => '09261112341',
        //     'email' => 'vonerika@gmail.com',
        //     'address' => 'MAMBURAO',
        //     'officeid' => '1',
        //     'sectionid' => '2',
        //     'unitid' => '3',
        //     'position' => 'ADMINISTRATIVE OFFICER IV',
        //     'datehired' => '2002-01-01',
        //     'empstatus' => 'PERMANENT',
        //     'officesectionunit' => '1,2,3',
        //     'has_account' => true,
 
             
        // ]);
        // Employee::create([
        //     'id' => '5',
        //     'employeeid' => 'P-000005',
        //     'firstname' => 'ERNESTO',
        //     'middlename' => 'E.',   
        //     'lastname' => 'TAÑADA',
        //     'birthdate' => '2002-02-02',
        //     'contactnumber' => '09261112341',
        //     'email' => 'penro@gmail.com',
        //     'address' => 'MAMBURAO',
        //     'officeid' => '1',
        //     'sectionid' => '1',
        //     'unitid' => '1',
        //     'position' => 'OIC, PROVINCIAL ENVIRONMENT AND NATURAL RESOURCES OFFICER',
        //     'datehired' => '2002-01-01',
        //     'empstatus' => 'PERMANENT',
        //     'officesectionunit' => '1,1,1',
        //     'has_account' => true,
 
             
        // ]);
        // Employee::create([
        //     'id' => '4',
        //     'employeeid' => 'P-000004',
        //     'firstname' => 'ABE',
        //     'middlename' => 'R.',   
        //     'lastname' => 'FRANCISCO',
        //     'birthdate' => '2002-02-02',
        //     'contactnumber' => '09261112341',
        //     'email' => 'msd@gmail.com',
        //     'address' => 'MAMBURAO',
        //     'officeid' => '1',
        //     'sectionid' => '2',
        //     'unitid' => '6',
        //     'position' => 'CHIEF, MANAGEMENT SERVICES DIVISION',
        //     'datehired' => '2002-01-01',
        //     'empstatus' => 'PERMANENT',
        //     'officesectionunit' => '1,2,6',
        //     'has_account' => true,
 
             
        // ]);

        // Employee::create([
        //     'id' => '6',
        //     'employeeid' => 'P-000006',
        //     'firstname' => 'ANN CHERRYL',
        //     'middlename' => 'R.',   
        //     'lastname' => 'HERNANDENZ',
        //     'birthdate' => '2002-02-02',
        //     'contactnumber' => '09261112341',
        //     'email' => 'records@gmail.com',
        //     'address' => 'MAMBURAO',
        //     'officeid' => '1',
        //     'sectionid' => '2',
        //     'unitid' => '7',
        //     'position' => 'CHIEF, MANAGEMENT SERVICES DIVISION',
        //     'datehired' => '2002-01-01',
        //     'empstatus' => 'PERMANENT',
        //     'officesectionunit' => '1,2,7',
        //     'has_account' => true,
 
             
        // ]);

        Leave_Type::create([
            'leave_type' => 'Vacation Leave (Sec. 51, Rule XV, Omnibus Rules Implementing E.O. No. 292)',
            'available' => 10,
        ]);
        Leave_Type::create([
            'leave_type' => 'Mandatory/Forced Leave (Sec. 25, Rule XVI, Omnibus Rules Implemmenting E.O. No. 292)',
            'available' => 5,
        ]);
        Leave_Type::create([
            'leave_type' => 'Sick Leave (Sec. 43, Rule XVI, Omnibus Rules Implemmenting E.O. No. 292)',
            'available' => 15,
        ]);
        Leave_Type::create([
            'leave_type' => 'Maternity Leave (R.A. No. 11210/IRR issued by CSC, DOLE and SSS)',
            'available' => 105,
        ]);
        Leave_Type::create([
            'leave_type' => 'Paternity Leave (RA No. 8187/CSC MC No. 71, S 1998, as amended)',
            'available' => 7,
        ]);
        Leave_Type::create([
            'leave_type' => 'Special Privilege Leave (Sec. 21, Rule XVI, Omnibus Rules Implemmenting E.O. No.292)',
            'available' => 3,
        ]);
        Leave_Type::create([
            'leave_type' => 'Solo Parent Leave (RA No. 8972/CSC MC no. 8, S. 2004)',
            'available' => 7,
        ]);
        Leave_Type::create([
            'leave_type' => 'Study Leave (Sec. 68, Rule XVI, Omnibus Rules Immplemmenting E.O. No. 292)',
            'available' => 0,
        ]);
        Leave_Type::create([
            'leave_type' => '10-Day VAWC Leave (RA No. 9262 / CSC MC No. 15, S. 2005)',
            'available' => 0,
        ]);
        Leave_Type::create([
            'leave_type' => 'Rehabilitation Privilege (Sec. 55, Rule XVI, Omnibus Rules Implementing E.O. No. 292)',
            'available' => 0,
        ]);
        Leave_Type::create([
            'leave_type' => 'Special Leave Benefits for Women (RA No. 9710 / CSC MC No. 25, S. 2010)',
            'available' => 0,
        ]);
        Leave_Type::create([
            'leave_type' => 'Special Emergency (Calamity) Leave (CSC MC no. 2, S. 2012, as amended)',
            'available' => 0,
        ]);
        Leave_Type::create([
            'leave_type' => 'Adoption Leave (RA No. 8552)',
            'available' => 0,
        ]);
        Leave_Type::create([
            'leave_type' => 'Others',
            'available' => 0,
        ]);  
        LeaveSignatory::create([
            'name' => 'PENRO - MAMBURAO',
            'approver1' => '3',
            'approver2' => '4',
            'approver3' => '5',
        ]);
        
        TravelOrderSignatory::create([
            'name' => 'MSD - PENRO',
            'approver1' => '4',
            'approver2' => '5',
        
        ]);
           
    }
}
