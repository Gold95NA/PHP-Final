<?php
$apiKey = '69f3eef59d5944b7aaa6af836d6a8691'; 

$query = isset($_GET['q']) ? urlencode($_GET['q']) : '';
$url = "https://api.rawg.io/api/games?key=$apiKey&search=$query";

$response = file_get_contents($url);
$data = json_decode($response, true);

echo json_encode($data['results']);