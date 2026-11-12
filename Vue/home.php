<?php
$bornes = $bornes ?? [];
$stations = $stations ?? [];
$typesBorne = $typesBorne ?? [];
$techniciensDispo = $techniciensDispo ?? [];
$planning = $planning ?? [];
$flash = $flash ?? null;
$csrf = $csrf ?? '';

// Libellés utiles pour l'affichage
$nomStation = [];
foreach ($stations as $station) {
    $nomStation[$station->getIdStation()] = $station->getLibelleEmplacement();
}
$etats = ['p' => 'Planifiée', 'a' => 'En cours', 'r' => 'Réalisée'];

function e($valeur) {
    return htmlspecialchars((string)$valeur, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ROSSE — Maintenance des bornes</title>
    <style>
        :root { --bleu: #1f5fbf; --bleu-fonce: #17478f; --fond: #f3f5f9; --ligne: #dde3ec; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: "Segoe UI", Arial, sans-serif; background: var(--fond); color: #1d2533; }
        header { padding: 22px 24px; background: var(--bleu); color: #fff; }
        header h1 { margin: 0; font-size: 22px; }
        header p { margin: 4px 0 0; opacity: .85; font-size: 14px; }
        main { max-width: 1200px; margin: 0 auto; padding: 24px; }
        h2 { margin: 32px 0 12px; font-size: 18px; color: var(--bleu-fonce); border-bottom: 2px solid var(--bleu); padding-bottom: 6px; }
        .grille { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 16px; }
        .card { background: #fff; padding: 18px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        .card h3 { margin: 0 0 12px; font-size: 16px; }
        form { display: flex; flex-direction: column; gap: 8px; }
        label { font-weight: 600; font-size: 13px; }
        input, select { padding: 8px; border: 1px solid #c4ccd8; border-radius: 4px; font-size: 14px; }
        button { margin-top: 4px; padding: 10px; background: var(--bleu); color: #fff; border: 0; border-radius: 4px; cursor: pointer; font-size: 14px; }
        button:hover { background: var(--bleu-fonce); }
        .message { padding: 12px 14px; margin-bottom: 18px; border-radius: 6px; border: 1px solid; }
        .message.ok { background: #e3f6e8; color: #155724; border-color: #b9e3c4; }
        .message.erreur { background: #fde8e8; color: #8a1c1c; border-color: #f3c0c0; }
        .table-wrap { overflow-x: auto; background: #fff; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,.08); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px 12px; text-align: left; border-bottom: 1px solid var(--ligne); font-size: 14px; white-space: nowrap; }
        th { background: #eaf0fa; color: var(--bleu-fonce); }
        tr:last-child td { border-bottom: 0; }
        .vide { color: #6b7686; font-style: italic; }
        .badge { display: inline-block; padding: 2px 9px; border-radius: 12px; font-size: 12px; color: #fff; }
        .badge-green { background: #28a745; } .badge-orange { background: #e8730c; } .badge-red { background: #dc3545; } .badge-blue { background: var(--bleu); }
        .astuce { font-size: 12px; color: #6b7686; margin: 0; }
    </style>
</head>
<body>

<header>
    <h1>ROSSE — Maintenance des bornes de recharge</h1>
    <p>Stations, bornes, techniciens et affectation automatique des visites de révision</p>
</header>

<main>

<?php if ($flash && $flash['texte'] !== ''): ?>
    <div class="message <?= $flash['ok'] ? 'ok' : 'erreur' ?>" role="status"><?= e($flash['texte']) ?></div>
<?php endif; ?>

<h2>Paramétrage</h2>
<div class="grille">
    <div class="card">
        <h3>Nouvelle station</h3>
        <form method="POST">
            <input type="hidden" name="action" value="creerStation">
            <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
            <label for="libelleEmplacement">Libellé de l'emplacement</label>
            <input type="text" id="libelleEmplacement" name="libelleEmplacement" maxlength="100" required>
            <button type="submit">Créer la station</button>
        </form>
    </div>

    <div class="card">
        <h3>Nouveau type de borne</h3>
        <form method="POST">
            <input type="hidden" name="action" value="creerTypeBorne">
            <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
            <label for="codeTypeBorne">Code (10 caractères max.)</label>
            <input type="text" id="codeTypeBorne" name="codeTypeBorne" maxlength="10" required>
            <label for="dureeRevision">Durée d'une révision (heures)</label>
            <input type="number" id="dureeRevision" name="dureeRevision" min="1" required>
            <label for="nbJoursEntreRevisions">Jours entre deux révisions</label>
            <input type="number" id="nbJoursEntreRevisions" name="nbJoursEntreRevisions" min="1" required>
            <label for="nbUnitesEntreRevisions">Unités (kWh) entre deux révisions</label>
            <input type="number" id="nbUnitesEntreRevisions" name="nbUnitesEntreRevisions" min="1" required>
            <button type="submit">Créer le type</button>
        </form>
    </div>

    <div class="card">
        <h3>Nouveau technicien</h3>
        <form method="POST">
            <input type="hidden" name="action" value="creerTechnicien">
            <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
            <label for="matricule">Matricule</label>
            <input type="number" id="matricule" name="matricule" min="1" required>
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" minlength="2" maxlength="50" required>
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" minlength="2" maxlength="50" required>
            <button type="submit">Créer le technicien</button>
        </form>
    </div>
</div>

<h2>Bornes et affectations</h2>
<div class="grille">
    <div class="card">
        <h3>Nouvelle borne</h3>
        <form method="POST">
            <input type="hidden" name="action" value="creerBorne">
            <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
            <label for="dateDerniereRevision">Date de dernière révision</label>
            <input type="date" id="dateDerniereRevision" name="dateDerniereRevision" required>
            <label for="indiceCompteurUnites">Indice du compteur (kWh)</label>
            <input type="number" id="indiceCompteurUnites" name="indiceCompteurUnites" min="0" required>
            <label for="codeTypeBorneBorne">Type de borne</label>
            <select id="codeTypeBorneBorne" name="codeTypeBorne" required>
                <option value="">-- Choisir un type --</option>
                <?php foreach ($typesBorne as $type): ?>
                    <option value="<?= e($type->getCodeTypeBorne()) ?>">
                        <?= e($type->getCodeTypeBorne()) ?> (révision : <?= e($type->getDureeRevision()) ?> h)
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="idStation">Station</label>
            <select id="idStation" name="idStation" required>
                <option value="">-- Choisir une station --</option>
                <?php foreach ($stations as $station): ?>
                    <option value="<?= e($station->getIdStation()) ?>"><?= e($station->getLibelleEmplacement()) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Créer la borne</button>
            <p class="astuce">La borne est automatiquement confiée au technicien le moins chargé.</p>
        </form>
    </div>

    <div class="card">
        <h3>Attribuer une borne manuellement</h3>
        <form method="POST">
            <input type="hidden" name="action" value="attribuerBorne">
            <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
            <label for="matriculeAttrib">Technicien (du moins chargé au plus chargé)</label>
            <select id="matriculeAttrib" name="matricule" required>
                <option value="">-- Choisir un technicien --</option>
                <?php foreach ($techniciensDispo as $item): $t = $item['technicien']; ?>
                    <option value="<?= e($t->getMatricule()) ?>">
                        <?= e($t->getNomComplet()) ?> (<?= e($item['totalHeures']) ?> h · <?= e($item['nbVisites']) ?> visite(s))
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="idBorne">Borne</label>
            <select id="idBorne" name="idBorne" required>
                <option value="">-- Choisir une borne --</option>
                <?php foreach ($bornes as $borne): ?>
                    <option value="<?= e($borne->getIdBorne()) ?>">
                        Borne #<?= e($borne->getIdBorne()) ?> — <?= e($nomStation[$borne->getIdStation()] ?? 'Station ' . $borne->getIdStation()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <label for="dureeTotale">Durée de la visite (heures)</label>
            <input type="number" id="dureeTotale" name="dureeTotale" min="1" required>
            <button type="submit">Attribuer</button>
        </form>
    </div>
</div>

<h2>Charge des techniciens</h2>
<div class="table-wrap">
<table>
    <thead><tr><th>Matricule</th><th>Nom</th><th>Prénom</th><th>Heures planifiées</th><th>Visites</th><th>Charge</th></tr></thead>
    <tbody>
    <?php if (!$techniciensDispo): ?>
        <tr><td colspan="6" class="vide">Aucun technicien enregistré.</td></tr>
    <?php endif; ?>
    <?php foreach ($techniciensDispo as $item):
        $t = $item['technicien'];
        $h = (int)$item['totalHeures'];
        if ($h === 0) { $classe = 'badge-green'; $libelle = 'Libre'; }
        elseif ($h <= 10) { $classe = 'badge-orange'; $libelle = 'Modéré'; }
        else { $classe = 'badge-red'; $libelle = 'Chargé'; }
    ?>
        <tr>
            <td><?= e($t->getMatricule()) ?></td>
            <td><?= e($t->getNom()) ?></td>
            <td><?= e($t->getPrenom()) ?></td>
            <td><?= $h ?> h</td>
            <td><?= e($item['nbVisites']) ?></td>
            <td><span class="badge <?= $classe ?>"><?= $libelle ?></span></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>

<h2>Planning des visites</h2>
<div class="table-wrap">
<table>
    <thead><tr><th>Visite</th><th>État</th><th>Borne</th><th>Station</th><th>Technicien</th><th>Durée</th></tr></thead>
    <tbody>
    <?php if (!$planning): ?>
        <tr><td colspan="6" class="vide">Aucune visite planifiée pour le moment.</td></tr>
    <?php endif; ?>
    <?php foreach ($planning as $v): ?>
        <tr>
            <td>#<?= e($v['idVisite']) ?></td>
            <td><span class="badge badge-blue"><?= e($etats[$v['etat']] ?? $v['etat']) ?></span></td>
            <td><?= $v['idBorne'] ? '#' . e($v['idBorne']) . ' (' . e($v['codeTypeBorne']) . ')' : '—' ?></td>
            <td><?= e($v['libelleEmplacement']) ?></td>
            <td><?= $v['matricule'] ? e($v['prenom'] . ' ' . $v['nom']) . ' (' . e($v['matricule']) . ')' : '—' ?></td>
            <td><?= e($v['dureeTotale']) ?> h</td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>

<h2>Bornes</h2>
<div class="table-wrap">
<table>
    <thead><tr><th>Borne</th><th>Type</th><th>Station</th><th>Dernière révision</th><th>Compteur</th></tr></thead>
    <tbody>
    <?php if (!$bornes): ?>
        <tr><td colspan="5" class="vide">Aucune borne enregistrée.</td></tr>
    <?php endif; ?>
    <?php foreach ($bornes as $borne): ?>
        <tr>
            <td>#<?= e($borne->getIdBorne()) ?></td>
            <td><?= e($borne->getCodeTypeBorne()) ?></td>
            <td><?= e($nomStation[$borne->getIdStation()] ?? $borne->getIdStation()) ?></td>
            <td><?= e(date('d/m/Y', strtotime($borne->getDateDerniereRevision()))) ?></td>
            <td><?= e($borne->getIndiceCompteurUnites()) ?> kWh</td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>

<h2>Stations et types de borne</h2>
<div class="grille">
    <div class="table-wrap">
    <table>
        <thead><tr><th>Station</th><th>Emplacement</th><th>Bornes</th></tr></thead>
        <tbody>
        <?php if (!$stations): ?>
            <tr><td colspan="3" class="vide">Aucune station.</td></tr>
        <?php endif; ?>
        <?php foreach ($stations as $station):
            $nbBornes = 0;
            foreach ($bornes as $borne) {
                if ($borne->getIdStation() == $station->getIdStation()) $nbBornes++;
            }
        ?>
            <tr>
                <td>#<?= e($station->getIdStation()) ?></td>
                <td><?= e($station->getLibelleEmplacement()) ?></td>
                <td><?= $nbBornes ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <div class="table-wrap">
    <table>
        <thead><tr><th>Type</th><th>Révision</th><th>Tous les</th><th>Ou tous les</th></tr></thead>
        <tbody>
        <?php if (!$typesBorne): ?>
            <tr><td colspan="4" class="vide">Aucun type de borne.</td></tr>
        <?php endif; ?>
        <?php foreach ($typesBorne as $type): ?>
            <tr>
                <td><?= e($type->getCodeTypeBorne()) ?></td>
                <td><?= e($type->getDureeRevision()) ?> h</td>
                <td><?= e($type->getNbJoursEntreRevisions()) ?> jours</td>
                <td><?= e($type->getNbUnitesEntreRevisions()) ?> kWh</td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>

</main>
</body>
</html>
