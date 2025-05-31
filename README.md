# Plateforme de Visioconférence

## Comment Configurer le Serveur

### Configuration de la Base de Données
1. **Créez une base de données MySQL** et exécutez le script SQL fourni.
2. **Mettez à jour les identifiants** dans le fichier `db_connect.php`.

### Installation des Dépendances
Exécutez la commande suivante pour installer les dépendances nécessaires :
```bash
composer require cboden/ratchet
```

### Démarrer le Serveur WebSocket
Lancez le serveur WebSocket avec la commande :
```bash
php ws_server.php
```

> **Remarque :** Assurez-vous que tous les fichiers PHP sont dans le dossier `api`.

## Résumé et Avantages
- **Authentification complète** : Inscription, connexion et gestion de session.
- **Base de données MySQL** : Stockage des utilisateurs et des salles.
- **Serveur WebSocket** : Signalisation entre utilisateurs distants.
- **Interface utilisateur améliorée** : Mode sombre et liste des salles récentes.
- **Sécurité renforcée** : Hashage des mots de passe et vérification des sessions.
- **Meilleure gestion des salles** : Codes uniques et délais d'expiration.

