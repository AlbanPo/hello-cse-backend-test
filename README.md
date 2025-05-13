# 😀 Hello CSE API - Laravel 10

**API sécurisée de gestion de profils avec système d'authentification et commentaires**

# 🏋‍ Fonctionnalités

- 💐 Authentification via Sanctum (JWTT)
- 👨‍💻 Gestion CRUD des profils
- 💼 – Système de commentaires
- 🖼️ Upload d'images
- 📊 Gestion des statuts (actif/inactif/en attente)
- 💉 Validation des données robuste
- 🛱 Tests unitaires

# 🧰 ‍ Installation

```bash
# 1. Cloner le dâpâpt
git clone https://github.com/AlbanPo/hello-cse-backend-test.git
cd hello-cse-backend-test

# 2. Installer les dépendances
composer install

# 3. Configurer l'environnement
cp .env.example .env
artisan key:generate

# 4. Configurer la base de données (dans .env)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hello_cse_api
DB_USERNAME=root
DB_PASSWORD=

# 5. Migrer et peupler la DB
artisan migrate --seed

# 6. Lancer le serveur
artisan serve
```

# 🐡 ‍ Endpoints API

## Authentification
|Méthode | Endpoint       | Description          |
|-------|---------------|--------------------|
| POST    | `/api/login`   | Connexion           |
| POST    | `/api/logout` | Déconnexion          |

## Profils
| Méthode | Endpoint               | Description          |
|-------|----------------------|-------------------|
| GET     | `/api/profiles`       | Lister profils       |
| POST     | `/api/profiles`       | Créer profil         |
| GET     | `/api/profiles/{id}`  | Voir profil          |
| PUT      | `/api/profiles/{id}`  | Modifier profil      |
| DELETE   | `/api/profiles/{id}`  | Suppprimer profil    |

## Commentaires
| Méthode | Endpoint                       | Description          |
|-------|-----------------------------|------------------|
| POST    | `/api/profiles/{id}/comments` | Ajouter commentaire  |

# 🐡 ‍ Exemple Requête

```bash
curl -X POST http://localhost:8000/api/profiles \
  -H "Authorization: Bearer votre_token" \
  -H "Content-Type: multipart/form-data" \
  -F "first_name=Jean" \
  -F "last_name=Dupont" \
  -F "status=active" \
  -F "image=@photo.jpg"
```

# 🛱 ‍ Tests

```bash
# Lancer les tests
php artisan test

# Avec couverture
php artisan test --coverage

# Analyse statique
composer analyse
```

# 💄 ‍ Sécurité

- Authentification par tokens JWTT
- Protection CSRF
- Validation stricte des inputs
- Hash bcrypt pour les mots de passe
- Middleware d'authentification

# 🚁 ‍ Structure du Projet

```
app/
__   // Contrôleurs
__   |   |- Modèles
__   |   |- Services
__   |   |- Policies
__   |   |- Providers
config/
database/
__   |- factories
__   |- migrations
__   |- seeders
routes/
tests/
```
---

**Auteur**: Alban Poirel
**Version**: 1.0.0  
**Dernière mise à jour**: 2025-05-15
