<?php

//curl
function curlMessage($ReplyToken, $message, $AccessToken)
{
  $post_data = array(
    'replyToken' => $ReplyToken,
    'messages' => array($message)
  );

  // CURLでメッセージを返信する
  $ch = curl_init('https://api.line.me/v2/bot/message/reply');
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
  curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json; charser=UTF-8',
    'Authorization: Bearer ' . $AccessToken
  ));
  $result = curl_exec($ch);
  curl_close($ch);
}

//送れるメッセージタイプ(Line公式ページ)
//https://developers.line.biz/ja/docs/messaging-api/message-types/#text-messages

//○
function msgArray_text($text)
{
  return array(
    'type' => 'text',
    'text' => $text
  );
};

//○
function msgArray_img($originalContentUrl, $previewImageUrl)
{
  return array(
    'type' => 'image',
    // オリジナル画像（タップしたら表示される画像）
    'originalContentUrl' => $originalContentUrl, //'https://wws.sanshi.jp/linkurl/sys/image1.jpg',
    // サムネル画像（トーク中に表示される画像）
    'previewImageUrl' => $previewImageUrl //'https://wws.sanshi.jp/linkurl/sys/image2.jpg'
  );
};

//○
function msgArray_sticker($packageId, $stickerId)
{
  return array(
    'type' => 'sticker',
    'packageId' => $packageId,
    'stickerId' => $stickerId
  );
};

//○
function msgArray_location($title, $address, $latitude, $longitude)
{
  return array(
    'type' => 'location',
    'title' => $title,
    'address' => $address,
    'latitude' => $latitude,
    'longitude' => $longitude
  );
};

// //未確認
// $msgArray_video = array(
//   'type'               => 'video',
//   'originalContentUrl' => '動画ファイルのURL',
//   'previewImageUrl'    => 'プレビュー画像のURL'
// );
// //未確認
// $msgArray_audio = array(
//   'type'               => 'audio',
//   'originalContentUrl' => '音源のURL',
//   'duration'           =>  10 //音声ファイルの長さ（ミリ秒）
// );

//////////以下 引数が多くなるため関数化しない//////////

//○ボタンテンプレート
$msgArray_templateButton = array(
  'type'     => 'template',
  'altText'  => '代替テキスト',
  'template' => array(
    'type'    => 'buttons',
    'thumbnailImageUrl' => 'https://piyopiyo-hiyoko.com/NY005_MessagingAPI_sample/image/neko240.jpg',
    'imageSize' => 'cover',
    'title'   => 'タイトル(最大40文字)',
    'text'    => '(タイトルがないときは最大160文字、タイトルがあるときは最大60文字)',
    'actions' => array(
      array('type' => 'message', 'label' => 'ラベルです', 'text' => 'アクションメッセージ'),
      array('type' => 'message', 'label' => 'ラベル2です', 'text' => 'アクション2メッセージ')
    )
  )
);

//○確認テンプレート
$msgArray_ConfirmationTemplate = array(
  'type'     => 'template',
  'altText'  => '代替テキスト',
  'template' => array(
    'type'    => 'confirm',
    'text'    => 'テキストメッセージ(最大240文字)',
    'actions' => array(
      array('type' => 'message', 'label' => 'yes', 'text' => 'yesを押しました'),
      array('type' => 'message', 'label' => 'no',  'text' => 'noを押しました')
    )
  )
);

//○カルーセルテンプレート
$msgArray_CarouselTemplate = array(
  'type'     => 'template',
  'altText'  => '代替テキスト',
  'template' => array(
    'type'    => 'carousel',
    'columns' => array( //最大10？
      array(
        'thumbnailImageUrl' => 'https://piyopiyo-hiyoko.com/NY005_MessagingAPI_sample/image/neko01.jpg',
        'title'   => 'タイトル最大40文字',
        'text'    => 'タイトルか画像がある場合は最大60文字、ない場合は最大120文字',
        'actions' => array(array('type' => 'message', 'label' => 'ラベル', 'text' => 'メッセージ'))
      ),
      array(
        'thumbnailImageUrl' => 'https://piyopiyo-hiyoko.com/NY005_MessagingAPI_sample/image/neko02.png',
        'title'   => 'タイトル最大40文字',
        'text'    => 'タイトルか画像がある場合は最大60文字、ない場合は最大120文字',
        'actions' => array(array('type' => 'message', 'label' => 'ラベル', 'text' => 'メッセージ'))
      )
    )
  )
);

