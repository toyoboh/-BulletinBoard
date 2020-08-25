<?php

    //データベース接続用
    define( 'DB_HOST', 'localhost');
    define( 'DB_USER', 'unknown');
    define( 'DB_PASS', '6Ovm86RtzkDu71P5');
    define( 'DB_NAME', 'channel');

    date_default_timezone_set('Asia/Tokyo');  //時間の設定を東京に

    //スレッドを開いたと同時に変数に代入される、違うスレッドを開いたら更新もされる
    if(!empty($_GET['thedetails'])) {
      $thread_name = $_GET['thedetails'];
    }

    //変数の初期化
    $now_date    = null;
    $success_message = null;
    $error_message = array();
    $thedetails_array = array();
    $user_name = null;
    $message   = null;

    //スレッドにnullで登録したもの
    $user_name = 'null';   //user-name
    $message   = 'null';   //message 内容
    

    //書き込み処理
    if(!empty($_POST['user_name']) && !empty($_POST['message'])) {
    
    $mysqli = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME);  //mysqliオブジェクト

    if($mysqli->connect_errno) {
      $error_message = '書き込みに失敗しました。エラー番号' . $mysqli->connect_error . ':' . $mysqli->connect_error;
    } else {

      $mysqli->set_charset('utf8');  //文字コード

      $now_date = date("Y-m-d H:i:s"); //投稿時間の取得
      
      //サニタイズ化
      $user_name = htmlspecialchars($_POST['user_name'], ENT_QUOTES);
      $user_name = preg_replace('/\\r\\n|\\n|\\r/','',$user_name);
  
      $message = htmlspecialchars($_POST['message'], ENT_QUOTES);

      //データを登録するSQL作成
      $sql = "INSERT INTO test (thread_name, user_name, message, date) VALUES ('$thread_name', '$user_name', '$message', '$now_date')";

      $res = $mysqli->query($sql);
      
      if($res) {
          $success_message = '投稿されました。';
      } else {
          $error_message[] = '投稿が失敗しました。';
      }
      
      $mysqli->close();

    }

    
    }

    //読み込み処理
    if(!empty($thread_name)) {
        
        $mysqli = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if($mysqli->connect_errno) {
             $error_message[] = '読み込みに失敗しました。エラー番号' . $mysqli->connect_error . ':' . $mysqli->connect_error;
        } else {
  
            //スレッド名が同じデータを取得するクエリ
            $sql = "SELECT * FROM test WHERE thread_name = '$thread_name' AND NOT user_name = 'null' ORDER BY date DESC";
          
            $res = $mysqli->query($sql);
        
            if($res) {
                $thedetails_array = $res->fetch_all(MYSQLI_ASSOC);
                // echo '読み込みに成功しました。';
            }
          
            $mysqli->close();
        }
    } 


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>とよチャンネル <?php echo $thread_name; ?>のスレッド</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sawarabi+Mincho&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<div class="container">

    <h1><a class="header" href="index.php">とよチャンネル</a></h1>

    <div class="ma">
    <h2>スレッド名：<?php echo $thread_name ?></h2>

    <?php if(!empty($success_message)): ?>
        <p><?php echo $success_message; ?></p>
    <?php endif ?>

    <?php if(!empty($error_message)): ?>
        <?php foreach($error_message as $value): ?>
            <p><?php echo $value; ?></p>
        <?php endforeach ?>
    <?php endif ?>

    <h3><a class="comeback" href="index.php">スレッド一覧に戻る</a></h3>

    <form action="" method="post">
        <p><label for="user_name">名前:</label>
        <input id="user_name" type="text" name="user_name" size="30" value=""></p>

        <p><label for="message">内容:</label>
        <textarea id="message" cols="50" rows="5" name="message"></textarea></p>

        <input type="submit" name="btn_submit" value="投稿">
    </form>

    </div>

    <section>
        <ul>
        <?php if(!empty($thedetails_array)): ?>
        <?php foreach($thedetails_array as $value): ?>
            <li class="thedetails-list p-space">
              <div class="flex-two">
                <p>名前：<span class="span-name"><?php echo $value['user_name']; ?></span></p>

                <div class="flex-p">
                    <p class="p-left"><time><?php echo date('Y年m月d日 H:i:s', strtotime($value['date'])) ?></time></p>
                    <p class="p-right"><a class="delete-button" href="delete.php?id=<?php echo $value['id']; ?>">削除する</a></p>
                </div>
              </div>

                <p><?php echo nl2br($value['message']); ?></p>
            </li>
        <?php endforeach ?>
        <?php endif ?>
    </section>
    
</div>

<footer>
    <p>2020/08/23 作成</p>
</footer>

</body>
</html>
