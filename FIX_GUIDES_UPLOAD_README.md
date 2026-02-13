# Farm Guides & Pest Guides Upload Fix - Implementation Guide

## What Was Fixed

I've enhanced your Farm Guides and Pest Guides modules with **better error detection and reporting**. This will help identify why uploads fail when moving from localhost to your domain.

---

## Changes Made

### 1. **admin-guides-farm.php**
✓ Added error logging to `error_guide_log.txt`
✓ Added directory permission validation
✓ Added detailed error messages  
✓ Added try-catch exception handling
✓ Validates uploads folder is writable before attempting upload
✓ Shows specific reasons for failures

### 2. **admin-guides-pest.php**
✓ Same improvements as farm guides
✓ Logs errors to `error_pest_guide_log.txt`
✓ Better validation of video uploads
✓ Improved error responses

### 3. **admin-dashboard.js**
✓ Shows actual error message instead of generic "Error Adding Guide"
✓ Logs response to browser console for debugging
✓ Better error handling for network issues

### 4. **admin-guides-pest.js**
✓ Enhanced error reporting
✓ Shows actual error responses
✓ Logs to browser console for debugging

---

## How to Use the Fix

### On Your Domain (After Uploading Changes)

1. **Navigate to Admin Dashboard**
   - Go to your domain admin section
   - Login as admin

2. **Try Adding a Guide**
   - Open browser Developer Tools: **F12**
   - Go to **Console** tab
   - Try adding a farm or pest guide
   - Watch for error messages

3. **Check for Error Details**
   - If it fails, you'll see specific error like:
     - "Failed to create upload directory. Check permissions."
     - "Upload directory is not writable. Check folder permissions."
     - "Database error: ..."
     - etc.

4. **Check Error Log Files**
   - Visit: `http://yourdomain.com/error_guide_log.txt`
   - Or: `http://yourdomain.com/error_pest_guide_log.txt`
   - These files will contain all server-side errors with timestamps

---

## Debugging Workflow

### Step 1: Check Browser Console
```
1. Open Browser F12
2. Console tab
3. Try adding a guide
4. Look for error message
5. This is your first clue
```

### Step 2: Check Error Logs
```
Visit in browser:
- http://yourdomain.com/error_guide_log.txt
- http://yourdomain.com/error_pest_guide_log.txt

These show server-side errors
```

### Step 3: Create Test Files
Run these test files to diagnose issues:

**test_db.php** (Check database):
```
<?php
include 'db.php';
if ($conn->connect_error) {
    echo "❌ DB Failed: " . $conn->connect_error;
} else {
    echo "✓ Database OK";
}
?>
```

**test_permissions.php** (Check folder access):
```
<?php
$uploadDirs = [
    'uploads/',
    'uploads/farm_videos/',
    'uploads/farm_images/',
    'uploads/pest_videos/'
];

foreach ($uploadDirs as $dir) {
    $fullPath = __DIR__ . '/' . $dir;
    echo $dir . ": ";
    echo (is_dir($fullPath) ? "✓ exists " : "❌ missing ");
    echo (is_writable($fullPath) ? "✓ writable" : "❌ readonly") . "<br>";
}
?>
```

---

## Most Common Issues & Fixes

### Issue: "Upload directory is not writable"
**Cause:** Folder permissions too restrictive on shared hosting

**Fix via FTP:**
1. Connect to domain via FTP
2. Right-click `uploads` folder → Properties
3. Set permissions to **755** (or 775)
4. Do same for subfolders: `farm_videos/`, `farm_images/`, `pest_videos/`
5. Retry upload

**Fix via SSH:**
```bash
chmod 755 uploads/
chmod 755 uploads/farm_videos/
chmod 755 uploads/farm_images/
chmod 755 uploads/pest_videos/
```

**Fix via cPanel:**
1. File Manager
2. Find uploads folder
3. Right-click → Change Permissions → 755

### Issue: "Database error: ..."
**Cause:** Database connection failing or table missing

**Fix:**
1. Verify credentials in `db.php` match your domain database
2. Import SQL: `lgu3_platform.sql` to your domain database
3. Test connection with `test_db.php`

### Issue: "Failed to move uploaded file"
**Causes:**
- File too large (server limit)
- Not enough disk space
- Permission denied
- Folder doesn't exist

**Fix:**
1. Check `.htaccess` for upload_max_filesize setting
2. Contact hosting if disk full
3. Verify permissions are 755
4. Verify folders exist (run `test_permissions.php`)

---

## Testing Checklist

- [ ] Website loads on domain
- [ ] Can login as admin
- [ ] Can access Farm Guides page
- [ ] Can access Pest Guides page
- [ ] Try adding guide via YouTube link (easiest test)
- [ ] Check browser console (F12) for errors
- [ ] Check error log files
- [ ] Try uploading small image file
- [ ] Try uploading small video file
- [ ] All changes saved to database

---

## Key Features of the Fix

1. **Specific Error Messages**
   - Instead of: "Error adding guide"
   - Now: "Failed to create upload directory. Check permissions." ✓

2. **Error Logging**
   - All issues logged to file with timestamps
   - Easy to track what went wrong and when

3. **Permission Validation**
   - Checks folder writable BEFORE attempting upload
   - Saves time troubleshooting

4. **Better Browser Feedback**
   - Console logs show raw response
   - Helpful for technical support

5. **Handles Missing Directories**
   - Auto-creates folders if they don't exist
   - Still checks if creation is possible (permissions)

---

## File Upload Limits

If files are too large, add to `.htaccess`:
```apache
php_value upload_max_filesize 100M
php_value post_max_size 100M
php_value max_input_time 300
php_value max_execution_time 300
```

---

## For Technical Support

If you need to contact your hosting provider, provide them:
1. Error message from `error_guide_log.txt`
2. Output from `test_permissions.php`
3. Output from `test_db.php`
4. Browser console error (F12)
5. Your domain name
6. PHP version (from phpinfo())

---

## Files Modified

1. `admin-guides-farm.php` - Enhanced error handling
2. `admin-guides-pest.php` - Enhanced error handling
3. `admin-dashboard.js` - Better error display
4. `admin-guides-pest.js` - Better error display
5. `DEBUG_GUIDE_FARM_UPLOAD.md` - Detailed debugging guide (created)

## Files Created

1. `DEBUG_GUIDE_FARM_UPLOAD.md` - Complete debugging guide
2. `error_guide_log.txt` - Farm guides error log (auto-created on error)
3. `error_pest_guide_log.txt` - Pest guides error log (auto-created on error)

---

## Quick Start on Domain

1. Upload all modified files to your domain
2. Visit admin dashboard
3. Open F12 console
4. Try adding a guide
5. Check error message or error log
6. Apply appropriate fix from guide above

---

## Common Success Indicators

✓ Can add YouTube guides (no file upload)
✓ Can add images and videos
✓ Files appear in `uploads/` folder
✓ Database shows entries in farm_videos/farm_images tables
✓ No errors in browser console
✓ No entries in error_guide_log.txt after successful upload

---

## Still Having Issues?

Check in this order:
1. Browser console (F12) for error message
2. `error_guide_log.txt` for server error
3. Run `test_permissions.php` to check folder access
4. Run `test_db.php` to check database
5. Contact hosting with findings described above

Good luck! 🚀
