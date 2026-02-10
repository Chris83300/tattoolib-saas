# File Upload Security

## Overview

Ink&Pik implements a comprehensive file upload security system to protect against malicious file uploads, unauthorized access, and data breaches.

## Security Features

### 1. Secure File Upload Middleware

**Location**: `app/Http/Middleware/SecureFileUpload.php`

**Features**:
- **MIME Type Validation**: Server-side validation of real file MIME types
- **File Size Limits**: 
  - Images (JPEG, PNG, WebP): 5MB maximum
  - PDF documents: 10MB maximum
- **Extension Filtering**: Forbidden extensions (.php, .exe, .js, .sh, etc.)
- **Double Extension Detection**: Blocks files like `image.jpg.php`
- **Filename Sanitization**: Alphanumeric characters only (underscores allowed)

**Allowed MIME Types**:
- `image/jpeg` → .jpg, .jpeg
- `image/png` → .png  
- `image/webp` → .webp
- `application/pdf` → .pdf

### 2. Antivirus Protection

**Service**: `app/Services/AntivirusService.php`

**Features**:
- **ClamAV Integration**: Real-time malware scanning
- **Socket Communication**: Direct connection to ClamAV daemon
- **Fallback Protection**: Blocks uploads if scanning fails
- **Comprehensive Logging**: All scan attempts logged

**Configuration**:
```bash
# Install ClamAV
sudo apt-get install clamav clamav-daemon

# Start daemon
sudo systemctl start clamav-daemon
```

### 3. Secure Storage

**Configuration**: `config/filesystems.php`

**Secure Disk**:
```php
'secure_uploads' => [
    'driver' => 'local',
    'root' => storage_path('app/secure'),
    'visibility' => 'private', // Not directly accessible
],
```

**Media Library**: Uses secure disk by default
```php
'disk_name' => env('MEDIA_DISK', 'secure_uploads'),
```

### 4. Secure Download System

**Route**: `GET /api/messages/{message}/download`

**Features**:
- **Authorization Check**: Only conversation participants can download
- **MIME Type Headers**: Proper content-type headers
- **Private Storage**: Files not directly accessible via URL
- **Access Logging**: All downloads tracked

## Implementation Details

### Middleware Application

Apply to routes requiring file uploads:

```php
Route::middleware(['auth:sanctum', 'secure.file.upload'])->group(function () {
    Route::post('/conversations/{conversation}/messages', [MessageController::class, 'store']);
    // Other upload routes...
});
```

### Validation Rules

Enhanced Form Request validation:

```php
'attachment' => [
    'nullable',
    'file', 
    'mimes:jpeg,png,webp,pdf',
    'max:10240',
    function ($attribute, $value, $fail) {
        $realMimeType = $value->getMimeType();
        $allowedMimes = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];
        
        if (!in_array($realMimeType, $allowedMimes)) {
            $fail('Le type de fichier n\'est pas autorisé.');
        }
    },
],
```

### Antivirus Integration

```php
// In controller after validation
if ($request->hasFile('attachment')) {
    $file = $request->file('attachment');
    
    // Scan for malware
    app(AntivirusService::class)->scan($file);
    
    // Process file
    $message->addAttachment($file, $attachmentType);
}
```

## Security Testing

### Test Suite

**Location**: `tests/Feature/FileUploadSecurityTest.php`

**Test Coverage**:
- ✅ Reject executable files disguised as images
- ✅ Reject double extension files (.jpg.php)
- ✅ Detect infected files with antivirus
- ✅ Enforce file size limits
- ✅ Validate real MIME types
- ✅ Secure download authorization
- ✅ Reject forbidden extensions

### Running Tests

```bash
# Run security tests
php artisan test --filter FileUploadSecurityTest

# Run specific test
php artisan test --filter test_reject_executable_files
```

## Monitoring & Logging

### Security Events Logged

1. **Rejected Uploads**: MIME type, extension, size violations
2. **Antivirus Detections**: Malicious files blocked
3. **Unauthorized Access**: Failed download attempts
4. **Suspicious Activity**: Multiple failed uploads

### Log Locations

- **Application Logs**: `storage/logs/laravel.log`
- **Security Events**: Custom security log channel (recommended)
- **ClamAV Logs**: `/var/log/clamav/`

## Best Practices

### For Developers

1. **Always use** middleware for upload routes
2. **Validate on both** client and server side
3. **Use secure storage** for sensitive files
4. **Implement proper authorization** for downloads
5. **Log security events** for monitoring

### For Administrators

1. **Keep ClamAV updated**: `sudo freshclam`
2. **Monitor security logs** regularly
3. **Review upload patterns** for anomalies
4. **Test security measures** after updates
5. **Backup secure storage** regularly

## Troubleshooting

### Common Issues

**ClamAV Connection Failed**:
```bash
# Check if daemon is running
sudo systemctl status clamav-daemon

# Check socket location
ls -la /var/run/clamav/
```

**File Upload Rejected**:
1. Check file size limits
2. Verify MIME type
3. Ensure no double extensions
4. Review security logs

**Download Fails**:
1. Verify user is conversation participant
2. Check file exists in secure storage
3. Confirm proper authorization headers

### Performance Considerations

- **Antivirus scanning** adds ~100-500ms per file
- **Large file uploads** may timeout
- **Concurrent scans** may need queue processing

## Compliance

This implementation addresses:
- **OWASP File Upload Security** guidelines
- **GDPR data protection** requirements
- **French cybersecurity** recommendations
- **Payment card industry** (PCI) standards for financial documents

## Future Enhancements

1. **Queue-based scanning** for large files
2. **Machine learning** malware detection
3. **File content analysis** for sensitive data
4. **Rate limiting** on upload endpoints
5. **Automatic cleanup** of old temporary files
