<?php
header("Content-Type: application/json");

$cloud_name = 'dqsodebo9';
$upload_preset = 'barbearia_senai';

if (!isset($_FILES['foto'])) {
    echo json_encode(["success" => false, "error" => "Ficheiro não recebido"]);
    exit;
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://api.cloudinary.com/v1_1/$cloud_name/image/upload");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'file' => new CURLFile($_FILES['foto']['tmp_name']),
    'upload_preset' => $upload_preset
]);
// Ignora SSL para evitar erros em ambiente local (XAMPP)
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err = curl_error($ch);
curl_close($ch);

if ($err) {
    echo json_encode(["success" => false, "error" => "Erro de rede: " . $err]);
} else {
    $res = json_decode($response, true);
    if ($http_code === 200) {
        echo json_encode(["success" => true, "url" => $res['secure_url']]);
    } else {
        echo json_encode(["success" => false, "error" => $res['error']['message'] ?? "Erro Cloudinary"]);
    }
}