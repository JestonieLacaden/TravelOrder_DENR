# Memorandum Management System - Complete Documentation

## Overview

A comprehensive Laravel-based system for creating, managing, and distributing official DENR memorandums with auto-formatting, template versioning, and document generation capabilities.

---

## Features Implemented

### ✅ Core Functionality

1. **User Input Form**
    - Date selection
    - Recipient Type (FOR / TO / BOTH)
    - Multiple recipient selection
    - FROM user (auto-filled, editable)
    - Subject input with auto-suggestions
    - Multi-paragraph body editor
    - E-signature option
    - Template version selection

2. **Body Paragraph Handling**
    - Each Enter key creates a new paragraph
    - Auto-justified alignment
    - First-line indentation (0.5 inch / 36pt)
    - System-controlled formatting

3. **Auto-Formatting Rules**
    - Official DENR header (4 lines)
    - Date (top-right aligned)
    - Centered MEMORANDUM title
    - FOR/TO/FROM/SUBJECT sections
    - Justified body paragraphs with indent
    - Signature section (right-aligned)

4. **Template Versioning**
    - Multiple template support
    - Layout configuration (margins, fonts, spacing)
    - Only ONE active template at a time
    - Historical templates preserved
    - Version-locked memorandums

5. **Preview Mode**
    - Real-time preview before generation
    - Exact rendering of final output
    - Print-friendly format
    - Updates status to "Previewed"

6. **Auto-Subject Suggestions**
    - Keyword-based analysis
    - Top 3 suggestions displayed
    - Professional memorandum subjects
    - Click-to-select functionality
    - Dynamic updates on body change

7. **Signature Handling**
    - E-signature insertion (if enabled)
    - Wet signature space (if disabled)
    - User name and position display

8. **Output Generation**
    - DOCX file (editable, Microsoft Word format)
    - PDF file (final, print-ready)
    - Files stored in `storage/app/public/memorandums/`
    - Downloadable via secure routes

9. **Forwarding Feature**
    - Select multiple recipients
    - Add optional message
    - Internal notification system
    - Read/unread tracking
    - Forwarding history display

10. **Data Persistence & Audit Trail**
    - Complete memorandum history
    - Status tracking (Draft → Previewed → Generated → Forwarded → Signed)
    - Template version locked per memorandum
    - Creator and timestamps
    - Soft deletes support

---

## Installation & Setup

### 1. Database Setup

All migrations have been created and run:

```bash
php artisan migrate
```

### 2. Default Templates

Seed the default DENR template:

```bash
php artisan db:seed --class=MemorandumTemplateSeeder
```

### 3. Required Packages

Already installed:

- `phpoffice/phpword` (v1.3) - DOCX generation
- `barryvdh/laravel-dompdf` (v2.2) - PDF generation

### 4. Storage Symlink

Create public storage link:

```bash
php artisan storage:link
```

---

## File Structure

### Controllers

- `app/Http/Controllers/MemorandumController.php` - Main CRUD and operations

### Models

- `app/Models/Memorandum.php`
- `app/Models/MemorandumTemplate.php`
- `app/Models/MemorandumForward.php`

### Services

- `app/Services/MemorandumSubjectSuggestionService.php` - Auto-suggest subjects
- `app/Services/MemorandumDocumentGeneratorService.php` - DOCX/PDF generation

### Views

- `resources/views/memorandums/index.blade.php` - List all memorandums
- `resources/views/memorandums/create.blade.php` - Create form
- `resources/views/memorandums/edit.blade.php` - Edit form
- `resources/views/memorandums/show.blade.php` - View details
- `resources/views/memorandums/preview.blade.php` - Preview memorandum
- `resources/views/memorandums/pdf_template.blade.php` - PDF template

### Migrations

- `2026_01_26_132350_create_memorandum_templates_table.php`
- `2026_01_26_132404_create_memorandums_table.php`
- `2026_01_26_132414_create_memorandum_forwards_table.php`

### Seeders

- `database/seeders/MemorandumTemplateSeeder.php`

---

## Routes

All routes are under `memorandums.*` namespace and require authentication:

