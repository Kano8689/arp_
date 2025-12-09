<?php

$key = '12345678901234567890123456789012'; // 32-char secret key for AES-256

// convert into code
function Encrypt($password) {
      global $key;
      $cipher = 'AES-256-CBC';
      $ivLength = openssl_cipher_iv_length($cipher);
      $iv = openssl_random_pseudo_bytes($ivLength);
      $encrypted = openssl_encrypt($password, $cipher, $key, OPENSSL_RAW_DATA, $iv);
      return base64_encode($iv . $encrypted);
}

// convert into original
function Decrypt($encryptedPassword) {
    global $key;
    $cipher = 'AES-256-CBC';
    $ivLength = openssl_cipher_iv_length($cipher);
    $data = base64_decode($encryptedPassword);
    $iv = substr($data, 0, $ivLength);
    $encrypted = substr($data, $ivLength);
    return openssl_decrypt($encrypted, $cipher, $key, OPENSSL_RAW_DATA, $iv);
}

$password = 'MyP@_$w0r<!-/11==11';
// $encrypted = Encrypt($password);
// $decrypted = Decrypt($encrypted);

// echo "Original: " . htmlspecialchars($password) . "<br>";
// echo "Encrypted: " . htmlspecialchars($encrypted) . "<br>";
// echo "Decrypted: " . htmlspecialchars($decrypted) . "<br>";

?>
