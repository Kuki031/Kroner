<?php

declare(strict_types=1);
require_once("fileLoader.php");

function connectToDatabase() {
    $cfg = loadFile("database/config.php");

    try {
        $dsn = "{$cfg['driver']}:host={$cfg['host']};port={$cfg['port']};dbname={$cfg['dbname']};charset=utf8";
        
        $pdo = new PDO($dsn, $cfg['username'], $cfg['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $pdo;
        
    } catch (PDOException $e) {
        echo "connection failed" . $e->getMessage();
    }
}
