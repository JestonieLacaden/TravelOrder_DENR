# 📊 LEAVE CREDITS IMPORT FEATURE - NEW ADDITION

**Date:** December 22, 2025  
**Status:** 🚧 PLANNED  
**Branch:** haponBranch

---

## 📋 OVERVIEW

**Feature Description:**

-   Allow admin to import Leave Credits data from Excel files (Leave Card format)
-   System automatically extracts monthly earned credits (Vacation & Sick Leave) from Excel
-   Store historical leave credits per employee with monthly granularity
-   Display leave credits history and current balance to employees
-   Auto-calculate available balance when applying for leave

**Business Need:**

-   Manual entry of leave credits is time-consuming and error-prone
-   Need to maintain historical records of earned credits per month/year
-   Employees need visibility of their leave credit accumulation
-   System should validate sufficient credits before leave approval

---

## 🎯 OBJECTIVES

1. **Admin**: Easy bulk import of leave credits from existing Excel Leave Cards
2. **System**: Parse Excel files and match employee data automatically
3. **Database**: Store monthly earned credits with year-month granularity
4. **Employee**: View leave credits history and current balance
5. **Integration**: Auto-calculate balance in leave application forms

---

## 🏗️ PROPOSED ARCHITECTURE

### Database Structure

```
New Table: leave_credits_history
├── id (PK)
├── employeeid (FK → employees.id)
├── period_year (INT: 2024, 2025, etc)
├── period_month (INT: 1-12)
├── vacation_earned (DECIMAL 10,2)
├── sick_earned (DECIMAL 10,2)
├── vacation_balance (DECIMAL 10,2) [optional]
├── sick_balance (DECIMAL 10,2) [optional]
├── imported_by (FK → users.id)
├── imported_at (TIMESTAMP)
├── created_at
└── updated_at

UNIQUE KEY: (employeeid, period_year, period_month)
```

### Application Flow

```
ADMIN PANEL
    ↓
[Upload Excel File]
    ↓
[Parse & Validate]
    ↓
[Preview Import Data]
    ↓
[Confirm & Save to Database]
    ↓
EMPLOYEE VIEW
    ↓
[Display Credits History]
    ↓
LEAVE APPLICATION
    ↓
[Auto-calculate Balance]
```

---

## 📦 FEATURES

### Phase 1: Excel Import (Admin)

-   [ ] Install Laravel Excel package (maatwebsite/excel)
-   [ ] Create `leave_credits_history` table migration
-   [ ] Build admin UI for file upload
-   [ ] Create Excel import class to parse Leave Card format
-   [ ] Implement employee name matching logic
-   [ ] Add data validation (duplicate check, numeric validation)
-   [ ] Show preview before import
-   [ ] Save validated data to database

### Phase 2: Employee View

-   [ ] Create leave credits history page
-   [ ] Display current balance (sum of earned - used)
-   [ ] Show monthly history table with filters
-   [ ] Add export to PDF option

### Phase 3: Integration

-   [ ] Update leave application form to show current balance
-   [ ] Auto-calculate remaining balance after application
-   [ ] Add validation: insufficient credits warning
-   [ ] Update leave edit modal (Approver 1) to show credits from history

---

## 📄 EXCEL FILE FORMAT

Based on existing Leave Card (1990 Format):

| Column | Field                   | Description                         |
| ------ | ----------------------- | ----------------------------------- |
| A      | Period                  | Month name (January, February, etc) |
| B      | Balance Brought Forward | Previous month balance              |
| C      | Vacation EARNED         | Monthly earned (usually 1.25)       |
| D-F    | Vacation Usage          | Absence w/ Pay, w/out Pay, Balance  |
| J      | Sick EARNED             | Monthly earned (usually 1.25)       |
| K-L    | Sick Usage              | Absence w/ Pay, Balance             |

**Employee Identification:**

-   Name in header: "TRASGA, VON ERIKA C."
-   Position: "ADMINISTRATIVE OFFICER IV (HRMO II)"
-   Date of Birth: "April 17, 1994"

---

## 🔍 IMPLEMENTATION QUESTIONS

### 1. Employee Matching Strategy

**Options:**

-   A. Match by exact name (lastname, firstname)
-   B. Admin manually selects employee before upload
-   C. Auto-detect with confidence score + manual review

**Decision:** TBD

### 2. Upload Scope

**Options:**

-   A. One Excel file = One employee (single leave card)
-   B. One Excel file = Multiple employees (multiple sheets)

**Decision:** TBD

### 3. Update Frequency

**Expected usage:**

-   One-time bulk import (historical data)
-   Monthly updates (new earned credits)
-   Quarterly reviews

**Decision:** TBD

### 4. Integration with Existing Leave Data

**Current State:**

-   `leaves` table has `vacation_earned`, `sick_earned`, `vacation_balance`, `sick_balance`
-   These are set during leave application/approval

**Options:**

-   Replace with computed values from `leave_credits_history`
-   Keep separate (history vs current request)

**Decision:** TBD

---

## 📊 EXPECTED BENEFITS

✅ **Time Savings**: Bulk import vs manual entry  
✅ **Accuracy**: Reduced human error in data entry  
✅ **Transparency**: Employees see their credits history  
✅ **Validation**: System prevents over-booking of leave  
✅ **Audit Trail**: Track when/who imported credits  
✅ **Historical Data**: Maintain monthly records for reporting

---

## 🚀 NEXT STEPS

1. Get approval on architecture and flow
2. Answer implementation questions
3. Install required packages
4. Create database migration
5. Build admin upload UI
6. Implement Excel parser
7. Add employee view
8. Integrate with leave application
9. Testing and validation
10. Deploy to production

---

## 📝 NOTES

-   Feature requested on December 22, 2025
-   Based on existing Leave Card Excel format (1990 format)
-   Sample data reviewed: Von Erika Trasga's Leave Card (2015-2025)
-   Current balance as of Dec 2024: VL=62.515, SL=104.75

---

**Status:** Awaiting user feedback on implementation questions before proceeding with development.
