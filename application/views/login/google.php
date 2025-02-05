<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function getGoogleTokenInfo($token) {
    $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . $token;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["token"])) {
    $token = $_POST["token"];
    $client_id = "687851700073-kr1husplkvfn92tcbh0o7l7db025islb.apps.googleusercontent.com";

    $data = getGoogleTokenInfo($token);

    if (!$data) {
        echo json_encode(["success" => false, "error" => "Error al obtener datos del token"]);
        exit;
    }

    if (isset($data["aud"]) && $data["aud"] === $client_id) {
        echo json_encode([
            "success" => true,
            "nombre" => $data["name"],
            "email" => $data["email"],
            "foto" => $data["picture"]
        ]);
    } else {
        echo json_encode(["success" => false, "error" => "Token inválido"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Token no recibido"]);
}
?>
