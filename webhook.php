<?php
//名前を指定して定数を定義する
DEFINE("ACCESS_TOKEN", "Vxk3SnNoePbymOz3SsxevQUXxi/fDshskESbRND1X3Tfm3a1rcX4o0k5PDc3bBZEneKeRMG+aWLlsJA1Zs4l8SEdzFcEjcvSThaG9SR8GxqqY6y5R1kqLg0TBwmfwS9iE9Ywgrw0X99RYcH7oYf0EwdB04t89/1O/w1cDnyilFU=");
DEFINE("SECRET_TOKEN", "f49274c8d073af01c693a8be7a8692e4");

//インポート
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\Constant\HTTPHeader;

//やること
//SDKの勉強
//LINE公式のSDKを利用してユーザー名、画像保存、プッシュメッセージをしている。
//SDK1つで完結出来るようにする

//LINESDKの読み込み
require_once(__DIR__ . "/vendor/autoload.php");
require_once(__DIR__ . "/MySkdFunction.php");
require_once(__DIR__ . "/CurlMessageList.php");
require_once(__DIR__ . "/CurlFunction.php");
require_once(__DIR__ . "/dbConnect.php");

try {

  //LINEから送られてきたもののみ処理するため
  if (isset($_SERVER["HTTP_" . HTTPHeader::LINE_SIGNATURE])) {

    //POSTで送られてきた生データの取得
    $inputData = file_get_contents("php://input");

    //LINEBOTSDKの設定
    $httpClient = new CurlHTTPClient(ACCESS_TOKEN);
    $Bot = new LINEBot($httpClient, ['channelSecret' => SECRET_TOKEN]);
    $signature = $_SERVER["HTTP_" . HTTPHeader::LINE_SIGNATURE];
    $Events = $Bot->parseEventRequest($inputData, $signature);

    foreach ($Events as $event) {
      //POSTで送られたデータ
      $userId = $event->getuserid();
      $userName = GetUserName($Bot, $userId);
      $replyToken = $event->getreplytoken();
      $msgType = $event->getmessagetype();
      $messageId = $event->getmessageid();
      $eventType = $event->gettype();
      //リプライメッセージ送信フラグ
      $ReplyFLG = 1;
      //File Path
      $ImgFilePath = 'https://piyopiyo-hiyoko.com/NY005_MessagingAPI_sample/image/';

      if ($eventType === "message") {
        if ($msgType === "image") {
          $response = $Bot->getMessageContent($messageId);
          if ($response->isSucceeded()) { //ここのifを整理する
            //画像の生データ
            $RawBody = $response->getRawBody();
            //画像の保存
            $tempFile = tmpfile();
            fwrite($tempFile, $RawBody);
            $fileUrl = uploadImageThenGetUrl($RawBody);
            //メッセージの送信
            $SendMsg = msgArray_text($userName . ':' . 'image saved successfully');
          } else {
            $SendMsg = msgArray_text("保存に失敗しました");
            error_log($response->getHTTPStatus() . ' ' . $RawBody);
          }
        } else if ($msgType === "text") {
          $GetText = $event->gettext();
          if ($GetText === "リスト") {
            $SendMsg = "画像送信(保存)\n位置情報の送信\nスタンプ送信\nテキスト「」\nテキスト「プッシュ〜」\nテキスト「画像」\nテキスト「ボタンテンプレート」\nテキスト「確認テンプレート」\nテキスト「カルーセルテンプレート」\nテキスト「画像カルーセルテンプレート」\nテキスト「パラメーター」";
            $SendMsg = msgArray_text($SendMsg);
          } else if (strpos($GetText, "プッシュ") !== false) {
            //プッシュメッセージ
            PushLineMessage($Bot, $userId, 'プッシュメッセージの送信');
            $SendMsg = str_replace("プッシュ", '', $GetText);
            PushLineMessage($Bot, $userId, $SendMsg);
            $ReplyFLG = 0;
          } else if ($GetText === "画像") {
            //画像メッセージ
            //相対パスは利用不可だった
            $previewImageUrl = $ImgFilePath . 'neko240.jpg';
            $originalImageUrl = $ImgFilePath . 'neko06.jpg';
            $SendMsg = msgArray_img($previewImageUrl, $previewImageUrl);
          } else if ($GetText === "ボタンテンプレート") {
            //ボタンテンプレート
            $SendMsg = $msgArray_templateButton;
          } else if ($GetText === "確認テンプレート") {
            //確認テンプレート
            $SendMsg = $msgArray_ConfirmationTemplate;
          } else if ($GetText === "カルーセルテンプレート") {
            //カルーセルテンプレート
            $SendMsg = $msgArray_CarouselTemplate;
          } else if ($GetText === "画像カルーセルテンプレート") {
            //画像テンプレート
            $SendMsg = $msgArray_ImgCarouselTemplate;
          } else if ($GetText === "イメージマップ") {
            //MineTypeの設定が出来ないから動作しない
            $SendMsg = $msgArray_ImgMap;
          } else if ($GetText === "パラメーター") {
            $SendMsg = $inputData;
          } else {
            //テキスト オウム返し 
            $SendMsg = msgArray_text($userName . ":" . $GetText);
          }
        } else if ($msgType === "location") {
          //位置情報の送信
          $location_title = '東京スカイツリー';
          $location_address = '〒131-8634 東京都墨田区押上１丁目１−２';
          $location_latitude = 35.710063;
          $location_longitude = 139.8107;
          $SendMsg = msgArray_location($location_title, $location_address, $location_latitude, $location_longitude);
        } else if ($msgType === "sticker") {
          $SendMsg = msgArray_sticker(1, 1);
        }
        //デフォルト値は１ 送りたくない時は0に設定する
        if ($ReplyFLG = 1) {
          curlMessage($replyToken, $SendMsg, ACCESS_TOKEN);
        }
      } else if ($eventType === "follow") { //フォローイベント
        file_put_contents("./error.txt", "\n(" . date("Ymd-His") . ")" . "フォロー", FILE_APPEND); //debug用
        //友達に一斉送信(プッシュメッセージ)する案
        //フォローイベント時にユーザーIDをデータベースまたはXML(少数)に登録して全員に一回一回プッシュメッセージを送る。
        //db書き込み
        //$statement = $db->exec("INSERT INTO `USER_M`(`USER_ID`, `USER_NAME`, `FOLLOW`, `CREATE_YMD`, `UPDATE_YMD`) VALUES ('$userId','$userName',1,now(),now()) ON DUPLICATE KEY UPDATE `USER_NAME`='$userName',`FOLLOW`=1,`UPDATE_YMD`=now();");
        echo $statement;
      } else if ($eventType === "unfollow") { //アンフォローイベント
        file_put_contents("./error.txt", "\n(" . date("Ymd-His") . ")" . "アンフォロー", FILE_APPEND); //debug用
        //$statement = $db->exec("UPDATE `USER_M` SET `USER_NAME`=$userName,`FOLLOW`=1,`UPDATE_YMD`=now() WHERE `USER_ID`='$userId';");
      } else {
        //postBackイベント
        //データを取得して特定のイベントの処理も可能
      }
    }
  }
} catch (Exception $e) {
  $errorMsg = "\nエラー(" . date("Ymd-His") . ")" . $e;
  file_put_contents("./error.txt", $errorMsg, FILE_APPEND);
  //phpエラーログ
  error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
}
