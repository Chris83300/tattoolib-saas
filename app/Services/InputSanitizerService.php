<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;

class InputSanitizerService
{
    /**
     * Nettoie complètement le texte (supprime tous les tags HTML)
     */
    public function sanitizeText(string $input): string
    {
        // Supprimer tous les tags HTML
        $cleaned = strip_tags($input);
        
        // Supprimer les caractères de contrôle sauf sauts de ligne
        $cleaned = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $cleaned);
        
        // Normaliser les espaces
        $cleaned = preg_replace('/\s+/', ' ', trim($cleaned));
        
        return $cleaned;
    }
    
    /**
     * Nettoie le texte riche (autorise certains tags HTML sécurisés)
     */
    public function sanitizeRichText(string $input): string
    {
        // Si HTMLPurifier est disponible, l'utiliser
        if (class_exists('\Mews\Purifier\Facades\Purifier')) {
            return \Mews\Purifier\Facades\Purifier::clean($input, 'basic');
        }
        
        // Fallback : autoriser uniquement les tags de base
        $allowedTags = '<p><br><strong><em><u><ol><ul><li>';
        $cleaned = strip_tags($input, $allowedTags);
        
        // Supprimer les attributs dangereux
        $cleaned = preg_replace('/\s*on\w+\s*=\s*["\']?[^"\']*["\']?/i', '', $cleaned);
        $cleaned = preg_replace('/\s*javascript\s*:/i', '', $cleaned);
        $cleaned = preg_replace('/\s*vbscript\s*:/i', '', $cleaned);
        $cleaned = preg_replace('/\s*data\s*:/i', '', $cleaned);
        
        return $cleaned;
    }
    
    /**
     * Nettoie et valide une URL
     */
    public function sanitizeUrl(string $url): string
    {
        if (empty($url)) {
            return '';
        }
        
        // Validation et nettoyage de l'URL
        $url = filter_var($url, FILTER_SANITIZE_URL);
        
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return '';
        }
        
        // Parser l'URL pour valider le schéma
        $parsed = parse_url($url);
        
        if (!$parsed || !isset($parsed['scheme'])) {
            return '';
        }
        
        // Autoriser uniquement http et https
        if (!in_array(strtolower($parsed['scheme']), ['http', 'https'])) {
            return '';
        }
        
        return $url;
    }
    
    /**
     * Nettoie les entrées numériques
     */
    public function sanitizeNumeric(string $input): string
    {
        return filter_var($input, FILTER_SANITIZE_NUMBER_INT);
    }
    
    /**
     * Nettoie les emails
     */
    public function sanitizeEmail(string $email): string
    {
        return filter_var($email, FILTER_SANITIZE_EMAIL);
    }
    
    /**
     * Échappe les caractères spéciaux pour l'affichage
     */
    public function escape(string $input): string
    {
        return htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    
    /**
     * Nettoie pour les noms de fichiers
     */
    public function sanitizeFilename(string $filename): string
    {
        // Supprimer les caractères dangereux
        $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);
        
        // Limiter la longueur
        $filename = substr($filename, 0, 100);
        
        // Éviter les noms réservés
        $reserved = ['CON', 'PRN', 'AUX', 'NUL', 'COM1', 'COM2', 'COM3', 'COM4', 'COM5', 'COM6', 'COM7', 'COM8', 'COM9', 'LPT1', 'LPT2', 'LPT3', 'LPT4', 'LPT5', 'LPT6', 'LPT7', 'LPT8', 'LPT9'];
        
        $nameWithoutExt = pathinfo($filename, PATHINFO_FILENAME);
        if (in_array(strtoupper($nameWithoutExt), $reserved)) {
            $filename = '_' . $filename;
        }
        
        return $filename;
    }
    
    /**
     * Valide et nettoie le contenu JSON
     */
    public function sanitizeJson(string $json): string
    {
        json_decode($json);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return '';
        }
        
        return $json;
    }
    
    /**
     * Nettoie le contenu pour les meta descriptions
     */
    public function sanitizeMetaDescription(string $input): string
    {
        $cleaned = $this->sanitizeText($input);
        
        // Limiter à 160 caractères pour SEO
        return Str::limit($cleaned, 160);
    }
    
    /**
     * Détecte si une chaîne contient du code potentiellement dangereux
     */
    public function containsSuspiciousContent(string $input): bool
    {
        $suspiciousPatterns = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
            '/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/mi',
            '/<object\b[^<]*(?:(?!<\/object>)<[^<]*)*<\/object>/mi',
            '/<embed\b[^<]*(?:(?!<\/embed>)<[^<]*)*<\/embed>/mi',
            '/javascript:/i',
            '/vbscript:/i',
            '/data:text\/html/i',
            '/on\w+\s*=/i',
        ];
        
        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }
        
        return false;
    }
}
