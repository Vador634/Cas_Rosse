# ROSSE — Maintenance des bornes de recharge

Application web PHP (architecture **MVC + DAO**) qui gère le parc de bornes de recharge d'une entreprise fictive, **ROSSE**, et **affecte automatiquement chaque borne au technicien le moins chargé**.

Projet n°1 du **BTS SIO option SLAM**, réalisé à partir du cas d'étude « Rosse ».

---

## Fonctionnalités

- **Stations** : création et liste des emplacements, avec le nombre de bornes par station.
- **Types de borne** : durée d'une révision (en heures), intervalle en jours et en unités (kWh) entre deux révisions.
- **Bornes** : date de dernière révision, indice du compteur, type et station.
- **Techniciens** : création, avec contrôle des doublons de matricule.
- **Affectation automatique** : à la création d'une borne, une visite de révision est planifiée et confiée au technicien qui a le moins d'heures planifiées.
- **Affectation manuelle** : la liste des techniciens est triée du moins chargé au plus chargé.
- **Tableaux de bord** : charge de chaque technicien (Libre / Modéré / Chargé), planning des visites, liste des bornes.

## Points techniques

| Sujet | Mise en œuvre |
|---|---|
| Architecture | MVC : `Controleur/` (logique métier), `DAO/` (accès aux données), `Modele/` (classes métier), `Vue/` (affichage) |
| Base de données | MySQL / MariaDB via **PDO**, uniquement des requêtes préparées |
| Intégrité | L'affectation (visite + technicien + borne) est faite dans une **transaction** |
| Sécurité | Jeton **CSRF** sur chaque formulaire, échappement HTML de toutes les sorties |
| Ergonomie | Pattern **Post/Redirect/Get** : rafraîchir la page ne renvoie pas le formulaire, messages de succès et d'erreur distincts |
| Validation | Champs obligatoires, dates `AAAA-MM-JJ`, entiers positifs, codes uniques |

## Modèle de données

```
Station 1──n Borne n──1 TypeBorne
   │            │
   1            n
   │            │
   n         Visite_Borne
 Visite ────────┘
   │
   n
Technicien_Visite n──1 Technicien
```

Une **visite** a un état : `p` (planifiée), `a` (en cours) ou `r` (réalisée).

## Installation

**Prérequis** : PHP 7.3 ou plus et MySQL / MariaDB (XAMPP, WAMP ou Laragon conviennent).

1. Cloner le dépôt dans le dossier web du serveur (ex. `htdocs/` ou `www/`) :
   ```bash
   git clone https://github.com/Vador634/Cas_Rosse.git
   ```
2. Importer `Rosse_Bdd.sql` dans phpMyAdmin (ou `mysql -u root < Rosse_Bdd.sql`).
   Le script crée la base `rosse_bdd`, les tables et quelques **données de démonstration**.
3. Vérifier les identifiants dans `Config/config.php` (par défaut `root` sans mot de passe sur `localhost`).
   Ils peuvent aussi être fournis par variables d'environnement : `ROSSE_DB_HOST`, `ROSSE_DB_NAME`, `ROSSE_DB_USER`, `ROSSE_DB_PASS`.
4. Ouvrir `http://localhost/Cas_Rosse/`.

## Arborescence

```
Cas_Rosse/
├── index.php              Point d'entrée : traitement des formulaires et affichage
├── Config/config.php      Connexion PDO
├── Controleur/            Borne, Station, Technicien, TypeBorne, Visite, Maintenance
├── DAO/                   Requêtes SQL de chaque table
├── Modele/                Classes métier (Borne, Station, Technicien, TypeBorne, Visite)
├── Vue/home.php           Interface
└── Rosse_Bdd.sql          Création de la base et données de démonstration
```

## Auteur

**Kilian Carasco** — étudiant en Bachelor Concepteur Développeur d'Applications (EPSI Bordeaux)
[Portfolio](https://kiliandev.netlify.app) · [GitHub](https://github.com/Vador634) · [LinkedIn](https://www.linkedin.com/in/kilian-carasco-029915399/)
