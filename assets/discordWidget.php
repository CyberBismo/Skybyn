<?php
$serverId = '1094880679814246470';  // Replace with your server ID
$token = skybyn("discord_token");    // Replace with your bot token

$url = "https://discord.com/api/guilds/$serverId/widget.json";

$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Authorization: Bot ' . $token
));
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>
