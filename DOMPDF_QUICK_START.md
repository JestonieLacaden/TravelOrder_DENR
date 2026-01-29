# Quick Start: DomPDF Implementation

## 1. Install DomPDF

```bash
composer require barryvdh/laravel-dompdf
```

## 2. Optional: Remove Old Snappy (if not used elsewhere)

```bash
composer remove barryvdh/laravel-snappy
```

## 3. Files Already Updated ✅

### Controller: `app/Http/Controllers/MemorandumController.php`

```php
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

### Template: `resources/views/memorandums/export-pdf.blade.php`

- ✅ A4 paper size (210mm × 297mm)
- ✅ Simple block layout (no flexbox)
- ✅ All images use `public_path()`
- ✅ PNG/JPG only (no SVG)

### Route: `routes/web.php`

```php
Route::get('/{id}/export-pdf', [MemorandumController::class, 'exportPdf'])->name('export-pdf');
```

### Frontend Button: `resources/views/memorandums/preview.blade.php`

```blade
<a href="{{ route('memorandums.export-pdf', $memorandum->id) }}"
   class="export-pdf-btn"
   target="_blank">
    📄 Export to PDF
</a>
```

## 4. Test It

```bash
php artisan serve
```

Visit: `http://localhost:8000/memorandums/[id]/export-pdf`

**Expected:** PDF opens in new tab, ready for print/download.

---

## Key Changes Summary

| Before (SnappyPDF)          | After (DomPDF)     |
| --------------------------- | ------------------ |
| wkhtmltopdf binary required | Pure PHP           |
| Complex options (10+)       | Simple (2 options) |
| Letter (8.5×11in)           | A4 (210×297mm)     |
| Flexbox layout              | Block layout       |
| SVG images                  | PNG/JPG only       |

---

## Production Deployment

```bash
# 1. Upload updated files
# 2. Install dependencies
composer install --no-dev --optimize-autoloader

# 3. Clear and rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Verify images exist
ls -la public/images/
```

---

## Troubleshooting

**Images not showing?**

```php
// Check file exists
dd(file_exists(public_path('images/logo.png')));

// Check path
dd(public_path('images/logo.png'));
```

**PDF not opening in new tab?**

- This is browser-dependent
- `stream()` sends correct headers
- Some browsers auto-download PDFs

**Memory errors?**

```ini
# In php.ini or .htaccess
memory_limit = 256M
```

---

## Why DomPDF?

✅ **Shared Hosting Compatible** - No system binaries needed  
✅ **Pure PHP** - Works everywhere PHP runs  
✅ **Maintained** - Active development and support  
✅ **Simple API** - Minimal configuration needed  
✅ **Hostinger Ready** - Works out of the box

---

## Support

Full documentation: `DOMPDF_SETUP_INSTRUCTIONS.md`

**Ready to use!** Just run `composer require barryvdh/laravel-dompdf` 🚀