| Method | URI                               | Name                         | Description              |
| ------ | --------------------------------- | ---------------------------- | ------------------------ |
| GET    | `/memorandums`                    | memorandums.index            | List all memorandums     |
| GET    | `/memorandums/create`             | memorandums.create           | Show create form         |
| POST   | `/memorandums`                    | memorandums.store            | Store new memorandum     |
| GET    | `/memorandums/{id}`               | memorandums.show             | View memorandum details  |
| GET    | `/memorandums/{id}/edit`          | memorandums.edit             | Show edit form           |
| PUT    | `/memorandums/{id}`               | memorandums.update           | Update memorandum        |
| DELETE | `/memorandums/{id}`               | memorandums.destroy          | Delete memorandum        |
| GET    | `/memorandums/{id}/preview`       | memorandums.preview          | Preview memorandum       |
| POST   | `/memorandums/{id}/generate`      | memorandums.generate         | Generate DOCX/PDF        |
| POST   | `/memorandums/{id}/forward`       | memorandums.forward          | Forward to users         |
| GET    | `/memorandums/{id}/download-docx` | memorandums.download-docx    | Download DOCX file       |
| GET    | `/memorandums/{id}/download-pdf`  | memorandums.download-pdf     | Download PDF file        |
| POST   | `/memorandums/suggest-subjects`   | memorandums.suggest-subjects | AJAX subject suggestions |

---

## Usage Guide

### Creating a Memorandum

1. Navigate to `/memorandums/create`
2. Fill in required fields:
    - **Date**: Select memorandum date
    - **Recipient Type**: Choose FOR, TO, or BOTH
    - **Recipients**: Select users or type positions
    - **FROM**: Auto-filled with logged-in user (editable)
    - **SUBJECT**: Type manually or select from auto-suggestions
    - **Body**: Write content (each Enter = new paragraph)
    - **E-Signature**: Check to use stored signature
3. Click "Save as Draft"

### Previewing

1. Open memorandum from list
2. Click "Preview" button
3. Review exact formatting
4. Print or return to edit

### Generating Files

1. Open memorandum (status: Draft or Previewed)
2. Click "Generate Files"
3. System creates DOCX and PDF
4. Status changes to "Generated"
5. Download buttons appear

### Forwarding

1. Open memorandum (status: Generated or Previewed)
2. Click "Forward" button
3. Select recipients from list
4. Add optional message
5. Click "Forward"
6. Status changes to "Forwarded"

### Editing

- Only **Draft** memorandums can be edited
- Generated/Forwarded memorandums are locked
- Edit button appears only for drafts

---

## Template Configuration

### Default Template Structure

```php
[
    'name' => 'DENR PENRO Occidental Mindoro - Official Memorandum',
    'version' => '1.0',
    'layout_config' => [
        'margin_top' => 1,          // inches
        'margin_right' => 1,
        'margin_bottom' => 1,
        'margin_left' => 1,
        'font_family' => 'Times New Roman',
        'font_size' => 12,          // points
        'line_height' => 1.5,
        'paragraph_spacing' => 12,
        'first_line_indent' => 0.5, // inches
    ],
    'header_line_1' => 'Republic of the Philippines',
    'header_line_2' => 'Department of Environment and Natural Resources',
    'header_line_3' => 'Provincial Environment and Natural Resources Office',
    'header_line_4' => 'Occidental Mindoro',
    'is_active' => true,
]
```

### Adding New Templates

1. Create via database seeder or admin interface
2. Set `is_active => false` for non-active templates
3. Only ONE template can be active at a time
4. Old memorandums retain their original template version

---

## Auto-Subject Suggestion Keywords

The system analyzes body content for these keywords:

| Keyword        | Suggested Subject                   |
| -------------- | ----------------------------------- |
| budget         | Justification on Budget Utilization |
| approval       | Request for Approval                |
| procurement    | Status Update on Procurement        |
| travel         | Travel Order Authorization          |
| leave          | Leave Application                   |
| meeting        | Meeting Notice and Agenda           |
| training       | Training Program Notification       |
| report         | Submission of Report                |
| request        | Request for Assistance              |
| update         | Status Update                       |
| implementation | Implementation Guidelines           |
| compliance     | Compliance Reminder                 |
| directive      | Directive on Policy Implementation  |
| reminder       | Reminder on Compliance              |
| information    | Information Dissemination           |
| evaluation     | Performance Evaluation Report       |
| inspection     | Inspection Schedule                 |
| monitoring     | Monitoring and Evaluation Report    |

---

## Status Flow

```
Draft → Previewed → Generated → Forwarded → Signed
  ↓        ↓          ↓            ↓
 Edit   Preview   Download     Forward
```

