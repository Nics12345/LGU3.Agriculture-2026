# Farm Guides Upload - Debugging Guide

## Problem Summary
Adding guides works on **localhost** but fails on **domain** with "Error Adding Guide" message.

---

## What I Fixed

### 1. Better Error Messages
- Now shows specific error reasons instead of generic "Error Adding Guide"
- Error log file created at: `error_guide_log.txt`
- Check browser console (F12) for detailed error messages

### 2. Improved Error Handling
- Added try-catch blocks to catch database errors
- Added directory permission checks
- Added file move validation
- Better validation of YouTube links and file uploads

### 3. Debug Console Output
- Open browser Developer Tools (F12)
- Check **Console** tab for response details
- This helps identify the exact problem

---

## Common Causes & Solutions

### Issue 1: Directory Permissions (MOST COMMON)
**Symptoms:** "Failed to move uploaded file. Check server permissions..."

**Causes:**
- `uploads/` folder doesn't exist
- Parent folder (`uploads/farm_videos/` or `uploads/farm_images/`) not writable
- Shared hosting restrictions

**Solutions:**
```bash
# On your domain via SSH/FTP:
chmod 755 uploads/
chmod 755 uploads/farm_videos/
chmod 755 uploads/farm_images/

# Or create with FTP:
1. Connect via FTP
2. Create folders: uploads/farm_videos/ and uploads/farm_images/
3. Right-click → Properties → Permissions → 755 or 775
```

### Issue 2: Session Not Active
**Symptoms:** "Access denied. Not logged in."

**Solutions:**
- Make sure you're logged in as admin
- Check if cookies are enabled
- Clear browser cache and retry
- Check if domain uses HTTPS (might lose session over HTTP)

### Issue 3: Database Connection Issue
**Symptoms:** "Database error: ..." or "Failed to insert ... record"

**Solutions:**
- Verify database credentials in `db.php` (~at top of file)
- Ensure database and tables exist on domain
- Run the SQL migration: Import `lgu3_platform.sql`
- Check if host is same between localhost and domain

### Issue 4: File Size Limit
**Symptoms:** Progress shows but then fails

**Solutions:**
Check your domain's limits in `.htaccess` or contact hosting:
```apache
# Add to .htaccess in root:
php_value upload_max_filesize 100M
php_value post_max_size 100M
php_value max_input_time 300
```

---

## Troubleshooting Steps

### Step 1: Check Error Log
```
Look for: error_guide_log.txt in your root directory
```

### Step 2: Test Upload via Console
1. Open browser F12 → Console
2. Try uploading a guide
3. Look for error messages in console
4. Check Network tab to see response

### Step 3: Verify Directories Exist
```
Required folders:
- uploads/
- uploads/farm_videos/
- uploads/farm_images/
- uploads/pest_videos/
- uploads/farm_images/
```

### Step 4: Test Database Connection
Create a test file `test_db.php`:
```php
<?php
include 'db.php';
if ($conn->connect_error) {
    echo "❌ Connection failed: " . $conn->connect_error;
} else {
    echo "✓ Database connected successfully";
    
    // Test if tables exist
    $result = $conn->query("SHOW TABLES LIKE 'farm_videos'");
    if ($result->num_rows > 0) {
        echo "<br>✓ farm_videos table exists";
    } else {
        echo "<br>❌ farm_videos table missing - import lgu3_platform.sql";
    }
}
?>
```
Visit: `http://yourdomain.com/test_db.php`

### Step 5: Test File Permissions
Create `test_permissions.php`:
```php
<?php
$uploadDirs = [
    'uploads/',
    'uploads/farm_videos/',
    'uploads/farm_images/',
    'uploads/pest_videos/'
];

foreach ($uploadDirs as $dir) {
    $fullPath = __DIR__ . '/' . $dir;
    echo "Path: " . $fullPath . "<br>";
    echo "Exists: " . (is_dir($fullPath) ? "✓ Yes" : "❌ No") . "<br>";
    echo "Writable: " . (is_writable($fullPath) ? "✓ Yes" : "❌ No") . "<br>";
    echo "---<br>";
}
?>
```
Visit: `http://yourdomain.com/test_permissions.php`

---

## For Shared Hosting

### If you have cPanel:
1. Login to cPanel
2. Go to **File Manager**
3. Navigate to public_html/LGU3/uploads
4. Right-click → Properties → Set permissions to **755**
5. Do same for farm_videos and farm_images folders

### If you have Plesk:
1. Navigate to Files
2. Find uploads folder
3. Change permissions to **755**

### If you have WHM:
Contact your hosting provider to ensure shared hosting allows file uploads.

---

## Key Changes Made

### File: `admin-guides-farm.php`
- ✓ Added error logging to `error_guide_log.txt`
- ✓ Added directory permission checks
- ✓ Added detailed error messages
- ✓ Added try-catch error handling
- ✓ Added database error checking

### File: `admin-dashboard.js`
- ✓ Improved error message display
- ✓ Added response text logging to console
- ✓ Better error handling for network issues
- ✓ Shows actual error instead of generic message

---

## Testing the Fix

1. **On localhost (should still work):**
   - Open admin dashboard
   - Go to Farm Guides
   - Add a test guide
   - Should work as before

2. **On domain:**
   - Open browser F12 → Console
   - Try adding a guide
   - Check console for error details
   - Check `error_guide_log.txt` for server-side errors
   - Follow solutions above based on error message

---

## Error Messages & Meanings

| Error | Cause | Solution |
|-------|-------|----------|
| No error | Network issue | Check internet connection |
| "Access denied" | Not logged in | Login as admin first |
| "Failed to create upload directory" | No write permission | chmod 755 folders |
| "Upload directory is not writable" | Permission denied | chmod 755 uploads |
| "Failed to move uploaded file" | File move failed | Check disk space, permissions |
| "Database error: ..." | DB connection problem | Check db.php credentials |
| "Failed to insert * record" | Table missing or error | Import SQL database |
| "Invalid YouTube link" | Wrong URL format | Use full YouTube URL |

---

## Contact Hosting Support If:
- Directory permissions won't change (even with FTP)
- File upload size is limited
- Database tables missing and can't import SQL
- Getting PHP version incompatibility errors

Provide them with:
- Error message from `error_guide_log.txt`
- PHP info output
- Request to create folders with 755 permissions

---

## Files to Monitor

- `error_guide_log.txt` - Server-side errors
- Browser Console (F12) - Client-side errors
- Network tab (F12) - Response details

Check these when debugging!
