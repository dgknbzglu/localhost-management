<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['klasorAdi'])) {
    $klasorAdi = $_POST['klasorAdi'];
    $klasorYolu = "C:\\Wamp64\\www\\$klasorAdi";

    if (!file_exists($klasorYolu)) {
        mkdir($klasorYolu, 0777, true);
        $indexIcerik = '<?php echo "Merhaba!"; ?>';
        file_put_contents("$klasorYolu\\index.php", $indexIcerik);

        echo json_encode(array('success' => true));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Klasör zaten var.'));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'Geçersiz istek.'));
}
?>
