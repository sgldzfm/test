<?php
//下发session
session_start();
//使用数据库之前导入需要的常量
//载入配置文件

require_once $_SERVER['DOCUMENT_ROOT'].'/functions.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/config.php';

//判断是否为ajax的get类型的访问
if(isAjax()) {
    header('Content-Type: application/json');
    //获取文章分类信息
    $categories = bx_fetch_all('select * from categories');
    $json = json_encode($categories);
    echo $json;
} else {
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        postback();
    }
}

//判断是否为ajax的方法,是ajax返回1不是返回空
function isAjax() {
return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
    ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
}

//异步刷新的表单提交处理函数
function postback(){
    $data =  $_POST['json'];
    if(!empty($data)){
        $json = json_decode($data,true);
        $title = $json['title'];
        $content = $json['content'];
        $subhead = $json['subhead'];
        $created = $json['created'];
        $status = $json['status'];
        $user_id = $_SESSION['current_login_user']['id'];
        $category_id = $json['category_id'];
        if(empty($title)):
            echo '请输入文章标题';
            return;
        endif;
        if(empty($content)):
            echo '文章内容不能为空';
            return;
        endif;
        if(empty($subhead)):
            echo '请输入文章副标题';
            return;
        endif;
        if(empty($category_id)):
            echo '请文章分类';
            return;
        endif;
        if(empty($created)):
            echo '选择发布时间';
            return;
        endif;
        if(empty($status)):
            echo '请文章状态';
            return;
        endif;

        if($_FILES){

            $poster = $_FILES['feature'];
            // 判断用户是否选择了文件
            if ($poster['error'] !==UPLOAD_ERR_OK):
                echo '请选择上传的图片';
                return;
            endif;
            // 校验类型
            $types = array('image/jpeg', 'image/png');
            if (!in_array($poster['type'], $types)):
                echo '这是不支持的图片格式';
                return;
            endif;
            // 校验文件的大小
            if ($poster['size'] > 10 * 1024 * 1024):
                echo '图片文件过大';
                return;
            endif;

            if ($poster['size'] < 1 * 1024 * 10):
                echo '图片文件过小';
                return;
            endif;
            //图片移动到指定位置，成功则返回true
            if(!file_upload($poster)){
                echo '上传图片失败';
            }
            //图片上传的位置
            // '/static/uploads/'.date('Y').$poster['name']; ==>会变成下面的
            ///static/uploads/2018/avatar_2.jpg
            $img_path = "/static/uploads/" .date('Y').'/'. $poster["name"]; //这是存入数据库的图片地址
            //向数据库插入数据
            $rows = bx_execute("insert into posts values (null,'{$subhead}','{$title}','{$img_path}','{$created}','{$content}',0,0,'{$status}','{$user_id}','{$category_id}')");
            echo  $rows <=0 ? '添加失败': '添加成功';
        }

        //当代码运行到这里，说明上传的数据都可以获取到说明通过验证
    } else {
        echo '上传失败';
    }
}






//图片上传处理
function file_upload($felidata){
    $path = "../../static/uploads/" .date('Y').'/';
    creat($path);
    //is_uploaded_file()函数判断指定的文件是否是通过 HTTP POST 上传的 如果是则返回true
    if(is_uploaded_file($felidata['tmp_name'])) {
        //move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
        if (file_exists("../../static/uploads/" .date('Y').'/'. $felidata["name"]))
        {
            return true;
        }
        else
        {

            // 校验类型
            $types = array('image/jpeg', 'image/png');
            if (!in_array($felidata['type'], $types)):
                //不是图片格式
                // 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
                /*move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
                echo "文件存储在: " . "upload/" . $_FILES["file"]["name"];*/
                if(move_uploaded_file($felidata['tmp_name'], '../../static/uploads/'.date('Y').'/'.$felidata['name'])) {
                    echo '上传成功了';
                    return true;
                } else {
                    echo '上传失败了';
                    return false;
                }
            else://下面是图片格式
                // 如果 upload 目录不存在该文件则将文件上传到 upload 目录下
                /*move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $_FILES["file"]["name"]);
                echo "文件存储在: " . "upload/" . $_FILES["file"]["name"];*/
                if(move_uploaded_file($felidata['tmp_name'], '../../static/uploads/'.date('Y').'/'.$felidata['name'])) {
                    echo '上传成功了';
                    return true;
                } else {
                    echo '上传失败了';
                    return false;
                }
            endif;
        }
    }
}

//判断文件夹是否存在
function creat($path,$mode = 0777){
    return is_dir($path) or (creat(dirname($path))) and mkdir($path,$mode);
}
