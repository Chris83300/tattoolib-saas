@extends('layouts.app')

@section('title', 'Documents de Conformité - Ink&Pik')

@section('content')
    <div class="min-h-screen bg-noir-profond py-8">
        <div class="container-custom px-4">
            <div class="max-w-6xl mx-auto">

                <!-- Header -->
                <div class="mb-8">
                    <a href="{{ route('tattooer.compliance') }}"
                        class="inline-flex items-center text-ivoire-text/70 hover:text-ivoire-text mb-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                        Retour à la conformité
                    </a>
                    <h1 class="text-4xl md:text-5xl font-display font-bold text-ivoire-text mb-4">
                        Mes Documents de Conformité
                    </h1>
                    <p class="text-xl text-ivoire-text/70">
                        Téléchargez et gérez vos certificats et attestations
                    </p>
                </div>

                <!-- Messages flash -->
                @if (session('success'))
                    <div class="bg-vert-succes/20 border border-vert-succes text-vert-succes px-6 py-4 rounded-lg mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-rouge/20 border border-rouge text-rouge px-6 py-4 rounded-lg mb-6">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="grid lg:grid-cols-2 gap-8">
                    <!-- Formulaire d'upload -->
                    <div class="bg-gris-fonde rounded-xl p-8">
                        <h2 class="text-2xl font-bold text-ivoire-text mb-6">Ajouter des documents</h2>

                        <form action="{{ route('tattooer.compliance.documents.upload') }}" method="POST"
                            enctype="multipart/form-data" class="space-y-6">
                            @csrf

                            <!-- Sélection multiple des types de documents -->
                            <div>
                                <label class="block text-ivoire-text font-semibold mb-3">Types de documents à télécharger
                                    *</label>
                                <div class="space-y-3">
                                    <label
                                        class="flex items-center p-3 bg-noir-profond/50 rounded-lg border border-titane/20 cursor-pointer hover:border-beige-peau/50 transition-colors">
                                        <input type="checkbox" name="certification_types[]" value="hygiene_salubrite"
                                            class="mr-3 w-4 h-4 text-beige-peau">
                                        <div>
                                            <div class="font-medium text-ivoire-text"> Hygiène & Salubrité</div>
                                            <div class="text-sm text-ivoire-text/60">Formation obligatoire hygiène et
                                                salubrité</div>
                                        </div>
                                    </label>

                                    <label
                                        class="flex items-center p-3 bg-noir-profond/50 rounded-lg border border-titane/20 cursor-pointer hover:border-beige-peau/50 transition-colors">
                                        <input type="checkbox" name="certification_types[]" value="declaration_ars"
                                            class="mr-3 w-4 h-4 text-beige-peau">
                                        <div>
                                            <div class="font-medium text-ivoire-text"> Déclaration ARS</div>
                                            <div class="text-sm text-ivoire-text/60">Déclaration auprès de l'ARS</div>
                                        </div>
                                    </label>

                                    <label
                                        class="flex items-center p-3 bg-noir-profond/50 rounded-lg border border-titane/20 cursor-pointer hover:border-beige-peau/50 transition-colors">
                                        <input type="checkbox" name="certification_types[]" value="certibiocide"
                                            class="mr-3 w-4 h-4 text-beige-peau">
                                        <div>
                                            <div class="font-medium text-ivoire-text"> Certibiocide TP2</div>
                                            <div class="text-sm text-ivoire-text/60">Certification utilisation biocides
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Champs communs (tous optionnels si fichiers fournis) -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-ivoire-text">Informations générales <span
                                        class="text-titane text-sm font-normal">(optionnel si images fournies)</span></h3>

                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-ivoire-text font-medium mb-2">Numéro de certificat</label>
                                        <input type="text" name="certificate_number"
                                            class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:outline-none focus:border-beige-peau"
                                            placeholder="Ex: HYG-2024-001">
                                    </div>
                                    <div>
                                        <label class="block text-ivoire-text font-medium mb-2">Organisme de
                                            formation</label>
                                        <input type="text" name="training_organization"
                                            class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:outline-none focus:border-beige-peau"
                                            placeholder="Ex: Centre de Formation Tattoo">
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-ivoire-text font-medium mb-2">Date d'obtention</label>
                                        <input type="date" name="obtained_at"
                                            class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:outline-none focus:border-beige-peau">
                                    </div>
                                    <div>
                                        <label class="block text-ivoire-text font-medium mb-2">Date d'expiration</label>
                                        <input type="date" name="expires_at"
                                            class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:outline-none focus:border-beige-peau">
                                    </div>
                                </div>
                            </div>

                            <!-- Champs spécifiques ARS -->
                            <div id="ars_fields" class="hidden space-y-4">
                                <h3 class="text-lg font-semibold text-ivoire-text">Informations ARS <span
                                        class="text-titane text-sm font-normal">(optionnel si image fournie)</span></h3>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-ivoire-text font-medium mb-2">Région ARS</label>
                                        <select name="ars_region"
                                            class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:outline-none focus:border-beige-peau">
                                            <option value="">Sélectionnez une région</option>
                                            <option value="Île-de-France">Île-de-France</option>
                                            <option value="Auvergne-Rhône-Alpes">Auvergne-Rhône-Alpes</option>
                                            <option value="Bourgogne-Franche-Comté">Bourgogne-Franche-Comté</option>
                                            <option value="Bretagne">Bretagne</option>
                                            <option value="Centre-Val de Loire">Centre-Val de Loire</option>
                                            <option value="Corse">Corse</option>
                                            <option value="Grand Est">Grand Est</option>
                                            <option value="Hauts-de-France">Hauts-de-France</option>
                                            <option value="Normandie">Normandie</option>
                                            <option value="Nouvelle-Aquitaine">Nouvelle-Aquitaine</option>
                                            <option value="Occitanie">Occitanie</option>
                                            <option value="Pays de la Loire">Pays de la Loire</option>
                                            <option value="Provence-Alpes-Côte d'Azur">Provence-Alpes-Côte d'Azur</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-ivoire-text font-medium mb-2">Numéro de déclaration
                                            ARS</label>
                                        <input type="text" name="ars_number"
                                            class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:outline-none focus:border-beige-peau"
                                            placeholder="Ex: ARS-2024-001">
                                    </div>
                                </div>
                            </div>

                            <!-- Champs spécifiques Certibiocide -->
                            <div id="certibiocide_fields" class="hidden">
                                <h3 class="text-lg font-semibold text-ivoire-text">Informations Certibiocide <span
                                        class="text-titane text-sm font-normal">(optionnel si image fournie)</span></h3>
                                <div>
                                    <label class="block text-ivoire-text font-medium mb-2">Type de biocide</label>
                                    <select name="biocide_type"
                                        class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:outline-none focus:border-beige-peau">
                                        <option value="">Sélectionnez un type</option>
                                        <option value="TP2">TP2</option>
                                        <option value="TP4">TP4</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Fichiers -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold text-ivoire-text">Fichiers à télécharger</h3>

                                <div>
                                    <label class="block text-ivoire-text font-medium mb-2">
                                        Document Hygiène et Salubrité (Image du certificat ou PDF) *
                                    </label>
                                    <input type="file" name="certificate_file" required accept=".pdf,.jpg,.jpeg,.png"
                                        class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:outline-none focus:border-beige-peau">
                                    <p class="text-ivoire-text/60 text-sm mt-2">
                                        Formats acceptés : PDF, JPG, JPEG, PNG (max 5MB)<br>
                                        <span class="text-beige-peau"> Astuce : Uploadez une photo claire de votre
                                            certificat, les champs textes deviendront optionnels</span>
                                    </p>
                                </div>

                                <div id="ars_proof_field" class="hidden">
                                    <label class="block text-ivoire-text font-medium mb-2">
                                        Preuve de déclaration ARS (Image ou PDF) - Optionnel
                                    </label>
                                    <input type="file" name="ars_proof_file" accept=".pdf,.jpg,.jpeg,.png"
                                        class="w-full px-4 py-3 bg-noir-profond border border-titane/30 rounded-lg text-ivoire-text focus:outline-none focus:border-beige-peau">
                                    <p class="text-ivoire-text/60 text-sm mt-2">
                                        Optionnel : accusé de réception ARS ou preuve de déclaration
                                    </p>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full px-6 py-3 bg-beige-peau text-noir-profond font-bold rounded-lg hover:bg-beige-peau/90 transition-colors">
                                Télécharger les documents
                            </button>
                        </form>
                    </div>

                    <!-- Liste des documents existants -->
                    <div class="bg-gris-fonde rounded-xl p-8">
                        <h2 class="text-2xl font-bold text-ivoire-text mb-6">Mes documents</h2>

                        @if ($complianceRecords->isEmpty())
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 mx-auto mb-4 text-titane" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <p class="text-ivoire-text/70">Aucun document téléchargé pour le moment</p>
                                <p class="text-ivoire-text/50 text-sm mt-2">Utilisez le formulaire à gauche pour ajouter
                                    vos documents</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach ($complianceRecords as $record)
                                    <div class="bg-noir-profond rounded-lg p-6 border border-titane/20">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-3">
                                                    <h3 class="text-lg font-bold text-ivoire-text">
                                                        {{ $record->getCertificationLabel() }}
                                                    </h3>
                                                    @switch($record->status)
                                                        @case('pending')
                                                            <span
                                                                class="px-3 py-1 bg-titane/20 text-titane text-sm rounded-full">En
                                                                attente de validation</span>
                                                        @break

                                                        @case('valid')
                                                            <span
                                                                class="px-3 py-1 bg-vert-succes/20 text-vert-succes text-sm rounded-full">Validé</span>
                                                        @break

                                                        @case('expired')
                                                            <span
                                                                class="px-3 py-1 bg-rouge/20 text-rouge text-sm rounded-full">Expiré</span>
                                                        @break

                                                        @default
                                                            <span
                                                                class="px-3 py-1 bg-titane/20 text-titane text-sm rounded-full">{{ $record->status }}</span>
                                                    @endswitch
                                                </div>

                                                @if ($record->certificate_number)
                                                    <p class="text-ivoire-text/70 mb-1"><strong>Numéro :</strong>
                                                        {{ $record->certificate_number }}</p>
                                                @endif

                                                @if ($record->training_organization)
                                                    <p class="text-ivoire-text/70 mb-1"><strong>Organisme :</strong>
                                                        {{ $record->training_organization }}</p>
                                                @endif

                                                <p class="text-ivoire-text/70 mb-1"><strong>Obtenu le :</strong>
                                                    {{ $record->obtained_at?->format('d/m/Y') }}</p>

                                                @if ($record->expires_at)
                                                    <p class="text-ivoire-text/70 mb-1"><strong>Expire le :</strong>
                                                        {{ $record->expires_at->format('d/m/Y') }}</p>
                                                @endif

                                                @if ($record->verified_at)
                                                    <p class="text-ivoire-text/70 mb-1"><strong>Validé le :</strong>
                                                        {{ $record->verified_at->format('d/m/Y') }} par
                                                        {{ $record->verifier?->name }}</p>
                                                @endif

                                                <!-- Liens vers les fichiers -->
                                                <div class="flex gap-4 mt-4">
                                                    @if ($record->certificate_file_path)
                                                        <a href="{{ route('tattooer.compliance.documents.serve', [$record, 'certificate_file_path']) }}"
                                                            target="_blank"
                                                            class="text-beige-peau hover:underline text-sm flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                                </path>
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                                </path>
                                                            </svg>
                                                            Voir le document
                                                        </a>
                                                    @endif

                                                    @if ($record->ars_proof_file_path)
                                                        <a href="{{ route('tattooer.compliance.documents.serve', [$record, 'ars_proof_file_path']) }}"
                                                            target="_blank"
                                                            class="text-beige-peau hover:underline text-sm flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z">
                                                                </path>
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                                </path>
                                                            </svg>
                                                            Voir la preuve ARS
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Actions -->
                                            <div class="flex gap-2 ml-4">
                                                @if ($record->status === 'pending')
                                                    <form
                                                        action="{{ route('tattooer.compliance.documents.delete', $record) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="p-2 text-rouge hover:bg-rouge/10 rounded-lg transition-colors">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('input[name="certification_types[]"]');
            const arsFields = document.getElementById('ars_fields');
            const certibiocideFields = document.getElementById('certibiocide_fields');
            const arsProofField = document.getElementById('ars_proof_field');
            const submitButton = document.querySelector('button[type="submit"]');

            function updateFieldsVisibility() {
                const selectedTypes = Array.from(checkboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);

                // Afficher/masquer les champs spécifiques
                const hasArs = selectedTypes.includes('declaration_ars');
                const hasCertibiocide = selectedTypes.includes('certibiocide');

                arsFields.classList.toggle('hidden', !hasArs);
                certibiocideFields.classList.toggle('hidden', !hasCertibiocide);
                arsProofField.classList.toggle('hidden', !hasArs);

                // Mettre à jour le bouton
                submitButton.textContent = selectedTypes.length > 1 ?
                    `Télécharger ${selectedTypes.length} documents` :
                    'Télécharger le document';
            }

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateFieldsVisibility);
            });

            updateFieldsVisibility(); // Initialisation
        });
    </script>
@endsection
