<?php

    //下发session
    session_start();

    /*if(empty($_SESSION['current_login_user'])){
        //进入里面说明没有登陆,跳转回登陆页面
        header('Location: /admin/login.php');
    }*/
    //和上面注释的功能一样
    require_once '../functions.php';
    bx_get_current_user();

    //使用数据库之前导入需要的常量
    //载入配置文件
    require_once '../config.php';
    //查询数据库，获取文章总数
    $posts_count = bx_fetch_one('select count(1) as num from posts');
    //草稿总数:
    $draft_count = bx_fetch_one("select count(1) as draft from posts where status = 'drafted'");
    //分类总数:
    $categories_count = bx_fetch_one('select count(1) as categories from categories');
    //评论总数:
    $comments_count = bx_fetch_one('select count(1) as comments from comments');
    //待审核的评论总数:
    $audit_count = bx_fetch_one("select count(1)  as audit from comments where status = 'held'");




?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/assets/vendors/chart/chart.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
      <!--导入导航栏公共部分-->
    <?php include'inc/navbar.php';?>
    <div class="container-fluid">
      <div class="jumbotron text-center">
        <h1>One Belt, One Road</h1>
        <p>Thoughts, stories and ideas.</p>
        <p><a class="btn btn-primary btn-lg" href="/admin/post-add.php" role="button">写文章</a></p>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">站点内容统计：</h3>
            </div>
            <ul class="list-group">
              <li class="list-group-item"><strong><?php echo $posts_count['num']?></strong>篇文章（<strong><?php echo $draft_count['draft']?></strong>篇草稿）</li>
              <li class="list-group-item"><strong><?php echo $categories_count['categories']?></strong>个分类</li>
              <li class="list-group-item"><strong><?php echo $comments_count['comments']?></strong>条评论（<strong><?php echo $audit_count['audit']?></strong>条待审核）</li>
            </ul>
          </div>
        </div>
        <div class="col-md-4">
                <div class="row">
                    <div class="col-md-5">
                        <canvas id="myChart" width="400" height="400"></canvas>
                    </div>
                    <div class="col-md-5">
                        <canvas id="myChart2" width="400" height="400"></canvas>
                    </div>
                    <div class="col-md-2"></div>
                </div>
            </div>
        <div class="col-md-4"></div>
      </div>
    </div>
  </div>
<!--导入公共部分-->
  <?php $current_page='index'?>
  <?php include'inc/aside.php'?>

  <script>
      var popCanvas = document.getElementById("myChart");
      var barChart = new Chart(popCanvas, {
          type: 'doughnut',
          data: {
              labels: ["已发表", "草稿"],
              datasets: [{
                  label: '文章',
                  data: [<?php echo $posts_count['num']-$draft_count['draft']?>, <?php echo $draft_count['draft']?>],
                  backgroundColor: [
                      'rgba(255, 99, 132, 0.6)',
                      'rgba(54, 162, 235, 0.6)'
                  ]
              }]
          }
      });
      var popCanvas2 = document.getElementById("myChart2");
      var barChart2 = new Chart(popCanvas2, {
          type: 'doughnut',
          data: {
              labels: ["总评论数", "待审核"],
              datasets: [{
                  label: '文章',
                  data: [<?php echo $comments_count['comments']?>, <?php echo $audit_count['audit']?>],
                  backgroundColor: [
                      'rgba(255, 199, 132, 0.6)',
                      'rgba(154, 162, 235, 0.6)'
                  ]
              }]
          }
      });
  </script>
  <script>NProgress.done()</script>
</body>
</html>
