<?php
$encryptedString = 'OtSrzlB7n3MjD01XlzM4MfNeam1Z+oCnO3kEkxptuS4=';

$key = md5('automaze');

$decryptedData = openssl_decrypt($encryptedString, 'AES-256-CBC', $key);

echo "Decrypted string: " . $decryptedData;