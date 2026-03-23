<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CsvExportHelper
{
    /**
     * Génère un fichier CSV téléchargeable à partir d'une collection.
     * Séparateur ; (standard FR pour Excel), BOM UTF-8 pour les accents.
     */
    public static function download(Collection $data, array $headings, string $filename): StreamedResponse
    {
        return response()->streamDownload(function () use ($data, $headings) {
            $handle = fopen('php://output', 'w');

            // BOM UTF-8 — indispensable pour qu'Excel FR affiche les accents
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, $headings, ';');

            foreach ($data as $row) {
                fputcsv($handle, array_values(is_array($row) ? $row : $row->toArray()), ';');
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
