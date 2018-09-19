<nav class="navbar">
    <button class="btn btn-default navbar-btn fa fa-bars"></button>
    <ul class="nav navbar-nav navbar-right">
        <li><a href="/admin/profile.php"><i class="fa fa-user"></i>个人中心</a></li>
        <li><a id="user_exit" href="javascript:;"><i class="fa fa-sign-out"></i>退出</a></li>
    </ul>
</nav>
<script>
    $(function(){
        $("#user_exit").on('click',function(){
            NProgress.start()
            $.post('./api/user_exit.php', { behavior: "user_exit" }, function (res) {
                //判断返回是user_exit，是则表示已经删除登录信息
                if(res === "user_exit"){
                    location.href = "/admin/login.php";
                }

            })
            NProgress.done()
        })
    })
</script>