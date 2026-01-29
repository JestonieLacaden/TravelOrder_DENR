# Memorandum System - Quick Start Guide

## Accessing the System

1. Login to the system
2. Navigate to: **`/memorandums`**
3. Or add link in your navigation menu

---

## Quick Actions

### Create New Memorandum

```
/memorandums/create
```

### View My Memorandums

```
/memorandums
```

---

## Step-by-Step: Create Your First Memorandum

### 1. Click "Create New Memorandum"

### 2. Fill Basic Information

- **Date**: Today's date (auto-filled)
- **Template**: Use default "DENR PENRO Occidental Mindoro" (v1.0)

### 3. Select Recipients

- **Recipient Type**: Choose one:
    - `FOR` - Official routing
    - `TO` - Direct recipients
    - `BOTH` - Use both FOR and TO
- **Recipients**: Click dropdown, select users or type positions

### 4. Set Sender (FROM)

- Auto-filled with your name
- Can override if needed

### 5. Enter Subject

- Start typing in the Body field first
- **Auto-suggestions** will appear based on your content
- Click a suggestion or type your own subject

### 6. Write Body

- Type your content
- Press `Enter` to create new paragraphs
- System automatically:
    - Justifies text
    - Adds first-line indent
    - Formats spacing

### 7. Choose Signature

- ☑️ **Use E-Signature**: System adds your stored signature
- ☐ **Leave unchecked**: Space for wet signature

### 8. Save as Draft

Click **"Save as Draft"** button

---

## What Happens Next?

### Your memorandum is now in **DRAFT** status

**Available Actions:**

- ✏️ **Edit** - Make changes
- 👁️ **Preview** - See how it looks
- 🗑️ **Delete** - Remove it

---

## Generating Files

1. Open your memorandum
2. Click **"Preview"** to review (optional)
3. Click **"Generate Files"**
4. System creates:
    - 📄 DOCX (editable in Word)
    - 📄 PDF (print-ready)
5. Download buttons appear
6. Status changes to **GENERATED**

---

## Forwarding to Others

1. Open your memorandum
2. Click **"Forward"** button
3. Select recipients from list
4. Add optional message
5. Click **"Forward"**
6. Recipients will be notified
7. Status changes to **FORWARDED**

---

## Status Guide

| Status       | Meaning        | Can Edit? | Can Generate? | Can Forward? |
| ------------ | -------------- | --------- | ------------- | ------------ |
| 🔵 Draft     | Just created   | ✅ Yes    | ✅ Yes        | ❌ No        |
| 🔵 Previewed | User previewed | ✅ Yes    | ✅ Yes        | ✅ Yes       |
| 🟢 Generated | Files created  | ❌ No     | ❌ No         | ✅ Yes       |
| 🟣 Forwarded | Sent to users  | ❌ No     | ❌ No         | ❌ No        |
| ⚫ Signed    | Final version  | ❌ No     | ❌ No         | ❌ No        |

---

## Tips & Tricks

### 💡 Auto-Subject Suggestions

- Type at least 20 characters in the body
- Wait 1 second
- Suggestions appear as blue badges
- Click to use

### 💡 Multiple Paragraphs

- Each `Enter` key = new paragraph
- No need for manual formatting
- System handles justification and indentation

### 💡 Preview Before Generating

- Always preview first
- Check for typos and formatting
- Use Print Preview (Ctrl+P) to see final look

### 💡 Editing Rules

- Only **Draft** memorandums can be edited
- Once **Generated**, it's locked
- Delete and recreate if major changes needed

### 💡 Template Versions

- System uses active template
- Your memorandum keeps its template version forever
- Even if template is updated later

---

## Common Use Cases

### 1. Official Notice

```
Recipient Type: FOR
Recipients: All Staff Members
Subject: Official Notice on Policy Implementation
Body: Multi-paragraph content...
```

### 2. Request for Approval

```
Recipient Type: TO
Recipients: Director, PENR Officer
Subject: Request for Approval
Body: Justification and details...
```

### 3. Information Dissemination

```
Recipient Type: BOTH
FOR: File
TO: All Department Heads
Subject: Information Dissemination
Body: Important updates...
```

---

## Keyboard Shortcuts (in Preview)

- `Ctrl + P` - Print preview
- `Esc` - Close preview

---

## Need Help?

1. **Check the full documentation**: `MEMORANDUM_FEATURE_DOCUMENTATION.md`
2. **Contact IT Support**: For technical issues
3. **Admin Panel**: For template management (admin only)

---

## Sample Memorandum Structure

```
                    Republic of the Philippines
          Department of Environment and Natural Resources
        Provincial Environment and Natural Resources Office
                      Occidental Mindoro

                                                    January 26, 2026

                          MEMORANDUM

FOR     : All Staff Members
FROM    : Juan Dela Cruz, PENR Officer
SUBJECT : IMPLEMENTATION OF NEW POLICY

        This is the first paragraph with justified alignment and
first-line indentation. The system automatically formats this.

        Second paragraph here. Each paragraph is properly spaced
and indented according to official memorandum format standards.

        Third paragraph continues the content with consistent
formatting throughout the document.


                                            JUAN DELA CRUZ
                                            PENR Officer
```

---

## Quick Reference: All Routes

| Action        | URL                               |
| ------------- | --------------------------------- |
| List all      | `/memorandums`                    |
| Create new    | `/memorandums/create`             |
| View details  | `/memorandums/{id}`               |
| Edit          | `/memorandums/{id}/edit`          |
| Preview       | `/memorandums/{id}/preview`       |
| Download DOCX | `/memorandums/{id}/download-docx` |
| Download PDF  | `/memorandums/{id}/download-pdf`  |

---

**Happy Memo Writing! 📝**
