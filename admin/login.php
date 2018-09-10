<?php
    //下发session
    session_start();
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
        //登陆成功，该session中存放一个登陆成功的标记。这是方法一
        //$_SESSION['is_logged'] = true;
        //方法二，登陆成功给session存放登陆成功的对象
        $_SESSION['current_login_user'] = $user;
        //校验成功，跳转到首页
        header('Location: /admin/');
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
  <script src="/static/assets/vendors/jquery/jquery-1.12.4.js"></script>
  <script>
      //判断数值是否为空
      function isUndefined(value){
          return value==undefined || $.trim(value).length==0;
      }
      //给email输入框注册时区焦点事件
      $("#email").focusout(function(){
          //获取输入框里面的值
          var value = $("#email").val()
          //发送jquery封装的ajax请求
          $.post('/admin/api/avatar.php', { email: value }, function (res) {
              //判断返回的值是否为空，为空则使用默认的值，不为空则使用返回的值
              isUndefined(res) ? $(".avatar")[0].src= "/static/assets/img/default.png" : $(".avatar")[0].src= res
          })
      })
  </script>
</body>
</html>
