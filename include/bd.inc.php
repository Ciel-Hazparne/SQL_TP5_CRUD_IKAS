<?php
function pdo_connect_mysql()
{
    // Informations de connexion à la base de données (identifiants à adapter selon votre configuration locale)
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'admin';
    $DATABASE_PASS = 'admin';
    $DATABASE_NAME = 'sql_tp5_crud';

    try {
        // Création de la connexion PDO :
        // - 'mysql' = type de base de données
        // - 'host' = serveur de la base (ici local)
        // - 'dbname' = nom de la base
        // - 'charset=utf8mb4' = encodage des échanges
        $conn = new PDO(
            'mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8mb4',
            $DATABASE_USER,
            $DATABASE_PASS
        );

        // Activation du mode d'erreur pour afficher les exceptions si une requête échoue
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Si la connexion est réussie, on retourne l'objet PDO à utiliser dans les scripts
        return $conn;

    } catch (PDOException $exception) {
        // En cas d'erreur de connexion (mauvais identifiants, base inexistante...), on arrête le script avec un message
        exit('Erreur de connexion à la base de donnée : ' . $exception->getMessage());
    }
}
