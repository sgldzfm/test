<?php

//下发session
session_start();

if(empty($_SESSION['current_login_user'])){
    //进入里面说明没有登陆,跳转回登陆页面
    header('Location: /admin/login.php');
}
var_dump($_SESSION['current_login_user']['id']);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/assets/vendors/ajaxfileupload/ajaxfileupload.js"></script>
  <script src="/static/assets/vendors/ueditor1_4_3_3-utf8/ueditor.config.js"></script>
  <script src="/static/assets/vendors/ueditor1_4_3_3-utf8/ueditor.all.js"></script>

</head>
<body>
  <script>NProgress.start()</script>
  <div class="main">
      <!--导入导航栏公共部分-->
      <?php include'inc/navbar.php';?>
    <div class="container-fluid">
      <div class="page-title">
        <h1>写文章</h1>
      </div>
      <!-- 有错误信息时展示 -->
       <div class="alert alert-danger" id="hint">
      </div>
      <form class="row">
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题">
          </div>
          <div class="form-group">
            <label for="content">文章内容</label>
            <!--<textarea id="content" class="form-control input-lg" name="content" cols="30" rows="10" placeholder="内容"></textarea>-->
              <script id="content" name="content" type="text/plain">这是初始值</script>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="slug">副标题</label>
            <input id="subhead" class="form-control" name="subhead" type="text" placeholder="subhead" >
            <p class="help-block">https://zce.me/post/<strong>slug</strong></p>
          </div>
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <img class="help-block thumbnail" style="display: none">
            <input id="feature" class="form-control" name="feature" type="file">
          </div>
          <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category">
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <input id="created" class="form-control" name="created" type="datetime-local">
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">
              <option value="drafted">草稿</option>
              <option value="published">已发布</option>
            </select>
          </div>
          <div class="form-group">
            <button id="save" class="btn btn-primary" >保存</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!--导入公共部分-->
  <?php $current_page='post-add'?>
  <?php include'inc/aside.php'?>
  <script>
      //入口函数
      $(function(){
          //隐藏提示区域
          $("#hint").hide()
          //定义一个变量，存放ajax返回的对象
          var $ajax_data
          //初始化下拉列表
          $.get("/admin/api/article_actions.php", function(data) {
              $ajax_data = data
              for (key in data){
                  //分类下拉框
                  var $catopt = $("<option></option>"); //创建了一个jq对象的节点
                  $catopt.text(data[key]['name'])
                  $catopt.val(data[key]['id'])
                  $("#category").append($catopt)
              }
          });

          //当点击保存按钮时，通过ajax提交填写的表单
          $("#save").on('click',function(){
              var $title = $("#title").val()
              var $content = $("#content").val()
              var $subhead = $("#subhead").val()
              var $category_id = $("#category option:selected").val()
              var $created = $("#created").val()
              //把日期格式2018-09-18T05:05 的T换成空格 2018-09-18 05:05
              $created = $created.replace("T", " ");
              var $status = $("#status option:selected").val()

              var $articles = {
                  "title":$title,
                  "content":$content,
                  "subhead":$subhead,
                  "category_id":$category_id,
                  "created":$created,
                  "status":$status
              }

              var jsonResStr=JSON.stringify($articles);
              ajaxFileUpload(jsonResStr)
              //阻止表单提交
              return false;
          });


      });
      //ajax请求，并且有文件上传的功能的方法
      function ajaxFileUpload(data) {
          $.ajaxFileUpload
          (
              {
                  url:"/admin/api/article_actions.php", //用于文件上传的服务器端请求地址
                  type: 'post',
                  data: { 'json': data },
                  secureuri: false, //是否需要安全协议，一般设置为false
                  fileElementId: 'feature', //文件上传域的ID
                  dataType: 'text', //返回值类型 一般设置为json
                  success: function (res, status)  //服务器成功响应处理函数
                  {
                      //把服务端返回的json格式的字符串解析成json对象
                      /*data = JSON.parse(res)
                      console.log(typeof data);*/
                      $("#hint").html(`<strong>${res}</strong>`)
                      $("#hint").show()
                  },
                  error: function (res, status, e)//服务器响应失败处理函数
                  {
                      $("#hint").html(`<strong>${res}</strong>`)
                      $("#hint").show()
                  }
              }
          )
      }

      //获取带后缀的文件名 如传入C:\fakepath\7.jpeg  返回7.jpeg
      function getFileName(o){
          var pos=o.lastIndexOf("\\");
          return o.substring(pos+1);
      }

  </script>
  <script>NProgress.done()</script>
  <script>
      UE.getEditor('content',{
          initialFrameHeight:320,
          autoHeight: false
      })
  </script>
</body>
</html>
