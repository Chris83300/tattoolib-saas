# 📋 METTRE À JOUR CLAUDE.md — 5 Règles de travail agents

## Objectif
Ajouter une section "Règles de travail" dans CLAUDE.md pour que
Claude Code applique systématiquement ces 5 principes à chaque session.

---

## ACTION UNIQUE

Lire `CLAUDE.md` à la racine, puis ajouter cette section
**en HAUT du fichier**, juste après le titre principal :

```markdown
---

## 🤖 RÈGLES DE TRAVAIL — À APPLIQUER SYSTÉMATIQUEMENT

Ces 5 règles s'appliquent à **chaque tâche**, sans exception.

---

### RÈGLE 1 — PLANIFIER avant de coder
Avant d'écrire la moindre ligne de code :
1. Lire tous les fichiers concernés
2. Identifier les dépendances et impacts
3. Lister les étapes dans l'ordre
4. Identifier les risques et cas limites
5. Valider le plan **avant** de commencer

> ❌ Ne jamais commencer à coder sans avoir compris l'ensemble du contexte.

---

### RÈGLE 2 — DÉLÉGUER via sous-agents
Pour les tâches complexes (> 3 fichiers ou > 2 domaines) :
- Décomposer en sous-tâches indépendantes
- Traiter chaque sous-tâche de façon isolée et testable
- Valider chaque sous-tâche avant de passer à la suivante
- Ne jamais modifier plus de 5 fichiers en une seule passe

> ✅ Préférer 3 petites tâches validées à 1 grosse tâche risquée.

---

### RÈGLE 3 — S'AUTO-AMÉLIORER via logs
À chaque correction :
1. **Avant** : logger le comportement actuel (attendu vs observé)
2. **Pendant** : commenter pourquoi la solution choisie résout le problème
3. **Après** : vérifier dans les logs que le problème est résolu
4. **Documenter** : noter la cause racine + solution dans le rapport final

Format de log standardisé :
```php
Log::info('[NomFix] Avant: {problème}');
// correction
Log::info('[NomFix] Après: {comportement attendu}');
```

> 🎯 Objectif : ne jamais reproduire deux fois la même erreur.

---

### RÈGLE 4 — TOUT TESTER avant de valider
Pour chaque modification :

**Tests obligatoires :**
```bash
# 1. Syntaxe PHP
php artisan route:cache 2>&1 | head -5

# 2. Routes impactées
php artisan route:list | grep <pattern_modifié>

# 3. Test fonctionnel Artisan
php artisan tinker --execute="/* vérifier le comportement */"

# 4. Pas de régression évidente
php artisan about 2>&1 | grep -i "error\|warning"
```

**Si des tests Pest/PHPUnit existent :**
```bash
php artisan test --filter=<NomTest>
```

> ❌ Ne jamais déclarer une tâche terminée sans avoir testé.

---

### RÈGLE 5 — CORRIGER les bugs jusqu'à résolution complète
En cas d'échec d'un test :
1. **Lire** les logs d'erreur en entier (pas seulement la première ligne)
2. **Identifier** la cause racine (pas seulement le symptôme)
3. **Corriger** en ciblant la cause (pas un patch superficiel)
4. **Re-tester** avec exactement le même test qu'avant
5. **S'améliorer** : documenter la cause + solution pour éviter la récurrence

**Cycle obligatoire :**
```
Test → Échec → Log → Cause racine → Fix → Re-test → Succès → Documenter
```

> 🔄 Ne jamais laisser un test en échec. Si bloqué après 3 tentatives :
> expliquer le problème en détail plutôt que de contourner.

---

### RÉCAPITULATIF RAPIDE
```
1. PLANIFIER  → Lire, comprendre, lister les étapes
2. DÉLÉGUER   → Sous-tâches isolées, max 5 fichiers par passe
3. AMÉLIORER  → Logs avant/après, documenter cause + solution
4. TESTER     → route:cache + tinker + tests unitaires
5. CORRIGER   → Cause racine, re-test, documenter
```

---
```

Placer cette section immédiatement après le titre `# 🎨 CLAUDE.md — Contexte Ink&Pik`.

Ensuite, mettre à jour la section **"MISE À JOUR DE CE FICHIER"** pour ajouter :
```markdown
**Déclencher aussi une mise à jour CLAUDE.md après :**
- Résolution d'un bug complexe → documenter la cause + solution
- Découverte d'un nouveau piège → l'ajouter à la section "PIÈGES CONNUS"
- Changement de règle de sécurité → mettre à jour la section sécurité
```

---

## VALIDATION

```bash
# Vérifier que CLAUDE.md a bien été mis à jour
head -80 CLAUDE.md | grep -c "RÈGLE"
# Doit retourner 5

wc -l CLAUDE.md
# Doit être > 150 lignes
```

## ⚠️ Contraintes
- Ajouter EN HAUT du fichier (avant le reste du contexte)
- Ne pas modifier les sections existantes
- Rapport : CLAUDE.md mis à jour + nombre de lignes
