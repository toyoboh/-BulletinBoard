<?php 

    //データベース接続用
    define( 'DB_HOST', 'localhost');
    define( 'DB_USER', 'unknown');
    define( 'DB_PASS', '6Ovm86RtzkDu71P5');
    define( 'DB_NAME', 'channel');

    //ログインなどの定数
    define( 'JANITOR', 'toyoChannel');
    define( 'PASSWORD', 'asdlfkjaoriulfa');

    date_default_timezone_set('Asia/Tokyo');  //時間の設定を東京に

    //変数の初期化
    $thread_name = null;
    $now_date    = null;
    $success_message = null;
    $error_message = array();
    $thread_name_array = array();
    $janitor = null;
    $password = null;
    $mysqli = null;
    $sql = null;
    $sql2 = null;
    $res = null;
    $res2 = null;
    $delete_message = null;
    $get_id = null;
    $post_id = null;
    $miss_message = null;
    

    //$_POSTでidが送られた場合の処理 読み込む
    if(!empty($_POST['id'])) {

        //サニタイズ化
        $janitor = htmlspecialchars($_POST['janitor'], ENT_QUOTES);
        $janitor = preg_replace('/\\r\\n|\\n|\\r/','',$janitor);

        $password = htmlspecialchars($_POST['password'], ENT_QUOTES);
        $password = preg_replace('/\\r\\n|\\n|\\r/','',$password);

        if($password === PASSWORD && $janitor === JANITOR) {  //合ってた場合

            $post_id = $_POST['id'];

            $mysqli = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME);

            
            if($mysqli->connect_errno) {  
              $error_message[] = 'postが読み込みに失敗しました。エラー番号' . $mysqli->connect_error . ':' . $mysqli->connect_error;
            } else {
              
              $mysqli->set_charset('utf8');  //文字コード
              
              $sql2 = "SELECT * FROM test WHERE id = '$post_id' ";

              $sql = "DELETE FROM test WHERE id = '$post_id' ";
              
              $res2 = $mysqli->query($sql2);
              if($res2) {
                  $thread_name_array = $res2->fetch_all(MYSQLI_ASSOC);
              }

              $res = $mysqli->query($sql);
              if($res) {
                  $delete_message = '削除されました。';
               }

           }

           $mysqli->close();

        } else {  //パスワードか名前が間違ってる場合、再度表示
        
            $post_id = $_POST['id'];

            $mysqli = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME);

            if($mysqli->connect_errno) {  
               $error_message[] = 'postが読み込みに失敗しました。エラー番号' . $mysqli->connect_error . ':' . $mysqli->connect_error;
            } else {

              $mysqli->set_charset('utf8');  //文字コード

              $sql = "SELECT * FROM test WHERE id = '$post_id' ";

              $res = $mysqli->query($sql);

              if($res) {
                  $miss_message = '削除されませんでした。管理人名かパスワードの間違いがあります。';
                  $thread_name_array = $res->fetch_all(MYSQLI_ASSOC);
              } else {
                 $error_message[] = '取得に失敗しました。';
              }

            }

            $mysqli->close();

        }

    }

    //$_GETでidが送られた場合の処理 読み込む
    if(!empty($_GET['id']) && empty($_POST['id'])) {

      $get_id = $_GET['id'];

      $mysqli = new mysqli( DB_HOST, DB_USER, DB_PASS, DB_NAME);

      if($mysqli->connect_errno) {  
          $error_message[] = 'getが読み込みに失敗しました。エラー番号' . $mysqli->connect_error . ':' . $mysqli->connect_error;
      } else {

        $mysqli->set_charset('utf8');  //文字コード

        $sql = "SELECT * FROM test WHERE id = '$get_id' ";

        $res = $mysqli->query($sql);

        if($res) {
            $thread_name_array = $res->fetch_all(MYSQLI_ASSOC);
        } else {
            $error_message[] = '取得に失敗しました。';
        }

      }
    }    



?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>とよチャンネル 削除ページ</title>
  <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Sawarabi+Mincho&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>

<body>
<div class="container">

    <h1><a class="header" href="index.php">とよチャンネル</a></h1>

    <h3 class="sub-title">管理人用削除ページ</h3>

    <div class="ma">
    <?php if(!empty($delete_message)): ?>
        <h2>スレッド名：<?php echo $thread_name_array[0]['thread_name']; ?></h2>
        <p class="delete-message"><?php echo $delete_message ?></p>
        <p>
        <a class="comeback" href="thedetails.php?thedetails=<?php echo $thread_name_array[0]['thread_name']; ?>">
        <?php echo $thread_name_array[0]['thread_name']; ?>、スレッドに戻る
        </a></p>
    <?php else: ?>

    <h2>スレッド名：<?php echo $thread_name_array[0]['thread_name']; ?></h2>

    <p>
    <a class="comeback" href="thedetails.php?thedetails=<?php echo $thread_name_array[0]['thread_name']; ?>">
    <?php echo $thread_name_array[0]['thread_name']; ?>、スレッドに戻る
    </a></p>

    <?php if(!empty($success_message)): ?>    <!-- 成功したときの文字表示 -->
        <p><?php echo $success_message; ?></p>
    <?php endif ?>

    <?php if(!empty($error_message)): ?>      <!-- 失敗したときの文字表示 -->
        <?php foreach($error_message as $value): ?>
            <p><?php echo $value; ?></p>
        <?php endforeach ?>
    <?php endif ?>

    </div>

    <?php if(!empty($thread_name_array)): ?>
    <?php foreach($thread_name_array as $value): ?>
        <div class="thread-check">
            <p>投稿者名：<input type="text" readonly size="30" value="<?php echo $value['user_name'] ?>"></p>
            <p>投稿内容：<textarea readonly cols="50" rows="5"><?php echo $value['message'] ?></textarea></p>
        </div>
    <?php endforeach ?>
    <?php endif ?>

    <div class="ma">
    <?php if(!empty($miss_message)): ?>
        <p class="miss-message"><?php echo $miss_message ?></p>
    <?php endif ?>
    </div>

    <!-- postで送られるもの janitor  password  btn_submit  id -->
    <form action="" method="post">
      <p class="delete-message">管理人として削除する場合は「管理人名」と「パスワード」を入力してください。</p>
      <div class="janitor-pass">
          <div class="jp-flex">
              <p>管理人名　：<input type="text" name="janitor" size="30" value=""></p>
              <p>パスワード：<input type="password" name="password" size="30" value=""></p>
          </div>
          <input type="submit" name="btn_submit" value="削除する">
      </div>

      <!-- 見えないけど送るもの -->
      <input type="hidden" name="id" value="<?php echo $value['id'] ?>">
    </form>

    <?php endif ?>

    
</div>

<footer>
    <p>2020/08/23 作成</p>
</footer>

</body>
</html>