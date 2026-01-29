# DomPDF Setup Instructions

## Overview

This document provides complete instructions for migrating from SnappyPDF (wkhtmltopdf) to DomPDF for PDF export functionality. DomPDF is fully PHP-based and works perfectly on shared hosting environments like Hostinger.

---

## 1. Dependency Installation

Run the following command in your project root:

```bash
composer require barryvdh/laravel-dompdf
```

**Note:** You can optionally remove SnappyPDF if no longer needed:

```bash
composer remove barryvdh/laravel-snappy
```

---

## 2. Configuration (Optional)

Publish the config file if you need custom settings:

```bash
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

This creates `config/dompdf.php`. Default settings are sufficient for most use cases.

---

## 3. Controller Changes

**File:** `app/Http/Controllers/MemorandumController.php`

**Before (SnappyPDF):**

```php
use Barryvdh\Snappy\Facades\SnappyPdf;

public function exportPdf($id)
{
    $memorandum = Memorandum::with(['template', 'fromUser.Employee', 'creator.Employee.office'])->findOrFail($id);

    $pdf = SnappyPdf::loadView('memorandums.export-pdf', compact('memorandum'))
        ->setPaper('letter')
        ->setOrientation('portrait')
        ->setOption('enable-local-file-access', true)
        // ... many options

    return $pdf->stream('memorandum_' . $memorandum->memorandum_number . '.pdf');
}
```

**After (DomPDF):**

```php
// No import needed - uses PDF facade from config/app.php

public function exportPdf($id)
{
    try {
        $memorandum = Memorandum::with(['template', 'fromUser.Employee', 'creator.Employee.office'])->findOrFail($id);

        $pdf = \PDF::loadView('memorandums.export-pdf', compact('memorandum'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('memorandum_' . $memorandum->memorandum_number . '.pdf');
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], 500);
    }
}
```

**Key Changes:**

- Removed all SnappyPDF-specific options
- Changed paper size from `letter` to `a4` (210mm × 297mm)
- Simplified to just `loadView()`, `setPaper()`, and `stream()`
- Uses `stream()` to open in new tab (not `download()`)

---

## 4. Blade Template Updates

**File:** `resources/views/memorandums/export-pdf.blade.php`

### Changes Made:

#### A. Page Setup

**Before:**

```css
@page {
    size: 8.5in 11in;
    margin: 0.5in 0.5in 0.5in 0.5in;
}
```

**After:**

```css
@page {
    size: A4;
    margin: 12mm 20mm 12mm 20mm;
}
```

**Reason:** DomPDF prefers standard page sizes (A4, Letter) and mm units.

#### B. Container Layout

**Before:**

```css
.page-container {
    display: flex;
    flex-direction: column;
    min-height: 11in;
    padding: 0.2in 0.8in 0.5in 0.8in;
}
```

**After:**

```css
.page-container {
    width: 100%;
    background: white;
    padding: 5mm 0;
}
```

**Reason:** DomPDF has limited flexbox support. Use simple block layout instead.

#### C. Image Paths

All images already use `public_path()` - perfect for DomPDF:

```blade
<img src="{{ public_path('images/logo.png') }}" alt="DENR Logo" class="logo">
<img src="{{ public_path('images/bagongPilipinasLogo.png') }}" alt="Bagong Pilipinas" class="logo2">
<img src="{{ public_path('images/ISO.jpg') }}" class="footer-img" alt="Footer Left">
<img src="{{ public_path('images/SOCOTEC.jpg') }}" class="footer-img" alt="Footer Right">
```

**Signature images:**

```blade
<img src="{{ public_path('storage/' . $signaturePath) }}" class="signature-img" alt="Signature">
```

**Important:** SVG files removed and replaced with PNG/JPG because DomPDF doesn't support SVG.

---

## 5. Frontend Button (Already Implemented)

**File:** `resources/views/memorandums/preview.blade.php`

```blade
<a href="{{ route('memorandums.export-pdf', $memorandum->id) }}"
   class="export-pdf-btn"
   target="_blank">
    📄 Export to PDF
