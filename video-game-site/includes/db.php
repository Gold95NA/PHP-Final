<?php
$host = 'mysql.neit.edu';
$port = 5500;
$db   = 'dev_202510_asnalle';
$user = 'dev_202510_asnalle';
$pass = '008024227';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}