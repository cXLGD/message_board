<?php
include 'functions.php';

if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $db_name = 'msg_board';
    $conn = mysqli_connect('localhost', 'root', 123456);
    if (!$conn) {
        die('连接数据库失败！');
    }
    mysqli_select_db($conn, $db_name);
    $sql = "SELECT `title`, `content`, `user_name` FROM msg INNER JOIN msg_user ON msg.msg_user = msg_user.user_id WHERE msg.id = $id";
    $res = mysqli_query($conn, $sql);

    if($res){
        $msg = mysqli_fetch_assoc($res);
    }

    if(!empty($_POST && isset($_POST['edit']))){
        $title = $_POST['title'];
        $content = $_POST['content'];

        $sql = "UPDATE msg SET `title` = '$title', `content` = '$content' WHERE  msg.id = $id";
        // pre($sql);
        $res = mysqli_query($conn, $sql);

        
        if($res){
            echo "<script>alert('修改成功！');window.location.href='mysql.php';</script>";
        }
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <title>编辑留言</title>
    <style>
        .form-horizontal .form-group {
            margin-right: 0px;
            margin-left: 0px
        }

        .form-control {
            width: 50%;
        }
    </style>
</head>

<body>
<form class="form-horizontal" action="" method="post">
        <div class="form-group">
            <label for="inputText3" class="col-sm-2 control-label">标题：</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="title" placeholder="<?php echo $msg['title']; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="inputTextarea3" class="col-sm-2 control-label">内容：</label>
            <div class="col-sm-10">
                <textarea class="form-control" name="content" rows="3"><?php echo $msg['content']; ?></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="inputTextarea3" class="col-sm-2 control-label">修改上传图片：</label>
            <div class="col-sm-10">
                <input type="file" name="pic[]">
            </div>
        </div>
        <div class="form-group">
            <label for="inputText3" class="col-sm-2 control-label">用户名：</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="author" disabled placeholder="<?php echo $msg['user_name']; ?>">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default " name="edit" value="编辑">提交</button>
            </div>
        </div>
    </form>

    <script src="../bootstrap/js/jquery-3.4.1.min.js"></script>
    <script src="../bootstrap/js/bootstrap.js"></script>
</body>

</html>