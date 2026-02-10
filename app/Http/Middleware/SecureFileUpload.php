<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class SecureFileUpload
{
    /**
     * Types MIME autorisés avec leurs limites de taille
     */
    private const ALLOWED_MIME_TYPES = [
        'image/jpeg' => ['max_size' => 5 * 1024 * 1024, 'extensions' => ['jpg', 'jpeg']],
        'image/png' => ['max_size' => 5 * 1024 * 1024, 'extensions' => ['png']],
        'image/webp' => ['max_size' => 5 * 1024 * 1024, 'extensions' => ['webp']],
        'application/pdf' => ['max_size' => 10 * 1024 * 1024, 'extensions' => ['pdf']],
    ];

    /**
     * Extensions interdites explicitement
     */
    private const FORBIDDEN_EXTENSIONS = [
        'php', 'php3', 'php4', 'php5', 'phtml', 'pht',
        'exe', 'bat', 'cmd', 'com', 'scr',
        'js', 'vbs', 'ps1', 'sh', 'bash',
        'pl', 'py', 'rb', 'go', 'java',
        'svg', 'swf', 'jar', 'app', 'deb',
        'rpm', 'dmg', 'pkg', 'msi', 'zip', 'rar', 'tar', 'gz'
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasFile('attachment') || $request->hasFile('attachments')) {
            $files = $request->hasFile('attachments') 
                ? $request->file('attachments') 
                : [$request->file('attachment')];

            foreach ($files as $file) {
                if ($file && $file->isValid()) {
                    $this->validateFile($file);
                }
            }
        }

        return $next($request);
    }

    /**
     * Valide un fichier uploadé
     */
    private function validateFile(\Illuminate\Http\UploadedFile $file): void
    {
        // 1. Vérifier le type MIME réel
        $realMimeType = $file->getMimeType();
        
        if (!isset(self::ALLOWED_MIME_TYPES[$realMimeType])) {
            Log::warning('File upload rejected: unauthorized MIME type', [
                'file' => $file->getClientOriginalName(),
                'mime_type' => $realMimeType,
                'ip' => request()->ip(),
            ]);
            
            abort(422, 'Type de fichier non autorisé');
        }

        // 2. Vérifier la taille
        $maxSize = self::ALLOWED_MIME_TYPES[$realMimeType]['max_size'];
        if ($file->getSize() > $maxSize) {
            Log::warning('File upload rejected: file too large', [
                'file' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'max_size' => $maxSize,
                'ip' => request()->ip(),
            ]);
            
            abort(422, 'Fichier trop volumineux');
        }

        // 3. Vérifier l'extension
        $originalExtension = strtolower($file->getClientOriginalExtension());
        $allowedExtensions = self::ALLOWED_MIME_TYPES[$realMimeType]['extensions'];
        
        if (!in_array($originalExtension, $allowedExtensions)) {
            Log::warning('File upload rejected: extension not allowed for MIME type', [
                'file' => $file->getClientOriginalName(),
                'extension' => $originalExtension,
                'mime_type' => $realMimeType,
                'ip' => request()->ip(),
            ]);
            
            abort(422, 'Extension de fichier non autorisée');
        }

        // 4. Vérifier les extensions interdites
        if (in_array($originalExtension, self::FORBIDDEN_EXTENSIONS)) {
            Log::warning('File upload rejected: forbidden extension', [
                'file' => $file->getClientOriginalName(),
                'extension' => $originalExtension,
                'ip' => request()->ip(),
            ]);
            
            abort(422, 'Type de fichier interdit');
        }

        // 5. Vérifier les doubles extensions
        $filename = $file->getClientOriginalName();
        if ($this->hasDoubleExtension($filename)) {
            Log::warning('File upload rejected: double extension detected', [
                'file' => $filename,
                'ip' => request()->ip(),
            ]);
            
            abort(422, 'Les fichiers avec double extension ne sont pas autorisés');
        }

        // 6. Sanitiser le nom du fichier
        $sanitizedName = $this->sanitizeFilename($filename);
        if ($sanitizedName !== $filename) {
            Log::info('File name sanitized', [
                'original' => $filename,
                'sanitized' => $sanitizedName,
                'ip' => request()->ip(),
            ]);
        }
    }

    /**
     * Détecte les doubles extensions (.jpg.php, .png.js, etc.)
     */
    private function hasDoubleExtension(string $filename): bool
    {
        $parts = explode('.', $filename);
        return count($parts) > 2;
    }

    /**
     * Nettoie le nom du fichier (alphanumérique + underscore + tiret + point)
     */
    private function sanitizeFilename(string $filename): string
    {
        // Extraire l'extension
        $parts = explode('.', $filename);
        if (count($parts) < 2) {
            return $filename;
        }

        $extension = array_pop($parts);
        $basename = implode('.', $parts);

        // Nettoyer le nom de base
        $sanitized = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $basename);
        $sanitized = preg_replace('/_+/', '_', $sanitized);
        $sanitized = trim($sanitized, '_');

        // Limiter la longueur
        $sanitized = substr($sanitized, 0, 50);

        return $sanitized . '.' . $extension;
    }
}
