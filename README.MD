# Développement Web : Deefy App

[Lien du repository github](https://github.com/Stantrh/Dev_Web-TD_15_TROHA_Stanislas_S3A)

## TROHA Stanislas S3A | TD_15

Le fichier contenu dans /config/.ini contient les informations relatives à la connexion à la base de données. Il ressemble à :

```
driver=mysql
username=user
password=passwd
host=localhost
database=databaseName
```

Il faut donc modifier les champs suivants par les vôtres.
`username` par le nom de l'utilisateur phpmyadmin qui peut accéder à la base de données  
`password` par le mot de passe de l'utilisateur défini ci-dessus
`database` le nom de la base de données phpmyadmin qui contient les données relatives à deefy (voir fichier deefy_db.sql)

### Librairies utilisées :

[Codeguy/Upload_v1_3](https://packagist.org/packages/codeguy/upload) pour l'upload de pistes sur le serveur.
[Composer](https://getcomposer.org/) pour l'autoloader et namespaces.

### Rendu :

Tout le projet phpstorm avec la configuration de l'autoloader (composer.json)

```
{
  "name": "td_15/Rendu_Final",
  "description": "Projet fini",
  "type": "project",
  "autoload": {
    "psr-4": {
      "iutnc\\deefy\\": "./src/classes"
  }
  },
  "require": {
    "codeguy/upload": "^1.3",
    "ext-pdo": "*",
    "ext-json": "*"
  }
}
```
