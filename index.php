<?php

    //データベース接続用
    define( 'DB_HOST', 'localhost');
    define( 'DB_USER', 'unknown');
    define( 'DB_PASS', '6Ovm86RtzkDu71P5');
    define( 'DB_NAME', 'channel');

    date_default_timezone_set('Asia/Tokyo');  //時間の設定を東京に

    //変数の初期化
    $thread_name = null;
    $now_date    = null;
    $success_message = null;
    $error_message = array();
    $thread_name_array = array();
    $mysqli = null;
    $sql = null;
    $res = null;

    //スレッドにnullで登録したもの
    $user_name = 'null';   //user-name
    $message   = 'null';   //message 内容


    //書き込み処理
    if(!empty($_POST['thread_name'])) {

        $mysqli = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME);  //mysqliオブジェクト

        if($mysqli->connect_errno) {
            $error_message[] = '書き込みに失敗しました。エラー番号' . $mysqli->connect_error . ':' . $mysqli->connect_error;
        } else {

            $mysqli->set_charset('utf8');  //文字コード

            $now_date = date("Y-m-d H:i:s"); //投稿時間の取得
            
            $thread_name = htmlspecialchars($_POST['thread_name'], ENT_QUOTES);//スレッド名をサニタイズ化
            $thread_name = preg_replace('/\\r\\n|\\n|\\r/','', $thread_name);  //改行コードを空に変換

            //データを登録するSQL作成
            $sql = "INSERT INTO test (thread_name, user_name, message, date) VALUES ('$thread_name', '$user_name', '$message', '$now_date')";

            $res = $mysqli->query($sql);

            if($res) {
                $success_message = 'スレッドが作成されました。';
            }

            $mysqli->close();

        }

    }

    //読み込み処理
    $mysqli = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if($mysqli->connect_errno) {
        $error_message[] = '読み込みに失敗しました。エラー番号' . $mysqli->connect_error . ':' . $mysqli->connect_error;
    } else {

        //スレッド名を取得するクエリ
        $sql = "SELECT thread_name FROM test WHERE user_name = 'null' GROUP BY thread_name ORDER BY thread_name ASC";

        $res = $mysqli->query($sql);

        if($res) {
            
            $thread_name_array = $res->fetch_all(MYSQLI_ASSOC);

        }

        $mysqli->close();
    }


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>とよチャンネル ホーム画面</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sawarabi+Mincho&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>


<body>
<div class="container">
  
    <h1><a class="header" href="index.php">とよチャンネル</a></h1>

    <div class="ma">
    <h3 class="create-thread"><a class="comeback" href="createthread.php">→スレッドを作成したい方はこちらから</a></h3>

    <h2 class="sub-title">スレッド一覧</h2>

    </div>
    
    <?php if(!empty($success_message)): ?>
        <p><?php echo $success_message; ?></p>
    <?php endif ?>

    <?php if(!empty($error_message)): ?>
        <?php foreach($error_message as $value): ?>
            <p><?php echo $value; ?></p>
        <?php endforeach ?>
    <?php endif ?>

    <section class="thread-list">
        <ul>
        <?php if(!empty($thread_name_array)): ?>
            <?php foreach($thread_name_array as $value): ?>
                <li class="thread-list-li">
                    <p><a class="hover-list" href="thedetails.php?thedetails=<?php echo $value['thread_name']; ?>">
                    ・<?php echo $value['thread_name'] ?></a></p>
                </li>
            <?php endforeach ?>
        <?php endif ?>
        </ul>
    </section>

    
</div>

<footer>
    <p>2020/08/23 作成</p>
</footer>

</body>

            
</html>
