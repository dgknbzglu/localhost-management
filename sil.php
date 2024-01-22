<?php
function klasorIceriginiSil($klasorYolu) {
    $dosyalar = glob($klasorYolu . '/*');

    foreach ($dosyalar as $dosya) {
        if (is_file($dosya)) {
            unlink($dosya);
        } elseif (is_dir($dosya)) {
            klasorIceriginiSil($dosya);
            rmdir($dosya);
        }
    }
}

if (isset($_POST['klasorAdi'])) {
    $klasorAdi = $_POST['klasorAdi'];
    $klasorYolu = "C:\\Wamp64\\www\\" . $klasorAdi;

    if (is_dir($klasorYolu)) {
        klasorIceriginiSil($klasorYolu);
        
        if (rmdir($klasorYolu)) {
            echo json_encode(['success' => true, 'message' => 'Klasör başarıyla silindi.', 'klasorAdi' => $klasorAdi]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Klasör silinirken bir hata oluştu.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Belirtilen klasör bulunamadı.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Geçersiz istek.']);
}
?>
