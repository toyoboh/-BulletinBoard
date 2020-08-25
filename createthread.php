<?php
    
    date_default_timezone_set('Asia/Tokyo');  //時間の設定を東京に

    //変数の初期化
    

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>とよチャンネル スレッド作成画面</title>
    <link rel="stylesheet" href="https://unpkg.com/ress/dist/ress.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Sawarabi+Mincho&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
      input[type="submit"] {
        border: 1px solid black;
        background-color: rgb(212, 212, 212);
        padding: 5px;
        border-radius: 5px;
        width: 5rem;
      }
    </style>
</head>

<body>
<div class="container">

    <h1><a class="header" href="index.php">とよチャンネル</a></h1>

    <div class="ma">
    <h3><a class="comeback" href="index.php">スレッド一覧に戻る</a></h3>

    <form action="index.php" method="post">
        <p><label for="thread-label">スレッド名:</label><input type="text" name="thread_name" value=""></p>
        <p><input type="submit" name="btn_submit" class="create-submit" size="30" value="作成"></p>
    </form>

    </div>
    
</div>

<footer>
    <p>2020/08/23 作成</p>
</footer>
</body>
</html>