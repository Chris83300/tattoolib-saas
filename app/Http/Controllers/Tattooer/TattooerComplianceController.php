<?php

namespace App\Http\Controllers\Tattooer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TattooerComplianceController extends ArtisanBaseController
{
    public function compliance()
    {
        $tattooer = $this->artisan();
        return view('tattooer.compliance', compact('tattooer'));
    }

    public function complianceDocuments()
    {
        $tattooer = $this->artisan();
        $complianceRecords = $tattooer->complianceRecords()->with('verifier')->get();

        return view('tattooer.compliance-documents', compact('tattooer', 'complianceRecords'));
    }

    public function complianceDocumentsUpload(Request $request)
    {
        $tattooer = $this->artisan();

        $hasFile = $request->hasFile('certificate_file');
        $certificationTypes = $request->input('certification_types', []);

        if (empty($certificationTypes)) {
            return redirect()->back()->with('error', 'Veuillez sélectionner au moins un type de document.');
        }

        $request->validate([
            'certification_types' => 'required|array|min:1',
            'certification_types.*' => 'in:hygiene_salubrite,certibiocide,declaration_ars',
            'certificate_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'certificate_number' => 'nullable|string|max:255',
            'training_organization' => 'nullable|string|max:255',
            'obtained_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:obtained_at',
            'ars_region' => 'nullable|string|max:100',
            'ars_number' => 'nullable|string|max:100',
            'biocide_type' => 'nullable|string|max:50',
        ]);

        $baseData = $request->except(['certification_types', 'certificate_file', 'ars_proof_file']);

        // Si pas de fichier, on s'assure que les champs obligatoires sont présents
        if (!$hasFile) {
            $request->validate([
                'certificate_number' => 'required|string|max:255',
                'training_organization' => 'required|string|max:255',
                'obtained_at' => 'required|date',
            ]);
        }

        // Validation conditionnelle pour les champs spécifiques
        if (in_array('declaration_ars', $certificationTypes) && !$hasFile) {
            $request->validate([
                'ars_region' => 'required|string|max:100',
                'ars_number' => 'required|string|max:100',
            ]);
        }

        if (in_array('certibiocide', $certificationTypes) && !$hasFile) {
            $request->validate([
                'biocide_type' => 'required|string|max:50',
            ]);
        }

        // Gérer l'upload des fichiers par type
        $filePaths = [];
        $arsProofPath = null;

        if ($hasFile) {
            $file = $request->file('certificate_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('compliance-documents', $filename, 'local');

            // Créer une copie du fichier pour chaque type sélectionné
            foreach ($certificationTypes as $certificationType) {
                $typeFilename = time() . '_' . $certificationType . '_' . $file->getClientOriginalName();
                $typePath = $file->storeAs('compliance-documents', $typeFilename, 'local');
                $filePaths[$certificationType] = $typePath;
            }
        }

        // Gérer l'upload du fichier ARS si applicable
        if ($request->hasFile('ars_proof_file') && in_array('declaration_ars', $certificationTypes)) {
            $file = $request->file('ars_proof_file');
            $filename = time() . '_ars_' . $file->getClientOriginalName();
            $arsProofPath = $file->storeAs('compliance-documents', $filename, 'local');
        }

        $createdRecords = [];

        // Créer un enregistrement pour chaque type de document sélectionné
        foreach ($certificationTypes as $certificationType) {
            // Vérifier si un enregistrement existe déjà pour ce type
            $existingRecord = $tattooer->complianceRecords()
                ->where('certification_type', $certificationType)
                ->first();

            if ($existingRecord) {
                // Mettre à jour l'enregistrement existant
                $data = $baseData;
                $data['certificate_file_path'] = $filePaths[$certificationType] ?? null;
                $data['ars_proof_file_path'] = ($certificationType === 'declaration_ars') ? $arsProofPath : null;
                $data['status'] = 'pending'; // Repasser en pending pour nouvelle vérification

                // S'assurer que les champs nullables sont bien null si vides
                // Si obtained_at est requis mais qu'on a un fichier, on utilise la date du jour
                if (empty($data['obtained_at']) && $hasFile) {
                    $data['obtained_at'] = now()->format('Y-m-d');
                }

                $data['expires_at'] = $data['expires_at'] ?? null;
                $data['certificate_number'] = $data['certificate_number'] ?? null;
                $data['training_organization'] = $data['training_organization'] ?? null;
                $data['ars_region'] = $data['ars_region'] ?? null;
                $data['ars_number'] = $data['ars_number'] ?? null;
                $data['biocide_type'] = $data['biocide_type'] ?? null;

                $existingRecord->update($data);
                $createdRecords[] = $existingRecord->getCertificationLabel() . ' (mis à jour)';
            } else {
                // Créer un nouvel enregistrement
                $data = $baseData;
                $data['certification_type'] = $certificationType;
                $data['certificate_file_path'] = $filePaths[$certificationType] ?? null;
                $data['ars_proof_file_path'] = ($certificationType === 'declaration_ars') ? $arsProofPath : null;
                $data['status'] = 'pending';
                $data['compliant_type'] = get_class($tattooer);
                $data['compliant_id'] = $tattooer->id;

                // S'assurer que les champs nullables sont bien null si vides
                // Si obtained_at est requis mais qu'on a un fichier, on utilise la date du jour
                if (empty($data['obtained_at']) && $hasFile) {
                    $data['obtained_at'] = now()->format('Y-m-d');
                }

                $data['expires_at'] = $data['expires_at'] ?? null;
                $data['certificate_number'] = $data['certificate_number'] ?? null;
                $data['training_organization'] = $data['training_organization'] ?? null;
                $data['ars_region'] = $data['ars_region'] ?? null;
                $data['ars_number'] = $data['ars_number'] ?? null;
                $data['biocide_type'] = $data['biocide_type'] ?? null;

                $complianceRecord = \App\Models\ComplianceRecord::create($data);
                $createdRecords[] = $complianceRecord->getCertificationLabel();
            }
        }

        $message = count($createdRecords) > 1
            ? count($createdRecords) . ' documents téléchargés avec succès. Ils seront vérifiés par notre équipe dans les 48h.'
            : 'Document téléchargé avec succès. Il sera vérifié par notre équipe dans les 48h.';

        return redirect()->route('tattooer.compliance.documents')
            ->with('success', $message);
    }

    public function complianceDocumentServe(\App\Models\ComplianceRecord $complianceRecord, string $field)
    {
        $tattooer = $this->artisan();
        $user = auth()->user();

        // Les admins peuvent consulter tous les documents
        if ($user->isAdmin()) {
            // Pas de vérification supplémentaire pour les admins
        } elseif (is_null($tattooer)) {
            abort(403, 'Accès non autorisé');
        } elseif ($complianceRecord->compliant_type !== get_class($tattooer) || $complianceRecord->compliant_id !== $tattooer->id) {
            abort(403);
        }

        if (!in_array($field, ['certificate_file_path', 'ars_proof_file_path'])) {
            abort(404);
        }

        $path = $complianceRecord->$field;
        if (!$path || !Storage::disk('local')->exists($path)) {
            abort(404);
        }

        return Storage::disk('local')->response($path);
    }

    public function complianceDocumentDelete(\App\Models\ComplianceRecord $complianceRecord)
    {
        $tattooer = $this->artisan();

        // Vérifier que le document appartient bien à l'utilisateur
        if ($complianceRecord->compliant_type !== get_class($tattooer) || $complianceRecord->compliant_id !== $tattooer->id) {
            abort(403);
        }

        // Supprimer les fichiers physiques
        if ($complianceRecord->certificate_file_path) {
            Storage::disk('local')->delete($complianceRecord->certificate_file_path);
        }
        if ($complianceRecord->ars_proof_file_path) {
            Storage::disk('local')->delete($complianceRecord->ars_proof_file_path);
        }

        $complianceRecord->delete();

        return redirect()->route('tattooer.compliance.documents')
            ->with('success', 'Document supprimé avec succès.');
    }
}