</a>
```

**CSS Styling:**

```css
.export-pdf-btn {
    position: fixed;
    top: 70px;
    right: 20px;
    background: #dc3545;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}
```

---

## 6. Route (Already Configured)

**File:** `routes/web.php`

```php
Route::get('/{id}/export-pdf', [MemorandumController::class, 'exportPdf'])->name('export-pdf');
```

No changes needed - same route works with new implementation.

---

## 7. DomPDF Compatibility Notes

### ✅ Supported Features:

- Basic CSS (margins, padding, borders, colors)
- Tables with `border-collapse`
- Images (PNG, JPG, GIF)
- Inline and block elements
- Text alignment and formatting
- `public_path()` for local files

### ⚠️ Limited Support:

- **Flexbox** - Use tables or floats instead
- **CSS Grid** - Use tables instead
- **SVG images** - Convert to PNG/JPG
- **Web fonts** - Use system fonts (Times New Roman, Arial, etc.)
- **Background images** - May not render consistently

### ❌ Not Supported:

- JavaScript
- External CDN resources (must be local)
- CSS animations
- Complex pseudo-selectors
- `position: fixed` (except for headers/footers via @page)

---

## 8. Image Troubleshooting

If images don't appear in PDF:

### Check 1: File Exists

```php
if (!file_exists(public_path('images/logo.png'))) {
    // File missing
}
```

### Check 2: File Permissions

```bash
chmod 644 public/images/*
```

### Check 3: DomPDF Image Option

In `config/dompdf.php` (if published):

```php
'enable_remote' => false,  // Security: disable remote images
'enable_php' => false,     // Security: disable PHP execution
```

### Fallback Strategy

```blade
@if(file_exists(public_path('images/logo.png')))
    <img src="{{ public_path('images/logo.png') }}" alt="Logo">
@else
    <div style="width: 90px; height: 90px; background: #ccc;"></div>
@endif
```

---

## 9. Testing

### Test Command:

```bash
php artisan serve
```

### Test URL:

```
http://localhost:8000/memorandums/{id}/export-pdf
```

### Expected Behavior:

1. Opens PDF in new browser tab
2. Shows all logos and images
3. Proper A4 page layout
4. Clean typography
5. Print-ready format

---

## 10. Production Deployment (Hostinger)

### Upload Files:

```
/app/Http/Controllers/MemorandumController.php
/resources/views/memorandums/export-pdf.blade.php
/config/dompdf.php (if published)
```

### Run on Server:

```bash
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Verify Images:

```bash
ls -la public/images/
# Should show: logo.png, bagongPilipinasLogo.png, ISO.jpg, SOCOTEC.jpg
```

---

## 11. Summary of Changes

| Component      | Change                  | Reason                     |
| -------------- | ----------------------- | -------------------------- |
| **Dependency** | SnappyPDF → DomPDF      | Hostinger compatibility    |
| **Paper Size** | Letter → A4             | Standard international     |
| **Layout**     | Flexbox → Block         | DomPDF limitation          |
| **Images**     | SVG → PNG               | DomPDF doesn't support SVG |
| **Options**    | 10+ options → 2 options | DomPDF is simpler          |
| **Method**     | `stream()` retained     | Opens in new tab           |

---

## 12. Troubleshooting Common Issues

### Issue: "Class 'PDF' not found"

**Solution:**

```bash
composer dump-autoload
php artisan config:clear
```

### Issue: Images not showing

**Solution:** Verify `public_path()` returns correct path:

```php
dd(public_path('images/logo.png'));
// Should output: /path/to/public/images/logo.png
```

### Issue: PDF downloads instead of opening

**Solution:** Check browser settings - some browsers auto-download PDFs. This is correct behavior; `stream()` sends proper headers for inline display.

### Issue: Layout broken

**Solution:** Avoid flexbox and complex CSS. Use tables for layout:

```html
<table style="width: 100%;">
    <tr>
        <td style="width: 33%;">Left</td>
        <td style="width: 34%; text-align: center;">Center</td>
        <td style="width: 33%; text-align: right;">Right</td>
    </tr>
</table>
```

---

## 13. Performance Notes

- **First PDF generation**: ~2-3 seconds (loading fonts, images)
- **Subsequent generations**: ~1-2 seconds (cached)
- **Memory usage**: ~50-100MB per PDF
- **Recommended `memory_limit`**: 256M in `php.ini`

---

## Complete Installation Checklist

- [ ] Run `composer require barryvdh/laravel-dompdf`
- [ ] Update `MemorandumController::exportPdf()` method
- [ ] Update `export-pdf.blade.php` template
- [ ] Verify all images are PNG/JPG (not SVG)
- [ ] Test PDF export locally
- [ ] Deploy to production
- [ ] Clear all caches on production
- [ ] Test on production environment

---

**Migration Complete!** ✅

The PDF export now works on any PHP hosting environment without requiring system-level dependencies.
