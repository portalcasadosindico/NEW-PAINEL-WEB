<?php
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.autentique.com.br/v2/graphql",
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS =>"{\n    \"query\": \"query { documents(limit: 2, page: 1) { data { id } } }\",\n    \"variables\": {}\n}",
  CURLOPT_HTTPHEADER => array(
    "Authorization: Bearer 1ac476673c4bbf172a290c6aa470f923efb50e839d515ed463e2eeee29e878b3",
    "Content-Type: application/json"
  ),
));
$response = curl_exec($curl);
curl_close($curl);
echo $response;