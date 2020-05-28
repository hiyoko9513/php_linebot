<?php
try {
    $db = new PDO('mysql:dbname=    ;host=localhost;charset=utf8', '    ', '    ');
} catch (PDOException $e) {
    echo 'DB接続エラー： ' . $e->getMessage();
    $errorMsg = "\nエラー(" . date("Ymd-His") . ")" . $e;
    file_put_contents("./error.txt", $errorMsg, FILE_APPEND);
    //phpエラーログ
    error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
}
