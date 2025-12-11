<?php
// Configurações do Cloudinary
$cloud_name = 'Root';
$api_key = '661713323243317';
$api_secret = 'AnnZk5KvrZa6so1-vssJ-luFldU';

// Arquivo enviado via formulário
$foto = $_FILES['foto']['tmp_name'];
$nome_original = $_FILES['foto']['name'];

// URL de upload
$url = "https://api.cloudinary.com/v1_1/$cloud_name/image/upload";

// Dados do POST
$post_fields = [
    'file' => new CURLFile($foto),
    'upload_preset' => '' // opcional, se você criar no Cloudinary
];

// Inicializa cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
curl_setopt($ch, CURLOPT_USERPWD, $api_key . ":" . $api_secret); // autenticação básica

// Executa o upload
$response = curl_exec($ch);
curl_close($ch);

// Converte a resposta JSON
$data = json_decode($response, true);

// URL da imagem
$foto_url = $data['secure_url'] ?? null;

if ($foto_url) {
    echo "Upload feito com sucesso: $foto_url";
    // Aqui você pode salvar no MySQL
} else {
    echo "Erro no upload: " . $response;
}
?>