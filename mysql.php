<?php
include 'functions.php';


$dbname = 'msg_board';
$conn = mysqli_connect('localhost', 'root', 123456);
if (!$conn) {
    die('数据库连接失败！');
}

mysqli_select_db($conn, $dbname);
mysqli_set_charset($conn, 'utf8');


$sql = 'SELECT * FROM msg LEFT JOIN
            msg_user 
            ON 
            msg.msg_user = msg_user.user_id 
            LEFT JOIN
            msg_pic
            ON
            msg.msg_user = msg_pic.pic_msg
            ORDER BY `id`';
$msg = @mysqli_query($conn, $sql);


while ($res = @mysqli_fetch_assoc($msg)) {
    $msg_arr[] = $res;
}
// pre($msg_arr);die;


// pre($msg_arr);
$rows = @mysqli_num_rows($msg);
// while ($res1 = @mysqli_fetch_object($msg)) {
//     $msg_arr1[] = $res1;
// }



if (!empty($_POST)) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $time = time();
    $author = 1;

    if(!empty($_FILES)){
        // pre($_FILES['pic']);die;
        
        // 图片内容
        $img_str = file_get_contents($_FILES['pic']['tmp_name'][0]);
        $img_con = mysqli_escape_string($conn, $img_str);

        // 图片后缀
        $type = preg_match('/image\/(.*)/', $_FILES['pic']['type'][0], $type_arr);
        $img_type = $type_arr[1];
        // $img_type = $_FILES['pic']['type'][0];

        // 图片名
        $img_name = preg_match('/(.*)\./sm', $_FILES['pic']['name'][0], $name_arr);
        $img_name = $name_arr[1];

        $img_sql = "INSERT INTO 
                        msg_pic (`pic_name`, `pic_con`, `pic_type`, `pic_msg`) 
                    VALUES 
                        ('$img_name', '$img_con', '$img_type', $author)";

        $img_res = mysqli_query($conn, $img_sql);
        // pre($img_sql);die;
        if($img_res){
            echo "<script>alert('图片上传成功！');</script>";
        }else{
            echo "<script>alert('图片上传失败！');</script>";
        }
    }

    
    $sql = "INSERT INTO 
                msg
                (`title`, `content`, `time`, `msg_user`)
            VALUES
                ('$title', '$content', $time, $author)";
    // echo $sql;die;
    $res = @mysqli_query($conn, $sql);
    $insert_id = mysqli_insert_id($conn);
    if ($res) {
        echo '<script>alert("留言成功！")</script>';
        header('Refresh:0.1;url=mysql.php');
    } else {
        echo '<script>alert("留言失败！")</script>';
        header('Refresh:3;url=mysql.php');
    }
}

$baseurl = $_SERVER['PHP_SELF']; //   classroom/mysql/mysql.php
// pre( $baseurl);die;
if (isset($_GET['del'])) {
    $id = $_GET['del'];
    $sql = "DELETE FROM msg WHERE id = {$id}";
    $res = mysqli_query($conn, $sql);
    if ($res) {
        // echo $baseurl;die;
        echo "<script>alert('删除成功!');window.location.href=\"{$baseurl}\"</script>";
    }
}

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    echo "<script>window.location.href=\"edit.php?edit={$id}\"</script>";
}

// 显示图片
// if(){

// }


mysqli_close($conn);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.css">
    <title>留言板</title>
    <style>
        .form-horizontal .form-group {
            margin-right: 0px;
            margin-left: 0px
        }

        .form-control {
            width: 50%;
        }
        .msg-board{
            width:600px;
            margin:0 auto;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="table-responsive msg-board">
        <table class="table table-bordered table-hover">
            <tr>
                <th>id</th>
                <th>title</th>
                <th>content</th>
                <th>photo</th>
                <th>time</th>
                <th>author</th>
                <th></th>
            </tr>
            <?php foreach ($msg_arr as $arr) { ?>
                <tr>
                    <td><?php echo $arr['id']; ?></td>
                    <td><?php echo $arr['title']; ?></td>
                    <td><?php echo $arr['content']; ?></td>
                    <td><?php echo "<img src='mysql.php?action='>"; ?></td>
                    <td><?php echo date('Y-m-d h:i:s', $arr['time']); ?></td>
                    <td><?php echo $arr['user_name']; ?></td>
                    <td>
                        <a href="?del=<?php echo $arr['id']; ?>" class="btn btn-danger" onclick="return confirm('确定删除吗？')">删除</a>
                        <a href="?edit=<?php echo $arr['id']; ?>" class="btn btn-info">编辑</a>
                    </td>
                </tr>
            <?php } ?>
            <!-- <?php //foreach ($msg_arr1 as $arr) { 
                    ?>
            <tr>
                <td><?php //echo $arr->id; 
                    ?></td>
                <td><?php //echo $arr->title; 
                    ?></td>
                <td><?php //echo $arr->content; 
                    ?></td>
                <td><?php //echo $arr->time; 
                    ?></td>
                <td><?php //echo $arr[''];
                    ?></td>
            </tr>

        <?php //} 
        ?> -->
            <tr>
                <td colspan="6" align="right"><?php echo '共查询到' . $rows . '条留言记录'; ?></td>
            </tr>
        </table>
    </div>

    <hr>

    <form class="form-horizontal" action="mysql.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="inputText3" class="col-sm-2 control-label">标题：</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="title" placeholder="请输入标题">
            </div>
        </div>
        <div class="form-group">
            <label for="inputTextarea3" class="col-sm-2 control-label">内容：</label>
            <div class="col-sm-10">
                <textarea class="form-control" name="content" rows="3"></textarea>
            </div>
        </div>
        <div class="form-group">
            <label for="inputTextarea3" class="col-sm-2 control-label">上传图片：</label>
            <div class="col-sm-10">
                <input type="file" name="pic[]">
            </div>
        </div>
        <div class="form-group">
            <label for="inputText3" class="col-sm-2 control-label">用户名：</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="author" placeholder="请输入用户名">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default " name="sub" value="提交">提交</button>
            </div>
        </div>
    </form>
    
    <script src="../bootstrap/js/jquery-3.4.1.min.js"></script>
    <script src="../bootstrap/js/bootstrap.js"></script>
</body>

</html>