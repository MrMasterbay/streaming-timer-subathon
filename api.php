<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

date_default_timezone_set('Europe/Vienna'); // Österreichische Zeitzone

$dataFile = 'timer_data.json';
$adminPassword = 'CHANGEME';

// Feste Startzeit: 31.12.2025 um 20:00 Uhr
$fixedStartTime = strtotime('2025-12-31 20:00:00') * 1000; // SET START TIME HERE 
$baseDuration = (34 * 60 + 15) * 60 * 1000; // 34h 15min in Millisekunden // SET "Starthours" HERE 

// Initialdaten
$defaultData = [
    'addedTime' => 0,           // Zusätzlich hinzugefügte Zeit in ms
    'isPaused' => false,
    'pausedAt' => null,
    'totalPausedDuration' => 0  // Gesamte Pausenzeit in ms
];

// Datei erstellen falls nicht vorhanden
if (!file_exists($dataFile)) {
    file_put_contents($dataFile, json_encode($defaultData, JSON_PRETTY_PRINT));
}

// Aktuelle Daten laden
$data = json_decode(file_get_contents($dataFile), true);

// Berechne die aktuellen Werte basierend auf Serverzeit
function calculateTimerState($data, $fixedStartTime, $baseDuration) {
    $now = round(microtime(true) * 1000);
    
    $totalDuration = $baseDuration + $data['addedTime'];
    $startTime = $fixedStartTime;
    
    // Endzeit = Startzeit + Gesamtdauer + bisherige Pausenzeit
    $endTime = $startTime + $totalDuration + $data['totalPausedDuration'];
    
    // Wenn gerade pausiert, addiere die aktuelle Pausendauer
    if ($data['isPaused'] && $data['pausedAt'] !== null) {
        $currentPauseDuration = $now - $data['pausedAt'];
        $endTime += $currentPauseDuration;
    }
    
    // Berechne verbleibende Zeit
    if ($data['isPaused'] && $data['pausedAt'] !== null) {
        $pausedTimeRemaining = $endTime - $now;
    } else {
        $pausedTimeRemaining = null;
    }
    
    return [
        'startTime' => $startTime,
        'endTime' => $endTime,
        'totalDuration' => $totalDuration,
        'isPaused' => $data['isPaused'],
        'pausedTimeRemaining' => $pausedTimeRemaining,
        'serverTime' => $now,
        'addedTime' => $data['addedTime'],
        'totalPausedDuration' => $data['totalPausedDuration']
    ];
}

// GET - Daten abrufen
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $state = calculateTimerState($data, $fixedStartTime, $baseDuration);
    echo json_encode($state);
    exit;
}

// POST - Daten aktualisieren
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Passwort prüfen
    if (!isset($input['password']) || $input['password'] !== $adminPassword) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }
    
    $action = $input['action'] ?? '';
    $now = round(microtime(true) * 1000);
    
    switch ($action) {
        case 'addTime':
            $minutes = intval($input['minutes'] ?? 0);
            $addMs = $minutes * 60 * 1000;
            $data['addedTime'] += $addMs;
            break;
            
        case 'pause':
            if (!$data['isPaused']) {
                $data['isPaused'] = true;
                $data['pausedAt'] = $now;
            }
            break;
            
        case 'resume':
            if ($data['isPaused'] && $data['pausedAt'] !== null) {
                $pauseDuration = $now - $data['pausedAt'];
                $data['totalPausedDuration'] += $pauseDuration;
                $data['isPaused'] = false;
                $data['pausedAt'] = null;
            }
            break;
            
        case 'reset':
            $data = $defaultData;
            break;
            
        default:
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action']);
            exit;
    }
    
    file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));
    
    $state = calculateTimerState($data, $fixedStartTime, $baseDuration);
    echo json_encode(['success' => true, 'data' => $state]);
    exit;
}
?>
