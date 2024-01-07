<?php
require 'C:\xampp\htdocs\Szakdolgozat\vendor\autoload.php'; // Elérési út megadása a Composer autoloader fájlhoz

// Alpha Vantage API kulcs
$api_key = 'A24WPD2HR3XC3PUD';

// Ellenőrizzük, hogy van-e keresési kifejezés
$search_term = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($search_term)) {
    // Az API végpont URL-je a részvények lekérdezésére
    $url = 'https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=' . $search_term . '&apikey=' . $api_key;

    // cURL inicializálása
    $curl = curl_init($url);

    // cURL beállítások
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    // Az adatok lekérése
    $response = curl_exec($curl);

    // Ellenőrizze a választ és dolgozza fel az adatokat
    if ($response) {
        $stock_data = json_decode($response, true);

        // Kiírjuk a részvény adatokat
        if (isset($stock_data['Time Series (Daily)'])) {
            echo '<h2>' . $search_term . '</h2>';
            echo '<p>Utolsó frissítés: ' . $stock_data['Meta Data']['3. Last Refreshed'] . '</p>';
            // További adatok megjelenítése vagy kezelése...
        } else {
            echo "Nincs adat ehhez a részvénykódhoz.";
        }
    } else {
        echo "Nem sikerült kapcsolódni az Alpha Vantage API-hoz.";
    }

    // cURL lezárása
    curl_close($curl);
}
?>

<form method="get" action="">
    <input type="text" name="search" placeholder="Ticker jelet ide..." required>
    <input type="submit" value="Keresés">
</form>
