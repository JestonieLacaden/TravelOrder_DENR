# 📋 Memorandum System - Complete Features Documentation

> **System Version:** January 2026
> **Framework:** Laravel 8.x
> **Department:** DENR - Provincial Environment and Natural Resources Office

---

## 📑 Table of Contents

1. [System Overview](#system-overview)
2. [Core Features](#core-features)
3. [Workflow Management](#workflow-management)
4. [Attachments System](#attachments-system)
5. [User Interface Components](#user-interface-components)
6. [Document Management](#document-management)
7. [Notification System](#notification-system)
8. [Permission & Access Control](#permission--access-control)
9. [Technical Routes](#technical-routes)

---

## 🎯 System Overview

The Memorandum Management System is a comprehensive Laravel-based application designed to streamline the creation, review, approval, and distribution of official government memorandums within DENR. It features automated formatting, parallel/sequential review workflows, real-time notifications, and document generation capabilities.

### Key Objectives:

- ✅ Standardize memorandum formatting according to DENR guidelines
- ✅ Enable collaborative review and approval processes
- ✅ Track complete document history and changes
- ✅ Facilitate efficient communication between offices
- ✅ Generate print-ready documents (PDF & DOCX)

---

## 🚀 Core Features

### 1. **Memorandum Creation & Editing**

#### Create New Memorandum

- **Location:** My Memoranda page
- **Access:** All authenticated users
- **Fields:**
    - Date selection (auto-filled with current date)
    - Recipient Type: FOR / TO / BOTH
    - Multiple recipient selection (users, offices, or preset groups)
    - FROM field (auto-filled with creator, editable)
    - Subject (textarea with 2 rows, supports multi-line)
    - Body content (rich text editor with Summernote)
    - E-signature option toggle
    - Template selection (DENR official format)

#### Edit Memorandum

- **Permission:** Owner can edit in Draft status OR reviewers with edit permission
- **Change Tracking:** System stores old_content as JSON before each reviewer edit
- **Restrictions:** Cannot edit after document is approved/finalized

#### Subject Field

- **Type:** Textarea (2 rows)
- **Purpose:** Allows longer, multi-line subjects for comprehensive memorandum titles
- **Auto-suggestions:** Smart AI-powered subject recommendations based on body content (top 3 suggestions)

#### Body Editor (Summernote)

- Rich text formatting (bold, italic, underline)
- Paragraph handling (Enter key creates new paragraph)
- Lists (ordered, unordered)
- Auto-justified alignment
- First-line indentation (0.5 inch / 36pt)
- Font size and style controls

---

### 2. **Document Status Management**

The system tracks memorandums through multiple statuses:

| Status         | Description                        | Available Actions                                   |
| -------------- | ---------------------------------- | --------------------------------------------------- |
| **Draft**      | Initial creation state             | Edit, Delete, Submit for Review                     |
| **For Review** | Sent to reviewers                  | View, Comment, Approve, Return, Edit (if permitted) |
| **Returned**   | Sent back to creator with comments | Revise, View Comments                               |
| **Revised**    | Updated after return               | Re-submit for Review                                |
| **Approved**   | All reviewers approved             | Generate, Download, Forward                         |
| **Generated**  | PDF/DOCX created                   | Download, Forward                                   |

#### Status Badges Display:

- **Draft** - Gray badge (secondary)
- **For Review** - Yellow badge (warning) with exclamation icon
- **Returned** - Red badge (danger) with undo icon
- **Approved** - Green badge (success) with check icon
- **Generated** - Blue badge (info) with file icon

---

### 3. **Preview & Document Generation**

#### Preview Mode

- **Route:** `/memorandums/{id}/preview`
- **Purpose:** View exact rendering before generating final document
- **Features:**
    - A4 paper size simulation
    - Official DENR header (centered, with left/right logos)
    - Proper formatting of all sections
    - Signature section display
    - Print-friendly layout
- **Header Layout:**
    - Left logo (90px, left-aligned)
    - Center text (Republic, Department name, Office details)
    - Right logo (110px, right-aligned)
    - White-space: nowrap on header text to prevent wrapping

#### Export PDF

- **Route:** `/memorandums/{id}/export-pdf`
- **Format:** PDF (read-only, print-ready)
- **Styling:** Inline CSS with @page A4 margins
- **Header:** No-wrap text formatting applied

#### Download Options

- **DOCX:** Editable Microsoft Word format (`/memorandums/{id}/download-docx`)
- **PDF:** Final print-ready format (`/memorandums/{id}/download-pdf`)
- **Storage:** `storage/app/public/memorandums/`

---

## 🔄 Workflow Management

### 4. **Review & Approval System**

#### Parallel Review Support

- Multiple reviewers can be assigned simultaneously
- Each reviewer works independently
- System tracks individual reviewer actions
- No "Current Holder" concept (all reviewers see memorandum at once)

#### Workflow History Tracking

Each action is recorded in `memorandum_workflow_history` table:

| Field           | Description                                            |
| --------------- | ------------------------------------------------------ |
| `memorandum_id` | Reference to memorandum                                |
| `from_user_id`  | User who performed action                              |
| `to_user_id`    | Target user (for forwarding)                           |
| `action`        | Type: forwarded, received, approved, returned, revised |
| `comments`      | Optional message/feedback                              |
| `can_edit`      | Boolean flag for edit permission                       |
| `old_content`   | JSON snapshot of content before reviewer edit          |
| `created_at`    | Timestamp of action                                    |

#### Available Actions:

**1. Submit for Review** (Owner)

- Send to selected reviewers
- Add optional message
- Updates status to "For Review"
- Creates workflow_history entries with action='forwarded'
- Sends notifications to all reviewers

**2. Mark as Received** (Reviewer)

- Acknowledges receipt of memorandum
- Creates workflow_history with action='received'
- Shows "Received" badge in inbox
- Notifies creator
- Dropdown: "Action Required" → "Mark as Received"

**3. Approve & Return to Sender** (Reviewer)

- Quick approval without forwarding to others
- Creates workflow_history with action='approved', to_user_id=creator
- Notifies creator
- Hides action buttons for that reviewer
- Dropdown: "Action Required" → "Approve & Return to Sender"

**4. Approve and Forward** (Reviewer)

- Approve and send to next reviewers
- Select multiple next-level reviewers
- Add comments
- Updates workflow_history with action='approved'
- Forwards with action='forwarded'

**5. Return with Comments** (Reviewer)

- Send back to creator for revisions
- Requires comment/feedback
- Updates status to "Returned"
- Notifies creator

**6. Revise After Return** (Owner)

- Update content based on reviewer feedback
- Re-submit for review
- Creates workflow_history with action='revised'

#### Reviewer Edit Feature

- Reviewers can request edit permission
- System tracks changes with before/after comparison
- Old content stored as JSON in `old_content` field
- Modal displays comparison view:
    - **Before Edit:** Original content (read-only)
    - **After Edit:** Reviewer's changes (read-only)
    - Side-by-side comparison

#### Filtered Workflow History

- Reviewers only see their own workflow entries
- Owner sees all workflow history
- Prevents confusion in parallel review scenarios
- Icons for each action:
    - Forwarded: paper plane
    - Received: inbox
    - Approved: check circle
    - Returned: undo
    - Revised: edit

---

### 5. **Action Button Visibility Logic**

The system intelligently shows/hides action buttons based on user role and status:

#### PHP Logic (show.blade.php):

```php
$isOwner = $memorandum->created_by === Auth::id();
$isReviewer = MemorandumWorkflowHistory::where('memorandum_id', $memorandum->id)
    ->where('to_user_id', Auth::id())
    ->where('action', 'forwarded')
    ->exists();

$reviewerAction = $memorandum->workflowHistory()
    ->where('from_user_id', Auth::id())
    ->whereIn('action', ['received', 'approved', 'returned', 'revised'])
    ->orderBy('created_at', 'desc')
    ->first();

$reviewerHasCompleted = $reviewerAction !== null;
$hasPendingAction = $isReviewer && !$reviewerHasCompleted && $memorandum->status === 'For Review';
```

#### Button Display Rules:

| Button                       | Condition                                                          |
| ---------------------------- | ------------------------------------------------------------------ |
| **Action Required Dropdown** | `$hasPendingAction = true` (reviewer hasn't acted yet)             |
| **Edit Memorandum**          | `$isOwner && status=Draft` OR `$hasPendingAction && can_edit=true` |
| **Add Comment**              | `$isOwner` OR `!$reviewerHasCompleted && $isReviewer`              |
| **Delete Memorandum**        | `$isOwner` only                                                    |
| **Submit for Review**        | `$isOwner && status=Draft`                                         |
| **Revise**                   | `$isOwner && status=Returned`                                      |

#### Action Dropdown Contents:

1. **Mark as Received** - Acknowledges receipt
2. **Approve & Return to Sender** - Quick approval back to creator

---

## 📥 Inbox & My Memoranda

### 6. **Inbox System**

#### Purpose

Display memorandums where user is a reviewer

#### Query Logic:

```php
MemorandumWorkflowHistory::where('to_user_id', Auth::id())
    ->whereIn('action', ['received', 'approved', 'returned', 'revised', 'forwarded'])
    ->with('memorandum')
    ->orderBy('created_at', 'desc')
```

#### Features:

- Status badge indicators
- Filter dropdown:
    - All Actions
    - Forwarded
    - Received
    - Approved
    - Returned
    - Revised
- Pending count badge in sidebar (yellow warning badge)
- Quick access to memorandum details
- Action status display (From: user, Action: type, Date: timestamp)

#### Status Badges in Inbox:

- **Forwarded:** Blue badge with paper-plane icon
- **Received:** Info badge with inbox icon
- **Approved:** Success badge with check-circle icon
- **Returned:** Danger badge with undo icon
- **Revised:** Warning badge with edit icon

#### Pending Count Calculation:

```php
$pendingCount = MemorandumWorkflowHistory::where('to_user_id', Auth::id())
    ->where('action', 'forwarded')
    ->whereHas('memorandum', function($q) {
        $q->where('status', 'For Review');
    })
    ->whereDoesntHave('memorandum.workflowHistory', function($q) {
        $q->where('from_user_id', Auth::id())
          ->whereIn('action', ['received', 'approved', 'returned', 'revised']);
    })
    ->count();
```

---

### 7. **My Memoranda**

#### Purpose

Display memorandums created by the current user

#### Query:

```php
Memorandum::where('created_by', Auth::id())
    ->orderBy('created_at', 'desc')
    ->paginate(15);
```

#### Features:

- Status badge display (Draft, For Review, Returned, Approved)
- Create New button (in page header)
- Quick actions: View, Edit (if Draft), Delete
- Date and recipient information
- Pagination (15 per page)

#### Status Badges Display:

The system shows colored badges indicating the current state of each memorandum:

- **Draft** - `badge-secondary` (gray)
- **For Review** - `badge-badge-warning` (yellow) with exclamation icon
- **Returned** - `badge-danger` (red) with undo icon
- **Approved** - `badge-success` (green) with check icon

---

## 🔔 Notification System

### 8. **Real-Time Notifications**

#### Notification Triggers:

| Event                | Trigger              | Recipients               | Type                   |
| -------------------- | -------------------- | ------------------------ | ---------------------- |
| Memorandum Created   | store()              | None                     | -                      |
| Submitted for Review | submitForReview()    | All reviewers            | `submitted_for_review` |
| Forwarded            | forward()            | Selected users           | `forwarded`            |
| Approved             | approveAndForward()  | Next reviewers + creator | `approved`             |
| Returned             | returnWithComments() | Creator                  | `returned`             |
| Revised              | reviseAfterReturn()  | Previous reviewers       | `revised`              |
| Comment Added        | addComment()         | All participants         | `comment_added`        |
| Marked Received      | markReceived()       | Creator                  | `received`             |
| Reviewer Approved    | approveReviewer()    | Creator                  | `approved`             |

#### Notification Display:

- **Location:** Top navigation bar (bell icon)
- **Badge:** Red counter badge (shows unread count, max "99+")
- **Dropdown:** Latest 10 notifications with:
    - Icon (based on type)
    - Title (bold)
    - Message
    - Timestamp
    - Read/unread indicator (gray background for unread)

#### Notification Icons:

```javascript
{
    'submitted_for_review': '<i class="fas fa-paper-plane text-info"></i>',
    'forwarded': '<i class="fas fa-share text-primary"></i>',
    'approved': '<i class="fas fa-check-circle text-success"></i>',
    'returned': '<i class="fas fa-undo text-warning"></i>',
    'revised': '<i class="fas fa-edit text-info"></i>',
    'comment_added': '<i class="fas fa-comment text-primary"></i>',
    'received': '<i class="fas fa-inbox text-info"></i>'
}
```

#### Notification Actions:

- Click notification → Navigate to memorandum → Mark as read
- "Mark All as Read" button
- "See All Notifications" link → `/memorandums/notifications/all`

#### AJAX Polling:

- Checks every 15 seconds for new notifications
- Route: `GET /memorandums/notifications/get`
- Returns JSON with unread count and latest notifications
- Updates badge and dropdown dynamically

---

## � Attachments System

### 9. **Memorandum Attachments (Supporting Documents)**

The Attachments feature allows users to upload supporting documents (PDF, Images, DOCX) related to a memorandum without affecting the memorandum's body or formatting.

#### Purpose & Use Cases:

- Supporting documents (justifications, references)
- Scanned approvals or evidence
- Photos and supplementary materials
- Reference documents for reviewers

#### Where Attachments Can Be Added:

**A. Create Memorandum Page:**

- Optional file upload field during creation
- Supports multiple files
- Attachments saved when memorandum is created

**B. Memorandum Details Page (Main Area):**

- Dedicated "Attachments" section with upload form
- Add new attachments after creation
- Upload button visible based on status and permissions

**NOT included in:**

- Edit Memorandum form (to avoid confusion)
- Preview or PDF generation

#### File Type & Size Restrictions:

- **Allowed Types:** PDF (.pdf), Images (.jpg, .jpeg, .png), Word documents (.docx)
- **Max Size:** 10MB per file
- **Multiple Files:** Yes, unlimited attachments
- **Validation:** MIME type and extension checked
- **Security:** Files stored in secure `storage/app/local` (not public)

#### Status-Based Rules:

**Attachments CAN be added/removed when status is:**

- Draft
- For Review
- Returned
- Revised
- Forwarded

**Attachments CANNOT be added/removed when status is:**

- Approved
- Generated
- Signed (final)

Once approved, attachments become **READ-ONLY** with no upload/delete buttons visible.

#### Role-Based Permissions:

**Creator (Owner):**

- ✅ Upload new attachments (if status allows)
- ✅ Remove attachments (if status allows)
- ✅ View, preview, and download all attachments

**Reviewer:**

- ✅ View all attachments
- ✅ Preview attachments (PDF & images)
- ✅ Download attachments
- ❌ Cannot upload attachments
- ❌ Cannot remove attachments
- 💬 Can request attachments via comments

#### Reviewer Use-Case Example:

1. Creator submits memorandum without attachments
2. Reviewer adds comment: _"Please attach supporting documents"_
3. Creator uploads attachment via Memorandum Details page
4. Reviewer can immediately see and download the attachment
5. **No re-forwarding required** - attachment visibility is instant

#### Attachment Display:

Each attachment shows:

- File type icon (PDF, Image, DOCX with color)
- Original filename
- File size (formatted: KB/MB)
- Uploaded by (user name)
- Upload date and time

**Action Buttons (per attachment):**

- 👁️ **Preview** - PDF & images inline view (opens in new tab)
- ⬇️ **Download** - All file types (browser download)
- 🗑️ **Delete** - Creator only, if status allows

#### Preview Behavior:

- **PDF & Images:** Inline preview in browser (new tab)
- **DOCX:** Download only (cannot preview in browser)
- Attachments remain accessible even after approval

#### Database Structure:

**Table:** `memorandum_attachments`

| Column              | Type        | Description                    |
| ------------------- | ----------- | ------------------------------ |
| `id`                | bigint      | Primary key                    |
| `memorandum_id`     | bigint      | Foreign key to memorandums     |
| `original_filename` | varchar     | Original uploaded filename     |
| `stored_path`       | varchar     | Storage path (local disk)      |
| `file_type`         | varchar(50) | Extension: pdf, jpg, png, docx |
| `file_size`         | bigint      | File size in bytes             |
| `uploaded_by`       | bigint      | Foreign key to users           |
| `created_at`        | timestamp   | Upload timestamp               |
| `updated_at`        | timestamp   | Last update                    |
| `deleted_at`        | timestamp   | Soft delete (nullable)         |

#### Notifications:

**Attachment Uploaded:**

- Triggers notification to all current reviewers
- Type: `attachment_uploaded`
- Message: _"[User] added a new attachment to: [Subject]"_

**Attachment Removed:**

- Triggers notification to all current reviewers
- Type: `attachment_removed`
- Message: _"[User] removed an attachment from: [Subject]"_

#### Audit & Logging:

- Upload action logged with user ID and timestamp
- Delete action logged (soft delete preserved)
- Attachment actions DO NOT change memorandum status
- Attachment actions DO NOT affect workflow history

#### UI/UX Features:

- File input with custom label showing selected filename
- Multiple file count display: _"3 file(s) selected"_
- Confirmation prompt before deleting attachment
- Read-only indicator for approved memorandums
- Empty state: _"No attachments yet"_ with info icon

#### Model Methods & Helpers:

**MemorandumAttachment Model:**

```php
// Relationships
memorandum() - belongsTo Memorandum
uploader() - belongsTo User

// Attributes
formatted_file_size - Human-readable size (KB/MB)
file_icon - Font Awesome icon class based on type
isPreviewable() - Returns true for PDF/images
```

#### Routes:

- `POST /memorandums/{id}/attachments/upload` - Upload attachment
- `DELETE /memorandums/{memorandumId}/attachments/{attachmentId}` - Delete attachment
- `GET /memorandums/{memorandumId}/attachments/{attachmentId}/download` - Download
- `GET /memorandums/{memorandumId}/attachments/{attachmentId}/preview` - Preview

---

## 💬 Comments System

### 10. **Commenting Feature**

#### Add Comment:

- **Route:** `POST /memorandums/{id}/add-comment`
- **Fields:** Comment text (required)
- **Access:** Owner or reviewers who haven't completed action
- **Display:** Threaded comments section with:
    - User name and avatar
    - Timestamp
    - Comment content
    - Edit/Delete options (comment owner only)

#### Comment Notifications:

- All memorandum participants receive notification
- Type: `comment_added`
- Links directly to memorandum

---

## 🔐 Permission & Access Control

### 11. **Edit Permissions**

#### Owner Permissions:

- Create new memorandums
- Edit in Draft status
- Delete own memorandums
- Submit for review
- Revise after return
- View all workflow history
- Add comments anytime

#### Reviewer Permissions:

- View assigned memorandums
- Mark as received
- Approve & return to sender
- Approve and forward
- Return with comments
- Request edit permission
- Add comments (before completing action)

#### Edit Permission Toggle:

- Reviewers can request edit access
- Creates workflow entry with `can_edit=true`
- Stores old_content before edit
- System tracks who made changes

#### User Has Edit Permission Check:

```php
$userHasEditPermission = $memorandum->workflowHistory()
    ->where('to_user_id', Auth::id())
    ->where('can_edit', true)
    ->exists();
```

---

## 🗂️ Database Structure

### 12. **Key Tables**

#### memorandums

- `id` - Primary key
- `template_id` - Foreign key to templates
- `created_by` - User ID of creator
- `from_user_id` - FROM field user
- `date` - Memorandum date
- `for_recipients` - JSON array
- `to_recipients` - JSON array
- `subject` - Subject text (multi-line)
- `body` - HTML content
- `status` - Current workflow status
- `e_signature_enabled` - Boolean
- `docx_path` - Generated DOCX file path
- `pdf_path` - Generated PDF file path
- `timestamps`

#### memorandum_workflow_history

- `id` - Primary key
- `memorandum_id` - Foreign key
- `from_user_id` - Action performer
- `to_user_id` - Target user
- `action` - Action type (forwarded, received, approved, returned, revised)
- `comments` - Optional message
- `can_edit` - Edit permission flag
- `old_content` - JSON snapshot before edit
- `created_at` - Timestamp

#### memorandum_notifications

- `id` - Primary key
- `memorandum_id` - Foreign key
- `user_id` - Recipient user
- `type` - Notification type
- `title` - Notification title
- `message` - Notification message
- `is_read` - Boolean read status
- `timestamps`

#### memorandum_comments

- `id` - Primary key
- `memorandum_id` - Foreign key
- `user_id` - Comment author
- `comment` - Comment text
- `timestamps`

#### memorandum_templates

- `id` - Primary key
- `name` - Template name
- `layout_config` - JSON (margins, fonts, spacing)
- `is_active` - Boolean (only one active)
- `timestamps`

#### memorandum_recipient_presets

- `id` - Primary key
- `name` - Preset name (e.g., "All Section Chiefs")
- `users` - JSON array of user IDs
- `timestamps`

#### memorandum_attachments

- `id` - Primary key
- `memorandum_id` - Foreign key to memorandums
- `original_filename` - Original uploaded filename
- `stored_path` - Storage path in local disk
- `file_type` - File extension (pdf, jpg, png, docx)
- `file_size` - File size in bytes
- `uploaded_by` - Foreign key to users
- `timestamps`
- `deleted_at` - Soft delete timestamp

---

## 🛣️ Technical Routes

### 13. **Complete Route List**

#### Basic CRUD:

- `GET /memorandums` - index (My Memoranda)
- `GET /memorandums/inbox` - inbox (Received memorandums)
- `GET /memorandums/create` - create form
- `POST /memorandums` - store (create new)
- `GET /memorandums/{id}` - show (view details)
- `GET /memorandums/{id}/edit` - edit form
- `PUT /memorandums/{id}` - update
- `DELETE /memorandums/{id}` - destroy

#### Preview & Generation:

- `GET /memorandums/{id}/preview` - preview mode
- `GET /memorandums/{id}/export-pdf` - PDF export

#### Downloads:

- `GET /memorandums/{id}/download-docx` - Download DOCX
- `GET /memorandums/{id}/download-pdf` - Download PDF

#### Workflow Actions:

- `POST /memorandums/{id}/submit-for-review` - Submit to reviewers
- `POST /memorandums/{id}/return-with-comments` - Return for revision
- `POST /memorandums/{id}/approve-and-forward` - Approve & forward
- `POST /memorandums/{id}/revise-after-return` - Revise after return
- `POST /memorandums/{id}/add-comment` - Add comment
- `POST /memorandums/{id}/update-edit-permissions` - Toggle edit permission
- `POST /memorandums/{id}/edit-as-reviewer` - Reviewer edit with tracking
- `POST /memorandums/{id}/mark-received` - Mark as received
- `POST /memorandums/{id}/approve-reviewer` - Approve & return to sender
- `POST /memorandums/{id}/forward` - Forward to users

#### Attachments:

- `POST /memorandums/{id}/attachments/upload` - Upload attachment
- `DELETE /memorandums/{memorandumId}/attachments/{attachmentId}` - Delete attachment
- `GET /memorandums/{memorandumId}/attachments/{attachmentId}/download` - Download attachment
- `GET /memorandums/{memorandumId}/attachments/{attachmentId}/preview` - Preview attachment (PDF/images)

#### Notifications:

- `GET /memorandums/notifications/get` - Get notifications (AJAX)
- `POST /memorandums/notifications/{id}/mark-read` - Mark notification read
- `POST /memorandums/notifications/mark-all-read` - Mark all read
- `GET /memorandums/notifications/all` - All notifications page

#### AJAX:

- `POST /memorandums/suggest-subjects` - AI subject suggestions

---

## 📊 System Statistics & Metrics

### 14. **Dashboard Integration**

Potential metrics that can be displayed:

- Total memorandums created
- Pending actions count (per user)
- Average approval time
- Most active users
- Memorandums by status breakdown
- Recent activity timeline

---

## 🎨 UI Components

### 15. **Sidebar Menu**

#### Memorandum Section:

- **Icon:** fas fa-file-alt
- **Submenu:**
    - My Memoranda (with circle icon)
    - Inbox (with pending badge if > 0)
    - ~~Create New~~ (removed - users create from My Memoranda page)

#### Menu Styling:

- Active state: Blue highlight
- Menu-open: Expanded submenu
- Pending badge: Yellow badge-warning with count

---

## 🔧 Technical Details

### 16. **Key Technologies Used**

- **Backend:** Laravel 8.x (PHP)
- **Frontend:** Blade Templates, Bootstrap 4, jQuery
- **Rich Text:** Summernote Editor
- **PDF Generation:** Barryvdh/DomPDF
- **DOCX Generation:** PhpOffice/PhpWord
- **Icons:** Font Awesome 5
- **Database:** MySQL
- **Session Management:** Laravel Sessions
- **Authentication:** Laravel Auth

### 17. **Key Services**

#### MemorandumSubjectSuggestionService

- Analyzes memorandum body content
- Generates AI-powered subject suggestions
- Returns top 3 relevant subjects
- Used in create/edit forms

#### MemorandumDocumentGeneratorService

- Generates DOCX files
- Generates PDF files
- Applies official DENR formatting
- Stores files in storage/app/public/memorandums/

---

## 📝 User Workflows

### 18. **Common User Scenarios**

#### Scenario 1: Creating and Sending a Memorandum

1. Navigate to "My Memoranda"
2. Click "Create New Memorandum" button
3. Fill in date, recipients, subject, body
4. Toggle e-signature if needed
5. Preview document
6. Submit for review to selected reviewers
7. Wait for notifications

#### Scenario 2: Reviewing a Memorandum

1. Receive notification
2. Navigate to Inbox
3. Click memorandum to view
4. Review content
5. Choose action:
    - Mark as Received (acknowledge)
    - Approve & Return to Sender (simple approval)
    - Approve and Forward (send to next level)
    - Return with Comments (request changes)
6. Add optional comments
7. Submit action

#### Scenario 3: Revising After Return

1. Receive "Returned" notification
2. View reviewer comments
3. Navigate to memorandum
4. Click "Revise" button
5. Make necessary changes
6. Click "Submit Revision"
7. Memorandum returns to reviewers

#### Scenario 4: Tracking Changes

1. View memorandum details
2. Scroll to "Workflow History" section
3. See all actions with timestamps
4. Click "View Changes" on reviewer edits
5. Compare before/after content in modal

#### Scenario 5: Uploading Attachments

1. Create memorandum or navigate to existing memorandum
2. Scroll to "Attachments" section
3. Click "Choose file..." button
4. Select PDF, image, or DOCX file (max 10MB)
5. Click "Upload" button
6. Attachment appears in list with preview/download options
7. Reviewers receive notification about new attachment

#### Scenario 6: Reviewing with Attachments

1. Receive notification about memorandum
2. Navigate to Inbox and click memorandum
3. Scroll to "Attachments" section
4. Click "Preview" icon for PDF/images (opens inline)
5. Click "Download" icon for any file type
6. Review supporting documents
7. Proceed with approval/return decision

---

## 🚨 Important Notes

### 19. **System Limitations & Best Practices**

#### Current Limitations:

- Only one active template at a time
- No bulk operations (mass approve, mass forward)
- No memorandum duplication feature
- No automatic archiving
- Attachments limited to 10MB per file
- DOCX attachments cannot be previewed (download only)

#### Best Practices:

- Always preview before generating final documents
- Add clear comments when returning memorandums
- Use recipient presets for common distribution lists
- Review workflow history before taking action
- Keep subjects concise but descriptive
- Upload relevant attachments early in the process
- Use PDF format for attachments when possible (previewable)
- Verify attachment file size before uploading (max 10MB)

#### Security Considerations:

- Users can only edit their own memorandums (in Draft status)
- Reviewers cannot see other reviewers' workflow entries
- Notifications are user-specific
- File downloads are authenticated
- Attachments stored in secure local storage (not public web)
- Only creators can upload/delete attachments
- File type validation prevents executable uploads

---

## 📚 Related Documentation

- `MEMORANDUM_FEATURE_DOCUMENTATION.md` - Original implementation docs
- `MEMORANDUM_QUICK_START.md` - User quick start guide
- `MEMORANDUM_IMPLEMENTATION_SUMMARY.md` - Developer implementation notes

---

## 🔄 Version History

### Latest Updates (January 2026):

#### UI Improvements:

- ✅ Subject field converted to textarea (2 rows)
- ✅ Preview header logo left-aligned
- ✅ Export PDF header text nowrap applied
- ✅ "Create New" button removed from sidebar (redundant)

#### Feature Additions:

- ✅ "Mark as Received" action for reviewers
- ✅ "Approve & Return to Sender" quick action
- ✅ Received status badge in inbox
- ✅ Filtered workflow history (reviewers see own entries)
- ✅ Change tracking with before/after comparison modal
- ✅ Action button visibility logic based on reviewer status
- ✅ **Attachments System** - Full support for supporting documents (PDF, Images, DOCX)
    - Upload during creation or after
    - Status-based upload/delete restrictions
    - Role-based permissions (owner vs reviewer)
    - Preview for PDF/images, download for all types
    - Notifications when attachments added/removed
    - Secure storage in local disk

#### Bug Fixes:

- ✅ Inbox not updating after "Mark as Received" action
- ✅ Action button persisting after reviewer completes action
- ✅ Missing status badges in My Memoranda
- ✅ Current holder confusion in parallel review

---

## 👥 Support & Maintenance

### Contact Information:

- **System Administrator:** DENR IT Department
- **Technical Support:** Contact your local IT support
- **Feature Requests:** Submit via official channels

### Backup & Recovery:

- Database backups: Daily at midnight
- File backups: Weekly full backup
- Retention period: 1 year

---

## 📖 Glossary

| Term                  | Definition                                                    |
| --------------------- | ------------------------------------------------------------- |
| **Memorandum**        | Official written communication document                       |
| **Workflow History**  | Complete log of all actions taken on a memorandum             |
| **Reviewer**          | User assigned to review/approve a memorandum                  |
| **Owner/Creator**     | User who created the memorandum                               |
| **Parallel Review**   | Multiple reviewers reviewing simultaneously                   |
| **Sequential Review** | Reviewers reviewing in order (one after another)              |
| **E-Signature**       | Digital signature embedded in document                        |
| **Preset**            | Pre-configured list of recipients                             |
| **Forward**           | Send memorandum to other users                                |
| **Return**            | Send memorandum back to creator for revision                  |
| **Attachment**        | Supporting document uploaded to memorandum (PDF, image, DOCX) |

---

**Document Last Updated:** January 29, 2026
**System Version:** 1.0
**Author:** Jestonie (DENR Development Team)
