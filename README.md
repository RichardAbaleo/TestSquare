# TEST TECHNIQUE BACK-END

Test effectué sur :

- Ubuntu 20
- Database Mysql

## Configuration nécessaire

- php : >=7.2.5
- composer d'installé
- git d'installé
- identifiants de l'API "La Bonne Boite v1"

## Installation

Voici les étapes à suivre pour utiliser ce projet Symfony 5.4^ :

### Cloner le repository :

```
git clone https://github.com/RichardAbaleo/TestSquare.git
cd /TestSquare
composer install
```

### Paramétrer le .env:

```
CLIENT_ID=!ChangeMe!
CLIENT_SECRET=!ChangeMe!
DATABASE_URL="mysql://!USERNAME!:!PASSWORD!@127.0.0.1:3306/!DBNAME!?serverVersion=8&charset=utf8mb4"
```

### Créer la bdd :

```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### Insérer données dans la bdd :

- Un fichier `cities.data.sql` avec les données au format sql se trouve dans `public/doc/`
  - Personnellement j'ai utilisé PhpMyAdmin avec la fonction import

## Application

### Démarrer le serveur

```
symfony server:start
```

Une fois démarrée, l'application est accessible sur :

- http://localhost:8000

### Utilisation

Deux données sont à rentrer :

- Le nom de la ville (exemple : Nantes)
- Le domaine de travail (exemple : Boucher)

Le nombre de tokens restants sont affichés en haut à droite une fois la recherche effectué.

Les résultats sont juste dump(), mais ils sont bien sûr utilisable pour un front perso.
