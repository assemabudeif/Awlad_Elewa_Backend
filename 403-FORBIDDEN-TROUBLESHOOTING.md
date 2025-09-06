# 403 Forbidden Error - Laravel Production Troubleshooting

## Problem
Getting 403 Forbidden error when accessing API endpoints (like `/api/products`) in production server.

## Common Causes & Solutions

### 1. File Permissions
The web server user needs proper permissions to access Laravel files.

**Check current permissions:**
```bash
ls -la /path/to/your/laravel/project
ls -la /path/to/your/laravel/project/storage/
ls -la /path/to/your/laravel/project/bootstrap/cache/
```

**Fix permissions:**
```bash
# Set proper ownership (replace www-data with your web server user)
sudo chown -R www-data:www-data /path/to/your/laravel/project

# Set proper permissions
sudo chmod -R 755 /path/to/your/laravel/project
sudo chmod -R 775 storage/
sudo chmod -R 775 bootstrap/cache/
sudo chmod -R 775 public/storage/
```

### 2. Web Server Configuration

**For Apache:**
- Ensure `mod_rewrite` is enabled: `sudo a2enmod rewrite`
- Check if `.htaccess` is being read: `AllowOverride All` in Apache config
- Restart Apache: `sudo systemctl restart apache2`

**For Nginx:**
- Ensure proper `try_files` directive in server block
- Check if `index.php` is in the correct location

### 3. Laravel Configuration Issues

**Check environment variables:**
```bash
# Ensure APP_ENV is set correctly
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

**Clear Laravel caches:**
```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear
php artisan optimize:clear
```

### 4. Storage Directory Issues

**Ensure storage directory exists and is writable:**
```bash
# Create storage directory if missing
mkdir -p public/storage

# Set proper permissions
chmod -R 775 public/storage/
chown -R www-data:www-data public/storage/
```

### 5. Database Connection

**Check database connectivity:**
```bash
php artisan tinker --execute="DB::connection()->getPdo();"
```

### 6. Route Caching Issues

**Clear route cache:**
```bash
php artisan route:clear
php artisan route:cache
```

### 7. Server Logs

**Check server error logs:**
```bash
# Apache
sudo tail -f /var/log/apache2/error.log

# Nginx
sudo tail -f /var/log/nginx/error.log

# Laravel logs
tail -f storage/logs/laravel.log
```

### 8. SELinux (if applicable)

**Check SELinux status:**
```bash
sestatus
# If enabled, you might need to set proper context
sudo setsebool -P httpd_can_network_connect 1
```

### 9. Firewall Issues

**Check if firewall is blocking requests:**
```bash
sudo ufw status
# If enabled, ensure port 80/443 is allowed
```

## Quick Diagnostic Steps

1. **Test basic Laravel access:**
   ```bash
   curl -I https://yourdomain.com/
   ```

2. **Test API endpoint directly:**
   ```bash
   curl -I https://yourdomain.com/api/products
   ```

3. **Check if it's a specific endpoint issue:**
   ```bash
   curl -I https://yourdomain.com/api/categories
   ```

4. **Test with verbose output:**
   ```bash
   curl -v https://yourdomain.com/api/products
   ```

## Most Likely Solution

Based on the error pattern, the most common fix is:

```bash
# 1. Set proper file permissions
sudo chown -R www-data:www-data /path/to/your/laravel/project
sudo chmod -R 755 /path/to/your/laravel/project
sudo chmod -R 775 storage/
sudo chmod -R 775 bootstrap/cache/
sudo chmod -R 775 public/storage/

# 2. Clear Laravel caches
php artisan optimize:clear

# 3. Restart web server
sudo systemctl restart apache2  # or nginx
```

## If Problem Persists

1. Check server error logs for specific error messages
2. Verify the web server user has access to all Laravel directories
3. Ensure the Laravel application is properly configured for production
4. Check if there are any custom middleware or security rules blocking access 