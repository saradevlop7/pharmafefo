# PharmaFEFO

Application de gestion de stock pharmaceutique avec règle FEFO (First Expired, First Out).

## Prérequis

- PHP 8.0+
- MySQL 8.0+
- Serveur web (Apache ou PHP built-in server)

## Installation

1. Cloner le projet :
```bash
git clone https://github.com/saradevlop7/pharmafefo.git
cd pharmafefo
```

2. Créer la base de données :
```bash
mysql -u root < database.sql
```

3. Configurer la connexion dans `config/database.php` si nécessaire.

4. Lancer le serveur de développement :
```bash
php -S localhost:8000 -t public
```

5. Ouvrir http://localhost:8000 dans le navigateur.

## Connexion par défaut

- **Utilisateur** : `admin`
- **Mot de passe** : `admin123`

## Architecture MVC

```
pharmafefo/
├── config/
│   └── database.php          # Connexion PDO
├── public/
│   ├── css/
│   │   └── style.css          # Styles CSS
│   └── index.php              # Routeur principal
├── src/
│   ├── Controller/
│   │   ├── AuthController.php
│   │   ├── BatchController.php
│   │   ├── MedicationController.php
│   │   ├── ReportController.php
│   │   └── StockController.php
│   ├── Entity/
│   │   ├── Batch.php
│   │   ├── Medication.php
│   │   └── User.php
│   ├── Enum/
│   │   └── BatchStatus.php
│   └── Repository/
│       ├── BatchRepository.php
│       ├── MedicationRepository.php
│       └── UserRepository.php
├── templates/
│   ├── alerts.php
│   ├── batch_receive.php
│   ├── batches.php
│   ├── dashboard.php
│   ├── layout.php
│   ├── login.php
│   ├── medication_form.php
│   ├── medications.php
│   ├── report_losses.php
│   ├── stock_out.php
│   ├── user_form.php
│   └── users.php
├── database.sql               # Script SQL
└── README.md
```

## Fonctionnalités

1. **Authentification** avec 3 rôles : Administrateur, Pharmacien, Préparateur
2. **Gestion des médicaments** : CRUD complet
3. **Gestion des lots** : numéro, date de péremption, quantité, statut
4. **Réception de stock** : validation de la date de péremption
5. **Alertes de péremption** : Vert (> 6 mois), Orange (< 90 jours), Rouge (< 30 jours)
6. **Filtre des lots critiques** (Rouge)
7. **Règle FEFO** : sortie de stock automatique par lot le plus proche de la péremption
8. **Déclaration de lot expiré** : statut EXPIRED, stock = 0
9. **Rapport des pertes financières** dues aux lots expirés

## Technologies

- PHP 8+ natif
- PDO / MySQL
- Architecture MVC
- Enum PHP 8.1
- CSS natiF
- <img width="1276" height="844" alt="image" src="https://github.com/user-attachments/assets/f5244646-4904-4141-97fc-37b88c438cc2" />
<img width="1633" height="895" alt="image" src="https://github.com/user-attachments/assets/8120bb9b-5773-4f84-abb5-3c01a5454751" />
<img width="1302" height="889" alt="image" src="https://github.com/user-attachments/assets/2f713c81-28cb-47e5-b965-ba0b9e399f98" />



- 
