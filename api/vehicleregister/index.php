<?php include_once '../../assets/conn.php';

header('Content-Type: application/json');

if (isset($_GET['plateNumber']) && !empty($_GET['plateNumber'])) {
    # $plate = 'DN 12345' or 'DN12345'
    $plate = $_GET['plateNumber'];
    $plate = strtoupper($plate);
    $plate = strpos($plate, ' ') ? str_replace(' ', '', $plate) : $plate;
} else {
    echo json_encode(['error' => 'Mangler kjennemerke.']);
    return;
}

$checkPlate = $conn->query("SELECT * FROM `vehicleregister` WHERE `plateNumber` = '$plate'");

if ($checkPlate->num_rows > 0) {
    $plateInfo = $checkPlate->fetch_assoc();
    $checkPlate->close();
    $registered = $plateInfo['registered'];
    if ($registered === 1) {
        $vehicleInfo = [
            'status' => 'Registrert'
        ];
        echo json_encode($vehicleInfo, JSON_PRETTY_PRINT);
        return;
    } else {
        $vehicle = oppslag($plate);
        
        $vehicleData = $vehicle['kjoretoydataListe'][0];
        $tekniskeData = $vehicleData['godkjenning']['tekniskGodkjenning']['tekniskeData'];

        $vehicleInfo = [
            'status' => $vehicleData['registrering']['registreringsstatus']['kodeBeskrivelse'],
            'registreringsnummer' => $vehicleData['kjoretoyId']['kjennemerke'],
            'understellsnummer' => $vehicleData['kjoretoyId']['understellsnummer'],
            'merkeOgModell' => $tekniskeData['generelt']['merke'][0]['merke'] . ' ' . $tekniskeData['generelt']['handelsbetegnelse'][0],
            'kjoretoygruppe' => $vehicleData['godkjenning']['tekniskGodkjenning']['kjoretoyklassifisering']['beskrivelse'],
            'antallSeter' => $tekniskeData['persontall']['sitteplasserTotalt'],
            'farge' => $tekniskeData['karosseriOgLasteplan']['rFarge'][0]['kodeNavn'],
            'drivstoff' => $tekniskeData['miljodata']['miljoOgdrivstoffGruppe'][0]['drivstoffKodeMiljodata']['kodeNavn'],
            'euroKlasse' => $tekniskeData['miljodata']['euroKlasse']['kodeNavn'],
            'nesteEUKontroll' => $vehicleData['periodiskKjoretoyKontroll']['kontrollfrist']
        ];

        if ($vehicleInfo['status'] === 'Registrert') {
            $conn->query("UPDATE `vehicleregister` SET `registered` = 1 WHERE `plateNumber` = '$plate'");

            echo json_encode($vehicleInfo, JSON_PRETTY_PRINT);
            return;
        } else {
            echo json_encode($vehicleInfo, JSON_PRETTY_PRINT);
            return;
        }
    }
} else {
    $vehicle = oppslag($plate);
    
    $vehicleData = $vehicle['kjoretoydataListe'][0];
    $tekniskeData = $vehicleData['godkjenning']['tekniskGodkjenning']['tekniskeData'];

    $vehicleInfo = [
        'status' => $vehicleData['registrering']['registreringsstatus']['kodeBeskrivelse'],
        'registreringsnummer' => $vehicleData['kjoretoyId']['kjennemerke'],
        'understellsnummer' => $vehicleData['kjoretoyId']['understellsnummer'],
        'merkeOgModell' => $tekniskeData['generelt']['merke'][0]['merke'] . ' ' . $tekniskeData['generelt']['handelsbetegnelse'][0],
        'kjoretoygruppe' => $vehicleData['godkjenning']['tekniskGodkjenning']['kjoretoyklassifisering']['beskrivelse'],
        'antallSeter' => $tekniskeData['persontall']['sitteplasserTotalt'],
        'farge' => $tekniskeData['karosseriOgLasteplan']['rFarge'][0]['kodeNavn'],
        'drivstoff' => $tekniskeData['miljodata']['miljoOgdrivstoffGruppe'][0]['drivstoffKodeMiljodata']['kodeNavn'],
        'euroKlasse' => $tekniskeData['miljodata']['euroKlasse']['kodeNavn'],
        'nesteEUKontroll' => $vehicleData['periodiskKjoretoyKontroll']['kontrollfrist']
    ];

    if ($vehicleInfo['status'] === 'Registrert') {
        $conn->query("INSERT INTO `vehicleregister` (`plateNumber`, `registered`) VALUES ('$plate', 1)");

        echo json_encode($vehicleInfo, JSON_PRETTY_PRINT);
        return;
    } else {
        echo json_encode($vehicleInfo, JSON_PRETTY_PRINT);
        return;
    }
}

function oppslag($plate) {
    $vehicle = null;
    $url = 'https://www.vegvesen.no/ws/no/vegvesen/kjoretoy/felles/datautlevering/enkeltoppslag/kjoretoydata?kjennemerke=' . $plate;
    
    // Replace {key} with your API key
    $headers = [
        "SVV-Authorization: Apikey d2e8dde7-2f70-4622-af60-ac31d0da54a0"
    ];

    $options = [
        'http' => [
            'method' => 'GET',
            'header' => $headers
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    $http_response_header_code = substr($http_response_header[0], 9, 3);

    if ($http_response_header_code === "200") {
        $vehicle = json_decode($response, true);
    }

    return $vehicle;
}

#$vehicleData = $vehicle['kjoretoydataListe'][0];
#$tekniskeData = $vehicleData['godkjenning']['tekniskGodkjenning']['tekniskeData'];
#
#$vehicleInfo = [
#    'status' => $vehicleData['registrering']['registreringsstatus']['kodeBeskrivelse'],
#    'registreringsnummer' => $vehicleData['kjoretoyId']['kjennemerke'],
#    'understellsnummer' => $vehicleData['kjoretoyId']['understellsnummer'],
#    'merkeOgModell' => $tekniskeData['generelt']['merke'][0]['merke'] . ' ' . $tekniskeData['generelt']['handelsbetegnelse'][0],
#    'kjoretoygruppe' => $vehicleData['godkjenning']['tekniskGodkjenning']['kjoretoyklassifisering']['beskrivelse'],
#    'antallSeter' => $tekniskeData['persontall']['sitteplasserTotalt'],
#    'farge' => $tekniskeData['karosseriOgLasteplan']['rFarge'][0]['kodeNavn'],
#    'drivstoff' => $tekniskeData['miljodata']['miljoOgdrivstoffGruppe'][0]['drivstoffKodeMiljodata']['kodeNavn'],
#    'euroKlasse' => $tekniskeData['miljodata']['euroKlasse']['kodeNavn'],
#    'nesteEUKontroll' => $vehicleData['periodiskKjoretoyKontroll']['kontrollfrist']
#];

?>