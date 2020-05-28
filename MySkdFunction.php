<?php

use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\Constant\HTTPHeader;
use LINE\LINEBot\Constant\MessageType;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\QuickReplyBuilder;
use LINE\LINEBot\SenderBuilder\SenderBuilder;

//○画像保存関数
function uploadImageThenGetUrl($rawBody)
{
  $im = imagecreatefromstring($rawBody);
  if ($im !== false) {
    $filename = date("Ymd-His") . '.jpg';
    imagejpeg($im, "./save_image/" . $filename);
  } else {
    error_log("fail to create image.");
  }
  return $filename;
}


//○ラインのプロフィールを取得
function GetUserName($Bot, $userId)
{
  $response = $Bot->getProfile($userId);
  if ($response->isSucceeded()) {
    $profile = $response->getJSONDecodedBody();
    return $profile['displayName'];
    // echo $profile['displayName'];
    // echo $profile['pictureUrl'];
    // echo $profile['statusMessage']; //これはもう取得できないかも？(未確認)
  }
}

//○プッシュ
function PushLineMessage($Bot, $userId, $PushMessage)
{
  $textMessageBuilder = new TextMessageBuilder($PushMessage);
  $response = $Bot->pushMessage($userId, $textMessageBuilder);
  if (!$response->isSucceeded()) {
    error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
  }
}

//////////以下 テスト中(動作しない)//////////

// //テキストを返信。引数はLINEBot、返信先、テキスト
// function SendLineMessage($Bot, $replyToken, $text)
// {
//   $response = $Bot->replyMessage($replyToken, new TextMessageBuilder($text));
//   if (!$response->isSucceeded()) {
//     error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
//   }
// }

// //画像メッセージ 引数 LINEBot、返信先、画像URL、サムネイルURL
// function replyImageMessage($Bot, $replyToken, $originalImageUrl, $previewImageUrl)
// {
//   // ImageMessageBuilderの引数は画像URL、サムネイルURL
//   $response = $Bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder($originalImageUrl, $previewImageUrl));
//   if (!$response->isSucceeded()) {
//     error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
//   }
// }

// //位置情報を返信。引数はLINEBot、返信先、タイトル、住所、緯度、経度
// function replyLocationMessage($Bot, $replyToken, $title, $address, $lat, $lon)
// {
//   $response = $Bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\LocationMessageBuilder($title, $address, $lat, $lon));
//   if (!$response->isSucceeded()) {
//     error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
//   }
// }

// //スタンプを返信。引数はLINEBot、返信先、スタンプのパッケージID、スタンプID
// function replyStickerMessage($Bot, $replyToken, $packageId, $stickerId)
// {
//   $response = $Bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\StickerMessageBuilder($packageId, $stickerId));
//   if (!$response->isSucceeded()) {
//     error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
//   }
// }

// //動画を返信。引数はLINEBot、返信先、動画URL、サムネイルURL
// function replyVideoMessage($Bot, $replyToken, $originalContentUrl, $previewImageUrl)
// {
//   //VideoMessageBuilderの引数は動画URL、サムネイルURL
//   $response = $Bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\VideoMessageBuilder($originalContentUrl, $previewImageUrl));
//   if (!$response->isSucceeded()) {
//     error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
//   }
// }

// //オーディオファイルを返信。引数はLINEBot、返信先、ファイルのURL、ファイルの再生時間
// function replyAudioMessage($Bot, $replyToken, $originalContentUrl, $audioLength)
// {
//   //AudioMessageBuilderの引数は動画URL、サムネイルURL
//   $response = $Bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\AudioMessageBuilder($originalContentUrl, $audioLength));
//   if (!$response->isSucceeded()) {
//     error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
//   }
// }

// //複数のメッセージをまとめて返信。引数はLINEBot、返信先、メッセージ(可変長引数)
// function replyMultiMessage($Bot, $replyToken, ...$msgs)
// {
//   //MultiMessageBuilderをインスタンス化
//   $builder = new \LINE\LINEBot\MessageBuilder\MultiMessageBuilder();
//   // ビルダーにメッセージをすべて追加
//   foreach ($msgs as $value) {
//     $builder->add($value);
//   }
//   $response = $Bot->replyMessage($replyToken, $builder);
//   if (!$response->isSucceeded()) {
//     error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
//   }
// }

// // Buttonsテンプレートを送信。引数はLINEBot、返信先、代替テキスト、画像URL、タイトル、本文、アクション(可変長引数)
// function replyButtonsTemplate($Bot, $replyToken, $alternativeText, $imageUrl, $title, $text, ...$actions)
// {
//   // アクションを格納する配列
//   $actionArray = array();
//   // アクションをすべて追加
//   foreach ($actions as $value) {
//     array_push($actionArray, $value);
//   }
//   //TemplateMessageBuilderの引数は代替テキスト、ButtonTemplateBuilder
//   $builder = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder(
//     $alternativeText,
//     // ButtonTemplateBuilderの引数はタイトル、本文
//     // 画像URL、アクションの配列
//     new \LINE\LINEBot\MessageBuilder\ButtonTemplateBuilder($title, $text, $imageUrl, $actionArray)
//   );
//   $response = $Bot->replyMessage($replyToken, $builder);
//   if (!$response->isSucceeded()) {
//     error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
//   }
// }

// //Confirmテンプレート返信。引数はLINEBot、返信先、代替テキスト、本文、アクション(可変長引数)
// function replyConfirmTemplate($Bot, $replyToken, $alternativeText, $text, ...$actions)
// {
//   $actionArray = array();
//   foreach ($actions as $value) {
//     array_push($actionArray, $value);
//   }
//   $builder = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder(
//     $alternativeText,
//     // Confirmテンプレートの引数はテキスト、アクションの配列
//     new \LINE\LINEBot\MessageBuilder\ConfirmTemplateBuilder($text, $actionArray)
//   );
//   $response = $Bot->replyMessage($replyToken, $builder);
//   if (!$response->isSucceeded()) {
//     error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
//   }
// }

// //Carouselテンプレートを返信。引数はLINEBot、返信先、メッセージ(可変長引数)
// //ダイアログの配列
// function replyCarouselTemplate($Bot, $replyToken, $alternativeText, $columnArray)
// {
//   $builder = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder(
//     $alternativeText,
//     // Carouselテンプレートの引数はダイアログの配列
//     new \LINE\LINEBot\MessageBuilder\CarouselTemplateBuilder($columnArray)
//   );
//   $response = $Bot->replyMessage($replyToken, $builder);
//   if (!$response->isSucceeded()) {
//     error_log('Failed! ' . $response->getHTTPStatus . ' ' . $response->getRawBody());
//   }
// }
