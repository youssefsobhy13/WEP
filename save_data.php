<?php
// save_data.php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");

$dataFile = "results.json";   // الملف الذي سيتم التخزين فيه (يمكنك تغيير مساره)

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if ($data && isset($data['name'], $data['id'], $data['score'], $data['total'])) {
        // قراءة البيانات الموجودة مسبقًا
        $existing = [];
        if (file_exists($dataFile)) {
            $content = file_get_contents($dataFile);
            if (!empty($content)) {
                $existing = json_decode($content, true) ?? [];
                if (!is_array($existing)) $existing = [];
            }
        }
        
        // إضافة السجل الجديد مع وقت التسجيل
        $newRecord = [
            'name' => $data['name'],
            'id' => $data['id'],
            'score' => $data['score'],
            'total' => $data['total'],
            'percentage' => round(($data['score'] / $data['total']) * 100, 2),
            'timestamp' => $data['timestamp'] ?? date('Y-m-d H:i:s')
        ];
        $existing[] = $newRecord;
        
        // حفظ المصفوفة كاملة
        if (file_put_contents($dataFile, json_encode($existing, JSON_PRETTY_PRINT))) {
            echo json_encode(["status" => "success", "message" => "Data appended"]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Failed to write file"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid data"]);
    }
} elseif ($_SERVER["REQUEST_METHOD"] === "GET") {
    // عرض جميع البيانات المخزنة (للاستخدام من خلال admin_download.php)
    if (file_exists($dataFile)) {
        $content = file_get_contents($dataFile);
        echo $content;
    } else {
        echo json_encode([]);
    }
} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed"]);
}
?>