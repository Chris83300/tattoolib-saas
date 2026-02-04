<!-- Modal acceptation (Alpine.js) -->
<div x-show="showModal" x-cloak
    class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4"
    @click.self="showModal = false" style="display: none;" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">

    <!-- Modal container -->
    <div class="bg-gris-fonde rounded-2xl border border-beige-peau/20 shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto"
        @click.stop>

        <!-- Header -->
        <div class="sticky top-0 bg-gris-fonde border-b border-beige-peau/20 p-6 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-ivoire-text">Accepter la demande</h2>
            <button @click="showModal = false" class="text-ivoire-text/60 hover:text-rouge-alerte">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>

        <!-- Form -->
        <form action="{{ route('tattooer.request.accept', $bookingRequest) }}" method="POST" class="p-6 space-y-8"
            id="accept-form">
            @csrf

            <!-- 1. Estimation projet -->
            <div class="space-y-4">
                <h3 class="text-xl font-bold text-ivoire-text flex items-center gap-2">
                    <span
                        class="w-8 h-8 bg-beige-peau rounded-full flex items-center justify-center text-noir-profond font-bold">1</span>
                    💰 Estimation du projet
                </h3>

                <div class="bg-noir-profond/50 rounded-xl p-4 space-y-4">
                    <!-- Fourchette -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-ivoire-text/80 text-sm font-semibold mb-2">
                                Prix minimum (€) <span class="text-rouge-alerte">*</span>
                            </label>
                            <input type="number" name="price_range_min" step="0.01" required placeholder="300"
                                class="w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-2 focus:ring-beige-peau/20">
                        </div>
                        <div>
                            <label class="block text-ivoire-text/80 text-sm font-semibold mb-2">
                                Prix maximum (€) <span class="text-rouge-alerte">*</span>
                            </label>
                            <input type="number" name="price_range_max" step="0.01" required placeholder="450"
                                class="w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-2 focus:ring-beige-peau/20">
                        </div>
                    </div>

                    <!-- Tarif estimé final sera calculé après versement de l'acompte -->
                </div>
            </div>

            <!-- 2. Dates proposées -->
            <div class="space-y-4">
                <h3 class="text-xl font-bold text-ivoire-text flex items-center gap-2">
                    <span
                        class="w-8 h-8 bg-beige-peau rounded-full flex items-center justify-center text-noir-profond font-bold">2</span>
                    📅 Proposition de rendez-vous
                </h3>

                <div class="bg-noir-profond/50 rounded-xl p-4">
                    <p class="text-ivoire-text/60 text-sm mb-4">Proposez 1 à 3 dates au client</p>

                    <div id="dates-container" class="space-y-3">
                        <input type="date" name="proposed_dates[]" required
                            min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            class="w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-2 focus:ring-beige-peau/20">
                    </div>

                    <button type="button" onclick="addDateField()"
                        class="mt-3 text-beige-peau hover:text-beige-peau/80 text-sm font-semibold flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Ajouter une date (max 3)
                    </button>
                </div>
            </div>

            <!-- 3. Phase création -->
            <div class="space-y-4">
                <h3 class="text-xl font-bold text-ivoire-text flex items-center gap-2">
                    <span
                        class="w-8 h-8 bg-beige-peau rounded-full flex items-center justify-center text-noir-profond font-bold">3</span>
                    🎨 Phase de création
                </h3>

                <div class="bg-noir-profond/50 rounded-xl p-4 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-ivoire-text/80 text-sm font-semibold mb-2">
                                Nombre de dessins inclus <span class="text-rouge-alerte">*</span>
                            </label>
                            <select name="included_design_versions" required
                                class="w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau">
                                <option value="1">1 dessin</option>
                                <option value="2" selected>2 dessins</option>
                                <option value="3">3 dessins</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-ivoire-text/80 text-sm font-semibold mb-2">
                                Modifications par dessin <span class="text-rouge-alerte">*</span>
                            </label>
                            <input type="number" name="modifications_per_version" value="2" min="0"
                                max="5" required
                                class="w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau">
                        </div>
                    </div>

                    <div>
                        <label class="block text-ivoire-text/80 text-sm font-semibold mb-2">
                            Règles de modification
                        </label>
                        <select name="design_modification_rules"
                            class="w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau">
                            <option value="Ajustements mineurs uniquement">Ajustements mineurs uniquement</option>
                            <option value="Refonte complète autorisée">Refonte complète autorisée</option>
                            <option value="Aucune modification">Aucune modification</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- 4. Acompte -->
            <div class="space-y-4">
                <h3 class="text-xl font-bold text-ivoire-text flex items-center gap-2">
                    <span
                        class="w-8 h-8 bg-beige-peau rounded-full flex items-center justify-center text-noir-profond font-bold">4</span>
                    💳 Acompte
                </h3>

                <div class="bg-noir-profond/50 rounded-xl p-4 space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-ivoire-text/80 text-sm font-semibold mb-2">
                                Montant acompte (€) <span class="text-rouge-alerte">*</span>
                            </label>
                            <input type="number" name="total_deposit_amount" step="0.01" required
                                placeholder="100"
                                class="w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau">
                        </div>
                        <div>
                            <label class="block text-ivoire-text/80 text-sm font-semibold mb-2">
                                Délai de paiement (jours) <span class="text-rouge-alerte">*</span>
                            </label>
                            <input type="number" name="client_payment_deadline_days" value="7" min="1"
                                max="30" required
                                class="w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau">
                        </div>
                    </div>

                    <div>
                        <label class="block text-ivoire-text/80 text-sm font-semibold mb-2">
                            Ce que couvre l'acompte
                        </label>
                        <textarea name="deposit_covers_description" rows="2"
                            placeholder="Ex: Temps de création du dessin + réservation du créneau"
                            class="w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau resize-none"></textarea>
                    </div>
                </div>
            </div>

            <!-- 5. Message personnalisé -->
            <div class="space-y-4">
                <h3 class="text-xl font-bold text-ivoire-text flex items-center gap-2">
                    <span
                        class="w-8 h-8 bg-beige-peau rounded-full flex items-center justify-center text-noir-profond font-bold">5</span>
                    💬 Message au client
                </h3>

                <div class="bg-noir-profond/50 rounded-xl p-4">
                    <textarea name="tattooer_notes" rows="4" placeholder="Message personnalisé pour le client..."
                        class="w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau resize-none"></textarea>
                </div>
            </div>

            <!-- Boutons -->
            <div class="flex gap-4 pt-6 border-t border-beige-peau/20">
                <button type="submit"
                    class="flex-1 px-6 py-4 bg-vert-succes text-noir-profond rounded-xl font-bold text-lg hover:bg-vert-succes/90 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    ✓ Valider et envoyer au client
                </button>
                <button type="button" @click="showModal = false"
                    class="px-6 py-4 bg-noir-profond border-2 border-beige-peau/20 text-ivoire-text rounded-xl font-semibold hover:bg-beige-peau/10 transition-all">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>
