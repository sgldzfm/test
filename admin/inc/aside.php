<?php
    //因为这个文件是要include到别的文件里的，相当于把里面的代码复制到那个文件里执行
    //所以下面使用的相对路径就会出现找不到文件或木路的报错信息
    //require_once'../functions.php';//因为不清楚include这个文件的位置，或者有可能变动
    //所以先通过dirname(__FILE__)获取带盘符的路劲，然后再以这个路劲的相对路劲找到药载入的文件
    require_once dirname(__FILE__) .'/../../functions.php';
    $bx_current_user = bx_get_current_user();

?>
<div class="aside">
    <div class="profile">
        <img class="avatar" src="<?php  echo $bx_current_user['avatar'] ?>">
        <h3 class="name"><?php echo $bx_current_user['nickname']?></h3>
    </div>
    <ul class="nav">
        <li <?php echo $current_page === 'index' ? 'class="active"' : ''?>>
            <a href="/admin/index.php"><i class="fa fa-dashboard"></i>仪表盘</a>
        </li>
        <?php $menu_posts = array('posts','post-add','categories');?>
        <li <?php echo in_array($current_page,$menu_posts) ? 'class="active"' : ''?>>
            <a href="/admin/#menu-posts" <?php echo in_array($current_page,$menu_posts) ? '' : 'class="collapsed"'?> data-toggle="collapse">
                <i class="fa fa-thumb-tack"></i>文章<i class="fa fa-angle-right"></i>
            </a>
            <ul id="menu-posts" class="collapse<?php echo in_array($current_page,$menu_posts) ? ' in"' : '"'?>>
                <li <?php echo $current_page === 'posts' ? 'class="active"' : ''?>><a href="/admin/posts.php">所有文章</a></li>
                <li <?php echo $current_page === 'post-add' ? 'class="active"' : ''?>><a href="/admin/post-add.php">写文章</a></li>
                <li <?php echo $current_page === 'categories' ? 'class="active"' : ''?>><a href="/admin/categories.php">分类目录</a></li>
            </ul>
        </li>
        <li <?php echo $current_page === 'comments' ? 'class="active"' : ''?>>
            <a href="/admin/comments.php"><i class="fa fa-comments"></i>评论</a>
        </li>
        <li <?php echo $current_page === 'users' ? 'class="active"' : ''?>>
            <a href="/admin/users.php"><i class="fa fa-users"></i>用户</a>
        </li>
        <?php $menu_settings = array('nav-menus','slides','settings');?>
        <li <?php in_array($current_page,$menu_settings) ? 'class="active"' : ''?>>
            <a href="/admin/#menu-settings" <?php echo in_array($current_page,$menu_settings) ? '' : 'class="collapsed"'?> data-toggle="collapse">
                <i class="fa fa-cogs"></i>设置<i class="fa fa-angle-right"></i>
            </a>
            <ul id="menu-settings" class="collapse<?php echo in_array($current_page,$menu_settings) ? ' in"' : '"'?>>
                <li <?php echo $current_page === 'nav-menus' ? 'class="active"' : ''?>><a href="/admin/nav-menus.php">导航菜单</a></li>
                <li <?php echo $current_page === 'slides' ? 'class="active"' : ''?>><a href="/admin/slides.php">图片轮播</a></li>
                <li <?php echo $current_page === 'settings' ? 'class="active"' : ''?>><a href="/admin/settings.php">网站设置</a></li>
            </ul>
        </li>
    </ul>
</div>