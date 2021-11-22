<?php

$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://zipcodebase-zip-code-search.p.rapidapi.com/search?codes=10005",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => [
        "x-rapidapi-host: zipcodebase-zip-code-search.p.rapidapi.com",
        "x-rapidapi-key: d00a0414e7msh006e50839e0dd28p185197jsn305b41e61d7e"
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    echo $response;
}