</div>

<script>
    function addDateField() {
        const container = document.getElementById('dates-container');
        const currentCount = container.querySelectorAll('input[type="date"]').length;

        if (currentCount >= 3) {
            alert('⚠️ Maximum 3 dates proposées');
            return;
        }

        const input = document.createElement('input');
        input.type = 'date';
        input.name = 'proposed_dates[]';
        input.required = false;
        input.min = new Date(Date.now() + 86400000).toISOString().split('T')[0];
        input.className =
            'w-full px-4 py-3 bg-noir-profond border border-beige-peau/20 rounded-lg text-ivoire-text focus:border-beige-peau focus:ring-2 focus:ring-beige-peau/20';

        container.appendChild(input);
    }

    // Validation des prix et formulaire
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('accept-form');
        if (!form) return;

        const priceMin = form.querySelector('[name="price_range_min"]');
        const priceMax = form.querySelector('[name="price_range_max"]');
        const priceEstimated = form.querySelector('[name="estimated_total_price"]');
        const depositAmount = form.querySelector('[name="total_deposit_amount"]');
        const deadlineDays = form.querySelector('[name="client_payment_deadline_days"]');
        const designVersions = form.querySelector('[name="included_design_versions"]');
        const modificationsPerVersion = form.querySelector('[name="modifications_per_version"]');

        // Validation fourchette prix
        function validatePrices() {
            const min = parseFloat(priceMin?.value) || 0;
            const max = parseFloat(priceMax?.value) || 0;
            const estimated = parseFloat(priceEstimated?.value) || 0;
            const deposit = parseFloat(depositAmount?.value) || 0;

            // Max > Min
            if (priceMax && max <= min && min > 0) {
                priceMax.setCustomValidity('Le prix maximum doit être supérieur au minimum');
            } else if (priceMax) {
                priceMax.setCustomValidity('');
            }

            // Estimé dans fourchette
            if (priceEstimated && estimated > 0) {
                if (estimated < min || estimated > max) {
                    priceEstimated.setCustomValidity(`Le tarif estimé doit être entre ${min}€ et ${max}€`);
                } else {
                    priceEstimated.setCustomValidity('');
                }
            }

            // Acompte raisonnable (max 50% du prix estimé)
            if (depositAmount && estimated > 0 && deposit > estimated * 0.5) {
                depositAmount.setCustomValidity('L\'acompte ne peut pas dépasser 50% du prix estimé');
            } else if (depositAmount) {
                depositAmount.setCustomValidity('');
            }
        }

        // Validation délais et quantités
        function validateLimits() {
            const deadline = parseInt(deadlineDays?.value) || 0;
            const versions = parseInt(designVersions?.value) || 0;
            const modifications = parseInt(modificationsPerVersion?.value) || 0;

            // Délai acompte entre 1 et 30 jours
            if (deadlineDays && (deadline < 1 || deadline > 30)) {
                deadlineDays.setCustomValidity('Le délai doit être entre 1 et 30 jours');
            } else if (deadlineDays) {
                deadlineDays.setCustomValidity('');
            }

            // Versions de dessin entre 1 et 3
            if (designVersions && (versions < 1 || versions > 3)) {
                designVersions.setCustomValidity('Le nombre de dessins doit être entre 1 et 3');
            } else if (designVersions) {
                designVersions.setCustomValidity('');
            }

            // Modifications entre 0 et 5 par version
            if (modificationsPerVersion && (modifications < 0 || modifications > 5)) {
                modificationsPerVersion.setCustomValidity('Le nombre de modifications doit être entre 0 et 5');
            } else if (modificationsPerVersion) {
                modificationsPerVersion.setCustomValidity('');
            }
        }

        // Ajouter les écouteurs d'événements
        [priceMin, priceMax, priceEstimated, depositAmount].forEach(element => {
            if (element) element.addEventListener('input', validatePrices);
        });

        [deadlineDays, designVersions, modificationsPerVersion].forEach(element => {
            if (element) element.addEventListener('input', validateLimits);
        });

        // Validation avant soumission
        form.addEventListener('submit', function(e) {
            validatePrices();
            validateLimits();

            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();

                // Afficher les erreurs
                const firstInvalid = form.querySelector(':invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                    firstInvalid.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            }
        });
    });
</script>
