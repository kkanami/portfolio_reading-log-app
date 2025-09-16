<!doctype html>
<?php
    mb_internal_encoding("utf8");
    session_start();
?>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name=”description” content=”読書記録アプリケーション”>
    <meta property=”og:type” content=”website” />
    <meta property=”og:title” content=”Collection Of Book” />
    <meta property=”og:description” content=”読書記録アプリケーション” />
    <meta property=”og:site_name” content=”Collection Of Book” />
    <title>Collection Of Book</title>

    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
    <script type="text/javascript">
        function check1() {
            if (form.mail.value == "") {
                document.getElementById("mail_msg").innerHTML = "*メールアドレスを入力してください。";
                return false;
            } else {
                return true;
            }
        }

        function check2() {
            if (form.password.value == "") {
                document.getElementById("password_msg").innerHTML = "*パスワードを入力してください。";
                return false;
            } else {
                return true;
            }
        }

    </script>
</head>


<body>
    <div class="wrap" ontouchstart="">
        <main>
            <div class="top_image">
                <h1>Collection Of Book</h1>
                <p>読書記録アプリケーション</p>

                <div class="mypage">
                    <?php
                  if(!empty($_SESSION['user'])) {
                    echo "ログイン済です<a href=mypage.php>▶マイページに戻る</a>";
                  }
                ?>
                </div>

                <form method="post" class="login" action="index.php" name="form" id="form" onsubmit="return !!(check1()& check2())">

                    <div>
                        <label>メールアドレス</label>
                        <br>
                        <input type="email" class="text" size="50" maxlength="100" id="mail" name="mail" value="">
                    </div>
                    <p id="mail_msg"></p>
                    <br>
                    <div>
                        <label>パスワード</label>
                        <br>
                        <input type="password" pattern="^[0-9a-zA-Z]*$" class="text" size="50" maxlength="10" id="password" name="password" value="">
                    </div>
                    <p id="password_msg"></p>

                    <div>
                        <input type="submit" class="button" value="ログイン">
                    </div>
                </form>
                <a href="regist.php">新規登録</a>
            </div>

            <?php
    if((empty($_POST['mail'])) || (empty($_POST['password']))) {
        exit;
    }

    try{
        $pdo=new PDO("mysql:dbname=your;host=localhost;","your","your");
        $sql="select*from login_user where mail = :mail";
        $stmt=$pdo->prepare($sql);
        $stmt->bindvalue(":mail", $_POST['mail'], PDO::PARAM_STR);
        $stmt->execute();
        $row=$stmt->fetch();

        if(!$row){
            echo "<span>ログインに失敗しました。</span>";
            exit;
        }

         if($row['delete_flag']==0 && password_verify($_POST['password'], $row['password'])){
            session_regenerate_id(true);
            $_SESSION['user']=$row['id'];
            header("Location: mypage.php");
        }else {
            echo"<span>ログインに失敗しました。</span>";

        }

    }catch(Exception $e){
            echo '<span>エラーが発生したためログイン情報を取得できません。</span>：';
            echo $e->getMessage();
            exit();
    }
 
    ?>

        </main>
    </div>
</body>

</html>
