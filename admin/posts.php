<?php

//下发session
session_start();

if(empty($_SESSION['current_login_user'])){
    //进入里面说明没有登陆,跳转回登陆页面
    header('Location: /admin/login.php');
}

//导入需要的配置文件
//使用数据库之前导入需要的常量
//载入配置文件
require_once '../functions.php';
require_once '../config.php';

//分页处理参数
$page = empty($_GET['page']) ? 1 : (int)$_GET['page'];
$step = 10;

//越过几条数据
$offset = ($page-1)*$step;

$sql = "SELECT
	posts.id,
	posts.title,
	users.nickname AS user_name,
	categories.name AS category_name,
	posts.created,
	posts.status
    FROM posts
    INNER JOIN categories ON posts.category_id = categories.id
    INNER JOIN users ON posts.user_id = users.id
    ORDER BY posts.created DESC
    LIMIT {$offset},{$step}	";
$posts = bx_fetch_all($sql);
var_dump($posts);

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Posts &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
      <!--导入导航栏公共部分-->
      <?php include'inc/navbar.php';?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>所有文章</h1>
        <a href="post-add.html" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        <form class="form-inline">
          <select name="" class="form-control input-sm">
            <option value="">所有分类</option>
            <option value="">未分类</option>
          </select>
          <select name="" class="form-control input-sm">
            <option value="">所有状态</option>
            <option value="">草稿</option>
            <option value="">已发布</option>
          </select>
          <button class="btn btn-default btn-sm">筛选</button>
        </form>
        <ul class="pagination pagination-sm pull-right">
          <li><a href="#">上一页</a></li>
          <li><a href="#">1</a></li>
          <li><a href="#">2</a></li>
          <li><a href="#">3</a></li>
          <li><a href="#">下一页</a></li>
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($posts as $value){?>
            <tr>
                <td class="text-center"><input type="checkbox"></td>
                <td><?php echo $value['title']?></td>
                <td><?php echo $value['user_name']?></td>
                <td><?php echo $value['category_name']?></td>
                <!--把时间年后面的一部分字符串去掉，再显示-->
                <td class="text-center"><?php echo strstr($value['created'], ' ', TRUE);?></td>
                <td class="text-center"><?php echo "published" == $value['status'] ? "已发布": "草稿" ;?></td>
                <td class="text-center">
                    <a href="javascript:;" class="btn btn-default btn-xs">编辑</a>
                    <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
                </td>
            </tr>
        <?php }?>
        </tbody>
      </table>
    </div>
  </div>

  <!--导入公共部分-->
  <?php $current_page='posts'?>
  <?php include'inc/aside.php'?>
  <script>NProgress.done()</script>
</body>
</html>
