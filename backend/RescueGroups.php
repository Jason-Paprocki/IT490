<?php
// CHANGE THIS TO MATCH YOUR ENVIRONMENT
const DB_HOST = "localhost";
const DB_DATABASE = "dogedb"; // the name of your database
const DB_USER = "joshua";        // the user or your database
const DB_PASSWORD = "password";   // the password of your database

// CREDENTIALS
const API_CLIENT_ID = 'XXXXXXXX';
// const API_CLIENT_SECRET = 'fb301c94b4a44f8286a26a268163f8c2';

// Variables
//$redirect_uri = 'http://localhost:8888/callback'; // not sure what the redirect url should be?

// Helper functions
function encode($client_id, $client_secret) {
    return base64_encode("$client_id:$client_secret");
}

function MakeAuthorizationHeaders($authorization) {
    return [
        "Authorization: Basic {$authorization}",
        "Content-Type: application/json"
    ];
}

function CallApi($token_url, $token_headers, $token_content) {
    $ch = curl_init();
    
    // set options, url, etc
    curl_setopt($ch, CURLOPT_HTTPHEADER, $token_headers);
    curl_setopt($ch, CURLOPT_URL, $token_url);
    
    curl_setopt($ch, CURLOPT_POSTFIELDS, $token_content);
    curl_setopt($ch, CURLOPT_POST, 1);
    
    //curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $result = curl_exec($ch);
    
    if (curl_errno($ch)) {
        $results = curl_error($ch);
    } else {
        curl_close($ch);
        $results = $result;
    }

    $resultsArray = json_decode($results);

    print_r($resultsArray);
}

function SaveToDatabase($username, $names, $ages, $breeds, $colors) {
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    foreach ($names as $name) {
        $animalname = $name['name'];
        $sql = "INSERT INTO animaldatabase(username, animalname) VALUES ('${username}', '${animalname}')";
        if (! mysqli_query($conn, $sql)) {
            die("Error: " . $sql . "" . mysqli_error($conn));
        }
    }

    foreach ($ages as $age) {
        $animalage = $age['age'];
        $sql = "INSERT INTO animaldatabase(username, animalage) VALUES ('${username}', '${animalage}')";
        if (! mysqli_query($conn, $sql)) {
            die("Error: " . $sql . "" . mysqli_error($conn));
        }
    }

    foreach ($breeds as $breed) {
        $animalbreed = $breed['breed'];
        $sql = "INSERT INTO animaldatabase(username, animalbreed) VALUES ('${username}', '${animalbreed}')";
        if (! mysqli_query($conn, $sql)) {
            die("Error: " . $sql . "" . mysqli_error($conn));
        }
    }

    foreach ($colors as $color) {
        $animalcolor = $color['color'];
        $sql = "INSERT INTO animaldatabase(username, animalcolor) VALUES ('${username}', '${animalcolor}')";
        if (! mysqli_query($conn, $sql)) {
            die("Error: " . $sql . "" . mysqli_error($conn));
        }
    }
    mysqli_close($conn);
}

function GetAnimalColor(){
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    {
        "apikey": "Li91u8iK",
        "objectType":"animalColors",
        "objectAction":"publicSearch",
        "search":
        {
            "resultStart": "0",
            "resultLimit": "100",
            "resultSort": "colorName",
            "resultOrder": "asc",
            "filters":
            [
                {
                    "fieldName": "colorSpecies",
                    "operation": "equals",
                    "criteria": "Dog"
                }
            ],
            "filterProcessing": "1",
            "fields": ["colorID","colorName","colorSpecies","colorSpeciesID"]
        }
    }
}
?>