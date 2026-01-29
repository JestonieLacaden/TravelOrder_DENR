# DomPDF Commands Cheat Sheet

## Installation

```bash
# Install DomPDF
composer require barryvdh/laravel-dompdf

# Optional: Remove Snappy
composer remove barryvdh/laravel-snappy

# Optional: Publish config
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

## Cache Management

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches (production)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Testing

```bash
# Start development server
php artisan serve

# Test URL
http://localhost:8000/memorandums/{id}/export-pdf
```

## Production Deployment

```bash
# Install dependencies (production)
composer install --no-dev --optimize-autoloader

# Optimize
composer dump-autoload --optimize

# Cache everything
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
chmod -R 755 storage bootstrap/cache
chmod -R 644 public/images/*
```

## Debugging

```bash
# Check if images exist
ls -la public/images/

# Check composer packages
composer show barryvdh/laravel-dompdf

# Check Laravel version
php artisan --version

# Check PHP version
php -v

# Regenerate autoload files
composer dump-autoload
```

## Quick Image Check

```bash
# Verify all required images
cd public/images
ls -la logo.png bagongPilipinasLogo.png ISO.jpg SOCOTEC.jpg
```

## Windows Equivalents

```cmd
REM List images
dir public\images

REM Check PHP version
php -v

REM Run server
php artisan serve
```

## Common Issues & Quick Fixes

```bash
# "Class PDF not found"
composer dump-autoload
php artisan config:clear

# Memory limit errors
# Edit php.ini: memory_limit = 256M

# Permission errors
chmod -R 755 storage
chmod -R 644 public/images

# Cached routes issue
php artisan route:clear
php artisan route:cache
```

## Git Commands (If Using Version Control)

```bash
# Add new files
git add .

# Commit changes
git commit -m "Migrate from SnappyPDF to DomPDF for PDF export"

# Push to repository
git push origin main
```

## Package Info

```bash
# View DomPDF package details
composer show barryvdh/laravel-dompdf

# Update DomPDF
composer update barryvdh/laravel-dompdf

# View all packages
composer show
```

## One-Line Setup (After Code Changes)

```bash
composer require barryvdh/laravel-dompdf && php artisan config:clear && php artisan route:clear && php artisan view:clear
```

## Production One-Liner

```bash
composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache
```

---

## File Locations Quick Reference

```
app/Http/Controllers/MemorandumController.php    ← Controller
resources/views/memorandums/export-pdf.blade.php ← Template
routes/web.php                                    ← Route
public/images/                                    ← Images
config/dompdf.php                                 ← Config (optional)
```

---

**Ready to Go!** 🚀

Just run: `composer require barryvdh/laravel-dompdf`
