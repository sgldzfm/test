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
        if($_GET['id']){
            //带id的是修改信息
            postAlter($_GET['id']);
        } else{
            //不带id的是添加信息
            postback();
        }


    }
//获取信息
function postback(){
    if(empty($_POST['edit_name'])):
        $GLOBALS['message'] = '分类名称不能为空';
        $GLOBALS['succeed'] = false;
        return;
    endif;
    if(empty($_POST['edit_slug'])):
        $GLOBALS['message'] = '别名不能为空';
        $GLOBALS['succeed'] = false;
        return;
    endif;
    //获取用户提交的数据
    $name = $_POST['edit_name'];
    $slug = $_POST['edit_slug'];
    //向数据库插入数据，并返回影响行数
    $rows = bx_execute("insert into categories values (null,'{$slug}','{$name}')");
    $GLOBALS['succeed'] = $rows>0;//值会是boolean值，为什么自己想去
    $GLOBALS['message'] = $rows <=0 ? '添加失败': '添加成功';
}
//提交修改信息
function postAlter($id){
    if(empty($_POST['edit_name'])):
        $GLOBALS['message'] = '分类名称不能为空';
        $GLOBALS['succeed'] = false;
        return;
    endif;
    if(empty($_POST['edit_slug'])):
        $GLOBALS['message'] = '别名不能为空';
        $GLOBALS['succeed'] = false;
        return;
    endif;
    //获取用户提交的数据
    $name = $_POST['edit_name'];
    $slug = $_POST['edit_slug'];
    //向数据库插入数据，并返回影响行数
    $rows = bx_execute("update categories set slug = '$slug',name = '$name' where id = '$id'");
    $GLOBALS['succeed'] = $rows>0;//值会是boolean值，为什么自己想去
    $GLOBALS['message'] = $rows <=0 ? '修改失败': '修改成功';
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
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script><script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
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
                  <form id = "edit" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                      <h2>添加新分类目录</h2>
                      <div class="form-group">
                          <label for="name">名称</label>
                          <input id="edit_name" class="form-control" name="edit_name" type="text" placeholder="分类名称">
                      </div>
                      <div class="form-group">
                          <label for="slug">别名</label>
                          <input id="edit_slug" class="form-control" name="edit_slug" type="text" placeholder="slug">
                          <p class="help-block">https://zce.me/category/<strong>slug</strong></p>
                      </div>
                      <div class="form-group">
                          <button id="edit_alter" class="btn btn-primary" >添加</button>
                      </div>
                  </form>
              </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a id="batch" class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input id="all_check" type="checkbox"></th>
                <th>名称</th>
                <th>Slug</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
            <!--循环取出分类信息的数组里的数据-->
            <?php foreach($categories as $value){?>
              <tr id="<?php echo $value['id']?>">
                <td class="text-center"><input type="checkbox" data-id="<?php echo $value['id']?>"></td>
                <td><?php echo $value['name']?></td>
                <td><?php echo $value['slug']?></td>
                <td class="text-center">
                  <a href="javascript:;" class="btn btn-info btn-xs edit" data-edit="<?php echo $value['id']?>">编辑</a>
                  <a href="javascript:;" class="btn btn-danger btn-xs delete" data-id="<?php echo $value['id']?>">删除</a>
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
  <script>
      $(function(){
          //第一种方式实现全选和全不选的功能
          /*//是否显示批量删除按钮并且判断是否全选并让全选按钮的状态随之选择改变
          $("tbody input").click(function(){
              $("tbody input:checked").length > 0 ? $("#batch").css("display","block") : $("#batch").css("display","none")
              $("tbody input:checked").length === $("tbody input").length ? $("#all_check").prop("checked",true) : $("#all_check").prop("checked",false)
          })
          //选中全选按钮，让所有的选项都选中。并让批量删除按钮显示
          $("#all_check").click(function(){
              this.checked ? $("tbody input").prop("checked",true) : $("tbody input").prop("checked",false)
              $("tbody input:checked").length > 0 ? $("#batch").css("display","block") : $("#batch").css("display","none")
          })

          //批量删除功能
          $("#batch").click(function(){
              console.log($("tbody input:checked").attr("id"));
              NProgress.start()
              var id = $(this).attr("cid");
              var aobj = $(this);
              $.post('./api/category_delete.php', { id: id }, function (res) {
                  if(res){
                      $("#"+id).remove()
                  }
              })
              NProgress.done()
          })*/
          //第二种方式实现全选和全不选
          //获取每一个选择按钮
          var $tbodyCheckboxs = $('tbody input')
          //存放被选中按钮的id值
          var allCheckeds = []
          //注册对象有变化时触发的事件
          $tbodyCheckboxs.on('change',function (){
              //获取自定义id
              var id = $(this).data('id')
              //判断当前checkbox是否被选中，有选中则把checkbox的id放进数组中,取消选中则从数组中移除该id
              if($(this).prop('checked')){
                  //往数组了存放id值
                  allCheckeds.push(id)
              } else {
                  //移除数组中的对应id,要从数组中移除某个元素首先要知道该元素在数组中的下标。要得到某个元素在数组中的下标需要知道元素的值
                  //数组.splice(下标) //从数组中移除元素，并返回移除的元素。 数组.indexof(元素值) 返回元素在数组中的下标
                  allCheckeds.splice(allCheckeds.indexOf(id),1)
              }
              //判断数组是否为空，显示或者隐藏批量删除按钮
              allCheckeds.length ? $('#batch').fadeIn() : $('#batch').fadeOut()
          })
          //选中所有
          $('#all_check').on('change',function(){
              //设置全部选中
              $tbodyCheckboxs.prop('checked',true)
              if($(this).prop('checked')){
                  $.each($tbodyCheckboxs,function(index,value){
                      $data_id = $(value).data('id')
                      if($.inArray($data_id, allCheckeds)===-1){
                          //往数组了存放id值
                          allCheckeds.push($data_id)
                      }
                  })
              } else{
                  allCheckeds = []
                  //取消全部选中
                  $tbodyCheckboxs.prop('checked',false)
              }
          })

          $('#batch').on('click',function(){
              NProgress.start()
              //批量删除所有选中的数据
              $.post('./api/category_delete.php', { id: allCheckeds }, function (res) {
                  if(res){
                      $.each(allCheckeds,function(index,value){
                          $("#"+value).remove()
                          //隐藏批量删除按钮
                          $('#batch').fadeOut()
                      });
                  }
              })
              NProgress.done()
          })
          //删除单条数据的方法
          $(".delete").click(function(){
              NProgress.start()
              var id = $(this).attr("data-id");
              $.post('./api/category_delete.php', { id: id }, function (res) {
                  if(res){
                      $("#"+id).remove()
                  }
              })
              NProgress.done()
          })

          //点击编辑按钮时
          $(".edit").on('click',function(){
              NProgress.start()
              var id = $(this).data("edit");
              $.post('./api/category_edit.php', { id: id }, function (res) {
                  if(res){

                      $("#edit").prop('action','/admin/categories.php?id='+id)
                      $("#edit h2").html("编辑《"+res.name+"》")
                      $("#edit button").html("修改")
                      $("#edit_name").val(res.name)
                      $("#edit_slug").val(res.slug)

                  }
              },'json')
              NProgress.done()
          })
      })
  </script>
  <script>NProgress.done()</script>
</body>
</html>
