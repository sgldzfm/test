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
        //从文件中读取内容
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
                <strong>错误！</strong> 用户名或密码错误！
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