- **Draft**: Initial creation, editable
- **Previewed**: User has previewed, still editable
- **Generated**: DOCX/PDF files created, locked
- **Forwarded**: Sent to other users, locked
- **Signed**: Final signed version (future enhancement)

---

## Database Schema

### `memorandum_templates`

- id, name, version, description
- layout_config (JSON)
- header_line_1 to header_line_4
- footer_template
- is_active, created_by
- timestamps, soft deletes

### `memorandums`

- id, template_id, memorandum_date, memorandum_number
- recipient_type, recipient_for (JSON), recipient_to (JSON)
- from_user_id, from_name, from_position
- subject, body
- use_esignature, signature_path
- status, created_by, generated_at, signed_at
- docx_path, pdf_path
- timestamps, soft deletes

### `memorandum_forwards`

- id, memorandum_id, forwarded_by, forwarded_to
- message, is_read, read_at
- timestamps

---

## Security Features

1. **Authentication Required**: All routes protected
2. **Authorization**: Only creator can edit/delete
3. **Status Locking**: Generated/Forwarded memorandums immutable
4. **File Storage**: Files in protected storage directory
5. **Soft Deletes**: Data recovery possible
6. **Template Version Lock**: Each memorandum preserves its template

---

## Testing Checklist

### ✅ Basic Operations

- [ ] Create new memorandum
- [ ] Edit draft memorandum
- [ ] View memorandum details
- [ ] Delete memorandum
- [ ] List all memorandums with pagination

### ✅ Formatting

- [ ] Body paragraphs are justified
- [ ] First-line indentation works
- [ ] Header displays correctly
- [ ] Date is right-aligned
- [ ] MEMORANDUM title is centered
- [ ] Signature section is right-aligned

### ✅ Preview & Generation

- [ ] Preview displays exact formatting
- [ ] Preview is print-friendly
- [ ] DOCX file is generated correctly
- [ ] PDF file is generated correctly
- [ ] Files are downloadable

### ✅ Auto-Suggestions

- [ ] Subject suggestions appear after typing
- [ ] Suggestions are relevant to body content
- [ ] Click-to-select works
- [ ] Suggestions update dynamically

### ✅ Forwarding

- [ ] Can select multiple recipients
- [ ] Optional message is included
- [ ] Forwarding history displays
- [ ] Status updates to "Forwarded"

### ✅ Template Versioning

- [ ] Multiple templates exist
- [ ] Only one is active
- [ ] Old memorandums use their locked template
- [ ] New memorandums use active template

### ✅ Status Flow

- [ ] Draft → Previewed transition works
- [ ] Previewed → Generated transition works
- [ ] Generated → Forwarded transition works
- [ ] Only drafts are editable

---

## Future Enhancements (Optional)

1. **Digital Signature Integration**
    - Integrate with e-signature providers
    - Capture wet signatures via tablet

2. **Notification System**
    - Email notifications on forward
    - In-app notifications
    - SMS alerts

3. **Advanced Search**
    - Search by date range
    - Search by subject/content
    - Filter by status

4. **Approval Workflow**
    - Multi-level approvals
    - Approval routing
    - Comments/feedback

5. **Analytics Dashboard**
    - Memorandum statistics
    - Most used subjects
    - User activity

6. **Template Management UI**
    - Admin interface for templates
    - Visual template editor
    - Preview before activation

7. **Bulk Operations**
    - Bulk forward
    - Bulk download
    - Bulk status updates

---

## Troubleshooting

### Issue: DOCX generation fails

**Solution**: Ensure `ext-gd` and `ext-zip` PHP extensions are enabled

### Issue: PDF has formatting issues

**Solution**: Check `config/dompdf.php` settings, verify font paths

### Issue: Auto-suggestions not working

**Solution**: Check AJAX route in JavaScript, ensure CSRF token is included

### Issue: Files not downloadable

**Solution**: Run `php artisan storage:link` to create symlink

### Issue: Template not showing in dropdown

**Solution**: Ensure at least one template exists via seeder

---

## Support & Contact

For questions or issues:

1. Check this documentation
2. Review code comments
3. Test with sample data
4. Contact development team

---

## Credits

**Developed By**: Travel Order System Team  
**Date**: January 26, 2026  
**Laravel Version**: 8.x  
**PHP Version**: 7.3+ / 8.0+

---

## License

Internal use only - DENR PENRO Occidental Mindoro
