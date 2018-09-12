<?php

    //下发session
    session_start();

    if(empty($_SESSION['current_login_user'])){
        //进入里面说明没有登陆,跳转回登陆页面
        header('Location: /admin/login.php');
    }

    //使用数据库之前导入需要的常量
    //载入配置文件
    require_once '../functions.php';
    require_once '../config.php';
    //添加文章分类的业务
    if($_SERVER['REQUEST_METHOD']==='POST'){
        postback();
    }
function postback(){
    if(empty($_POST['name'])):
        $GLOBALS['message'] = '分类名称不能为空';
        $GLOBALS['succeed'] = false;
        return;
    endif;
    if(empty($_POST['slug'])):
        $GLOBALS['message'] = '别名不能为空';
        $GLOBALS['succeed'] = false;
        return;
    endif;
    //获取用户提交的数据
    $name = $_POST['name'];
    $slug = $_POST['slug'];
    //向数据库插入数据，并返回影响行数
    $rows = bx_execute("insert into categories values (null,'{$slug}','{$name}')");
    $GLOBALS['succeed'] = $rows>0;//值会是boolean值，为什么自己想去
    $GLOBALS['message'] = $rows <=0 ? '添加失败': '添加成功';
}

    //获取文章分类信息
    $categories = bx_fetch_all('select * from categories');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
      <!--导入导航栏公共部分-->
      <?php include'inc/navbar.php';?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>分类目录</h1>
      </div>
        <?php if(isset($message)):?>
            <?php if($succeed){?>
                <div class="alert alert-success">
                    <strong>成功！</strong><?php echo $message?>
                </div>
            <?php }else{?>
                <div class="alert alert-danger">
                    <strong>失败！</strong><?php echo $message?>
                </div>
            <?php }?>
        <?php endif;?>
      <div class="row">
        <div class="col-md-4">
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <h2>添加新分类目录</h2>
            <div class="form-group">
              <label for="name">名称</label>
              <input id="name" class="form-control" name="name" type="text" placeholder="分类名称">
            </div>
            <div class="form-group">
              <label for="slug">别名</label>
              <input id="slug" class="form-control" name="slug" type="text" placeholder="slug">
              <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary" type="submit">添加</button>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input type="checkbox"></th>
                <th>名称</th>
                <th>Slug</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
            <!--循环取出分类信息的数组里的数据-->
            <?php foreach($categories as $value){?>
              <tr id="<?php echo $value['id']?>">
                <td class="text-center"><input type="checkbox"></td>
                <td><?php echo $value['name']?></td>
                <td><?php echo $value['slug']?></td>
                <td class="text-center">
                  <a href="javascript:;" class="btn btn-info btn-xs">编辑</a>
                  <a href="javascript:;" class="btn btn-danger btn-xs delete" cid="<?php echo $value['id']?>">删除</a>
                </td>
              </tr>
            <?php }?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!--导入公共部分-->
  <?php $current_page='categories'?>
  <?php include'inc/aside.php'?>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>
      $(function(){
          $(".delete").click(function(){
              NProgress.start()
              var id = $(this).attr("cid");
              var aobj = $(this);
              $.post('./api/category_delete.php', { id: id }, function (res) {
                  if(res){
                      $("#"+id).remove()
                  }
              })
              NProgress.done()
          })
      })
  </script>
  <script>NProgress.done()</script>
</body>
</html>
