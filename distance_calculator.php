<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pickupLocation = $_POST['pickupLocation'];
    $dropLocation = $_POST['dropLocation'];

    $api_key = 'AIzaSyBdNkShnbehc2ts6BwLGCWOhI_9jGh6sX8&callback=initMap';
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins={$pickupLocation}&destinations={$dropLocation}&key={$api_key}";

    $response = file_get_contents($url);

    if ($response === false) {
        echo json_encode(['error' => 'Error fetching data from Google Maps API']);
    } else {
        $data = json_decode($response, true);

        if ($data === null) {
            echo json_encode(['error' => 'Error decoding JSON response']);
        } elseif ($data['status'] === 'OK') {
            $distanceInKm = $data['rows'][0]['elements'][0]['distance']['value'] / 1000;
            $duration = $data['rows'][0]['elements'][0]['duration']['text'];

            $ratePerKm = 100;
            $rate = $distanceInKm * $ratePerKm;

            $result = [
                'distance' => $distanceInKm,
                'duration' => $duration,
                'rate' => $rate,
            ];

            echo json_encode($result);
        } else {
            echo json_encode(['error' => 'Error in API response']);
        }
    }
} else {
    echo json_encode(['error' => 'Invalid request method']);
}
?>

