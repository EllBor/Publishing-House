<?php

$security = new \yii\base\Security();
$secretKey = $security->generateRandomString(32);
return [
    'adminEmail' => 'admin@example.com',
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'jwtSecretKey' => $secretKey,
];
