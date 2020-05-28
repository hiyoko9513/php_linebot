<p>
  <?php
  //バージョン番号
  print "NY005_MessagingAPI_sample Ver3 2020/5/7";
  //ver3 2020/5/7 MineTypeの追加
  //ver2 2020/5/4 サンプルの追加
  //ver1 2020/4/23
  ?>
</p>
<p>
  <?php
  //ポート番号の表示
  $PortNumber = $_SERVER['SERVER_PORT'];
  print "server running at " . $PortNumber;
  ?>
</p>
<p>
  <?php
  $MineType = mime_content_type("./image/1024.jpeg");
  print "mineType " . $MineType;
  ?>
</p>