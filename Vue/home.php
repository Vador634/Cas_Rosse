<?php
$bornes = $bornes ?? [];
$stations = $stations ?? [];
$typesBorne = $typesBorne ?? [];
$techniciensDispo = $techniciensDispo ?? [];
$message = $message ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion ROSSE</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        h1 { color: #333; }
        h2 { color: #555; border-bottom: 2px solid #007bff; padding-bottom: 5px; }
        .container { display: flex; flex-wrap: wrap; gap: 20px; margin-bottom: 20px; }
        .card { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); flex: 1; min-width: 280px; }
        form { display: flex; flex-direction: column; gap: 10px; }
        label { font-weight: bold; font-size: 14px; }
        input, select { padding: 8px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        button { padding: 10px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 14px; }
        button:hover { background: #0056b3; }
        .message { padding: 10px; margin-bottom: 15px; border-radius: 4px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 30px; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #007bff; color: white; }
        tr:hover { background: #f1f1f1; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 12px; font-size: 12px; color: white; }
        .badge-green { background: #28a745; }
        .badge-orange { background: #fd7e14; }
        .badge-red { background: #dc3545; }
    </style>
</head>
<body>

<h1>Gestion des Bornes et Techniciens - ROSSE</h1>

<?php if (!empty($message)): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<div class="container">
    <div class="card">
        <h2>Créer une Station</h2>
        <form method="POST">
            <input type="hidden" name="action" value="creerStation">
            <label>Libellé emplacement</label>
            <input type="text" name="libelleEmplacement" required>
            <button type="submit">Créer la Station</button>
        </form>
    </div>

    <div class="card">
        <h2>Créer un Type de Borne</h2>
        <form method="POST">
            <input type="hidden" name="action" value="creerTypeBorne">
            <label>Code type borne</label>
            <input type="text" name="codeTypeBorne" required>
            <label>Durée révision (heures)</label>
            <input type="number" name="dureeRevision" required>
            <label>Nb jours entre révisions</label>
            <input type="number" name="nbJoursEntreRevisions" required>
            <label>Nb unités entre révisions</label>
            <input type="number" name="nbUnitesEntreRevisions" required>
            <button type="submit">Créer le Type</button>
        </form>
    </div>
</div>

<div class="container">
    <div class="card">
        <h2>Créer une Borne</h2>
        <form method="POST">
            <input type="hidden" name="action" value="creerBorne">
            <label>Date dernière révision</label>
            <input type="date" name="dateDerniereRevision" required>
            <label>Indice compteur unités(Kw/h)</label>
            <input type="number" name="indiceCompteurUnites" required>
            <label>Type de borne</label>
            <select name="codeTypeBorne" required>
                <option value="">-- Choisir un type --</option>
                <?php foreach ($typesBorne as $type): ?>
                    <option value="<?= htmlspecialchars($type->getCodeTypeBorne()) ?>">
                        <?= htmlspecialchars($type->getCodeTypeBorne()) ?> (révision: <?= $type->getDureeRevision() ?>h)
                    </option>
                <?php endforeach; ?>
            </select>
            <label>Station</label>
            <select name="idStation" required>
                <option value="">-- Choisir une station --</option>
                <?php foreach ($stations as $station): ?>
                    <option value="<?= $station->getIdStation() ?>">
                        <?= htmlspecialchars($station->getLibelleEmplacement()) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Créer la Borne</button>
        </form>
    </div>

    <div class="card">
        <h2>Créer un Technicien</h2>
        <form method="POST">
            <input type="hidden" name="action" value="creerTechnicien">
            <label>Matricule</label>
            <input type="number" name="matricule" required>
            <label>Nom</label>
            <input type="text" name="nom" required>
            <label>Prénom</label>
            <input type="text" name="prenom" required>
            <button type="submit">Créer le Technicien</button>
        </form>
    </div>
</div>

<div class="container">
    <div class="card">
        <h2>Attribuer une Borne à un Technicien</h2>
        <form method="POST">
            <input type="hidden" name="action" value="attribuerBorne">
            <label>Technicien (trié par disponibilité)</label>
            <select name="matricule" required>
                <option value="">-- Choisir un technicien --</option>
                <?php foreach ($techniciensDispo as $item): ?>
                    <?php $t = $item['technicien']; ?>
                    <option value="<?= $t->getMatricule() ?>">
                        <?= htmlspecialchars($t->getNomComplet()) ?> (<?= $item['totalHeures'] ?>h - <?= $item['nbVisites'] ?> visites)
                    </option>
                <?php endforeach; ?>
            </select>
            <label>Borne</label>
            <select name="idBorne" required>
                <option value="">-- Choisir une borne --</option>
                <?php foreach ($bornes as $borne): ?>
                    <option value="<?= $borne->getIdBorne() ?>">
                        Borne #<?= $borne->getIdBorne() ?> (Station <?= $borne->getIdStation() ?>)
                    </option>
                <?php endforeach; ?>
            </select>
            <label>Durée de la visite (heures)</label>
            <input type="number" name="dureeTotale" min="1" required>
            <button type="submit">Attribuer</button>
        </form>
    </div>
</div>

<h2>Techniciens triés par disponibilité (moins d'heures en premier)</h2>
<table>
    <thead>
        <tr>
            <th>Matricule</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Heures totales</th>
            <th>Nb Visites</th>
            <th>Charge</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($techniciensDispo as $item): ?>
            <?php
                $t = $item['technicien'];
                $h = $item['totalHeures'];
                if ($h == 0) $badgeClass = 'badge-green';
                elseif ($h <= 10) $badgeClass = 'badge-orange';
                else $badgeClass = 'badge-red';
            ?>
            <tr>
                <td><?= $t->getMatricule() ?></td>
                <td><?= htmlspecialchars($t->getNom()) ?></td>
                <td><?= htmlspecialchars($t->getPrenom()) ?></td>
                <td><?= $h ?>h</td>
                <td><?= $item['nbVisites'] ?></td>
                <td><span class="badge <?= $badgeClass ?>">
                    <?php if ($h == 0) echo 'Libre';
                          elseif ($h <= 10) echo 'Modéré';
                          else echo 'Chargé'; ?>
                </span></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>Aperçu des Stations</h2>
<table>
    <thead>
        <tr>
            <th>ID Station</th>
            <th>Emplacement</th>
            <th>Nb Bornes</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($stations as $station): ?>
            <?php
                $nbBornes = 0;
                foreach ($bornes as $borne) {
                    if ($borne->getIdStation() == $station->getIdStation()) $nbBornes++;
                }
            ?>
            <tr>
                <td><?= $station->getIdStation() ?></td>
                <td><?= htmlspecialchars($station->getLibelleEmplacement()) ?></td>
                <td><?= $nbBornes ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<h2>Types de Borne</h2>
<table>
    <thead>
        <tr>
            <th>Code</th>
            <th>Durée révision</th>
            <th>Jours entre révisions</th>
            <th>Unités entre révisions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($typesBorne as $type): ?>
            <tr>
                <td><?= htmlspecialchars($type->getCodeTypeBorne()) ?></td>
                <td><?= $type->getDureeRevision() ?>h</td>
                <td><?= $type->getNbJoursEntreRevisions() ?></td>
                <td><?= $type->getNbUnitesEntreRevisions() ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
