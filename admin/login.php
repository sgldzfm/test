<?php
    function postback(){
        if(empty($_POST['email'])):
            $GLOBALS['message'] = '邮箱不能为空';
            return;
        endif;
        if(empty($_POST['password'])):
            $GLOBALS['message'] = '密码不能为空';
            return;
        endif;
        //载入配置文件
        require_once '../config.php';
        //获取用户提交的数据
        $email = $_POST['email'];
        $password = $_POST['password'];
        //使用md5加密，存在数据库里的密码也是md5加密过的
        $password = md5($password);
        //连接数据库
        $conn = mysqli_connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        if(!$conn){
            exit('<h1>数据库连接失败</h1>');
        }
        //查询用户提交过来的email值在数据库是否存在,当查询到一个时就停止往后查了，提高效率
        $query = mysqli_query($conn,"select * from users where email = '{$email}' limit 1");
        if(!$query){
            $GLOBALS['message'] = '登陆失败，请重试';
            return;
        }
        //从查询出来的结果中取第一条数据
        $user = mysqli_fetch_assoc($query);
        if(!$user){
            $GLOBALS['message'] = '邮箱与密码不匹配';
            return;
        }
        if($user['password'] !== $password){
            $GLOBALS['message'] = '邮箱与密码不匹配';
            return;
        }
        //校验成功，跳转到首页
        header('Location: /admin/');
        /*$contents = file_get_contents("../uploads/user.txt");
        //拆分得到的字符串
        // $lines = explode("\n",$contents);
        $lines = explode("\n",$contents);
        foreach ($lines as $item){
            if($item =="") break;
            $cols = explode("|",$item);
            $data[] = $cols;
            foreach($data as $user){
                //$user[0];
                $name = trim($user[0]);
                $password = trim($user[1]);
                if($_POST['username'] !== $name){
                    break;
                }
                if($_POST['password'] !== $password){
                    break;
                }
                $GLOBALS['succeed'] = '登陆成功';
            }
        }*/
    }

    if($_SERVER['REQUEST_METHOD']==='POST'){
        postback();
    }
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
    <link rel="stylesheet" href="/static/assets/vendors/animate/animate.css"/>
</head>
<body>
  <div class="login">
      <!--在form标签里加novalidate取消浏览器自带的校验功能-->
      <!--autocomplete="off" 关闭客户端的自动完成功能-->
    <form class="login-wrap" method="post" action="<?php echo $_SERVER['PHP_SELF']?>" novalidate="false" autocomplete="off">
      <img class="avatar" src="/static/assets/img/default.png">
      <!-- 有错误信息时展示 -->
        <?php if(isset($message)):?>
            <div class="alert alert-danger bounce animated">
                <strong><?php echo $message?></strong>
            </div>
        <?php endif;?>

      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" name="email"type="email" class="form-control" placeholder="邮箱" autofocus>
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" name="password"type="password" class="form-control" placeholder="密码">
      </div>
      <button class="btn btn-primary btn-block" >登 录</button>
    </form>
  </div>
</body>
</html>