//○画像カルーセルテンプレート この書き方出ないと何故か動作しない。
$msgArray_ImgCarouselTemplate = [
  "type" => "template",
  "altText" => "this is a image carousel template",
  "template" => [
    "type" => "image_carousel",
    "columns" => [
      [
        "imageUrl" => "https://piyopiyo-hiyoko.com/NY005_MessagingAPI_sample/image/neko1024.jpeg",
        "action" => [
          "type" => "postback",
          "label" => "postback",
          "data" => "action=buy&itemid=111"
        ]
      ],
      [
        "imageUrl" => "https://piyopiyo-hiyoko.com/NY005_MessagingAPI_sample/image/neko1024.jpeg",
        "action" => [
          "type" => "message",
          "label" => "message",
          "text" => "yes"
        ]
      ],
      [
        "imageUrl" => "https://piyopiyo-hiyoko.com/NY005_MessagingAPI_sample/image/neko1024.jpeg",
        "action" => [
          "type" => "uri",
          "label" => "uri",
          "uri" => "https://piyopiyo-hiyoko.com/"
        ]
      ],
      [
        "imageUrl" => "https://piyopiyo-hiyoko.com/NY005_MessagingAPI_sample/image/neko1024.jpeg",
        "action" => [
          'type'  => 'datetimepicker',
          'label' => '日時選択',
          'data'  => 'ポストバックイベントのpostback.dataプロパティで返される文字列',
          'mode'  => 'datetime'
        ]
      ]
    ]
  ]
];

//MINEの設定がうまくいかない
$msgArray_ImgMap = array(
  array(
    'type' => 'imagemap',
    'baseUrl' => 'https://piyopiyo-hiyoko.com/NY005_MessagingAPI_sample/image/1024',
    'altText' => 'this is an imagemap',
    'baseSize' => array(
      'height' => 1040,
      'width' => 1040
    ),
    'actions' => array(
      array(
        'type' => 'uri',
        'linkUri' => 'https://www.yahoo.co.jp/',
        'area' => array(
          'x' => 0,
          'y' => 0,
          'width' => 520,
          'height' => 1040
        )
      ),
      array(
        'type' => 'uri',
        'linkUri' => 'https://www.google.co.jp/',
        'area' => array(
          'x' => 520,
          'y' => 0,
          'width' => 520,
          'height' => 1040
        )
      )
    )
  )
);


//アクションリスト
//メッセージアクション
$actions = array(
  "type" => "message",
  "label" => "message",
  "text" => "yes"
);
//URIアクション
$actions = array(
  'type'  => 'uri',
  'uri'   => 'https://piyopiyo-hiyoko.com/',
  'label' => 'ラベル文字列'
);
//日時選択アクション
$actions = array(
  'type'  => 'datetimepicker',
  'label' => 'ラベル文字列',
  'data'  => 'ポストバックイベントのpostback.dataプロパティで返される文字列',
  'mode'  => 'datetime'
);
//ポストバックアクション
$actions = array(
  'type'  => 'postback',
  'label' => 'ラベル文字列',
  'data'  => 'ポストバックイベントのpostback.dataプロパティで返される文字列',
  'text'  => 'アクションの実行時に送信されるテキスト'
);
//カメラアクション クイックリプライにのみ使える
$actions = array(
  'type'  => 'camera',
  'label' => 'ラベル文字列'
);
//カメラロールアクション クイックリプライにのみ使える
$actions = array(
  'type'  => 'cameraRoll',
  'label' => 'ラベル文字列'
);
//位置情報アクション クイックリプライにのみ使える
$actions = array(
  'type'  => 'location',
  'label' => 'ラベル文字列'
);
