<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Exception;

class AntivirusService
{
    /**
     * Scan un fichier avec ClamAV
     */
    public function scan(UploadedFile $file): bool
    {
        try {
            // Vérifier si ClamAV est disponible
            if (!$this->isClamAvAvailable()) {
                Log::warning('ClamAV not available, skipping antivirus scan', [
                    'file' => $file->getClientOriginalName(),
                ]);
                return true; // Autoriser si ClamAV non disponible (fallback)
            }

            // Créer une connexion socket à ClamAV
            $socket = @fsockopen('unix:///var/run/clamav/clamd.sock', 0, $errno, $errstr, 5);
            
            if (!$socket) {
                Log::error('Failed to connect to ClamAV', [
                    'errno' => $errno,
                    'error' => $errstr,
                    'file' => $file->getClientOriginalName(),
                ]);
                return true; // Fallback sécurisé
            }

            // Envoyer la commande de scan
            fwrite($socket, "SCAN {$file->getRealPath()}\n");
            
            // Lire la réponse
            $response = fgets($socket);
            fclose($socket);

            // Analyser la réponse
            if (strpos($response, 'FOUND') !== false) {
                Log::alert('Malicious file detected and blocked', [
                    'file' => $file->getClientOriginalName(),
                    'response' => trim($response),
                    'ip' => request()->ip(),
                ]);
                
                throw new Exception('Fichier malveillant détecté');
            }

            if (strpos($response, 'OK') === false) {
                Log::warning('Unexpected ClamAV response', [
                    'file' => $file->getClientOriginalName(),
                    'response' => trim($response),
                ]);
                return true; // Fallback en cas de réponse inattendue
            }

            Log::info('File scanned successfully', [
                'file' => $file->getClientOriginalName(),
                'response' => trim($response),
            ]);

            return true;

        } catch (Exception $e) {
            Log::error('Antivirus scan failed', [
                'file' => $file->getClientOriginalName(),
                'error' => $e->getMessage(),
                'ip' => request()->ip(),
            ]);
            
            // En cas d'erreur, on peut choisir de bloquer ou autoriser
            // Pour la production, on préfère bloquer par sécurité
            throw new Exception('Impossible de vérifier la sécurité du fichier');
        }
    }

    /**
     * Vérifie si ClamAV est disponible
     */
    private function isClamAvAvailable(): bool
    {
        // Vérifier si le socket existe
        return file_exists('/var/run/clamav/clamd.sock') || 
               file_exists('/tmp/clamd.sock') ||
               $this->isClamAvCommandAvailable();
    }

    /**
     * Vérifie si la commande clamdscan est disponible
     */
    private function isClamAvCommandAvailable(): bool
    {
        $output = [];
        $returnCode = 0;
        
        @exec('which clamdscan 2>/dev/null', $output, $returnCode);
        
        return $returnCode === 0 && !empty($output);
    }

    /**
     * Scan en utilisant la commande clamdscan (fallback)
     */
    private function scanWithCommand(UploadedFile $file): bool
    {
        $command = sprintf('clamdscan --no-summary %s 2>&1', escapeshellarg($file->getRealPath()));
        
        $output = [];
        $returnCode = 0;
        
        exec($command, $output, $returnCode);
        
        $output = implode("\n", $output);
        
        if ($returnCode === 1) {
            Log::alert('Malicious file detected via clamdscan', [
                'file' => $file->getClientOriginalName(),
                'output' => $output,
                'ip' => request()->ip(),
            ]);
            
            throw new Exception('Fichier malveillant détecté');
        }

        if ($returnCode !== 0) {
            Log::warning('clamdscan failed', [
                'file' => $file->getClientOriginalName(),
                'return_code' => $returnCode,
                'output' => $output,
            ]);
            return true;
        }

        return true;
    }
}
