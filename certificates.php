<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$cookieEmail = explode(',', $_COOKIE['login']);
$cookieEmail = $cookieEmail[0];

$config = array(
    "countryName" => "LV",
    "stateOrProvinceName" => "Zemgale",
    "localityName" => "Jelgava",
    "organizationName" => "Private",
    "organizationalUnitName" => "CD",
    "commonName" => $cookieEmail,
    "emailAddress" => $cookieEmail
);

$privateKey = openssl_pkey_new(array(
    "private_key_bits" => 2048,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
));

$csr = openssl_csr_new($config, $privateKey, array('digest_alg' => 'sha256'));
	
if($handle = fopen("ssCA.crt", 'r')) {
    $ca = fread($handle, 10000);
    fclose($handle);
}

if($handle = fopen("ssCA.key", 'r')) {
    $ca_key = fread($handle, 10000);
    fclose($handle);
}

$newCertificate = openssl_csr_sign($csr, $ca, $ca_key, 365, array('digest_alg' => 'sha256'));

openssl_x509_export($newCertificate, $certificate, true);

$file = fopen("client.crt", 'w');

fwrite($file, $certificate);
fclose($file);

openssl_pkcs12_export_to_file($certificate, './client-package.p12', $privateKey, "");

if(file_exists('./client-package.p12')) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename('./client-package.p12').'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize('./client-package.p12'));
    flush();
    readfile('./client-package.p12');
    die();
} else {
    http_response_code(404);
    die();
}

header('Location: ./index.php');
