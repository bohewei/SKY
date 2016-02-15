<?php
/**
 * Created by PhpStorm.
 * User: 337962552@qq.com
 * Date: 2016/1/17
 * Time: 15:36
 */
include_once('config.php');
include_once('sky.class.php');
$re=new Sky(WB_AKEY , WB_SKEY);
$code_url=$re->getAuthorizeURL(WB_CALLBACK_URL."?");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>天翼云PHP SDK</title>
</head>
<body>
<!-- 授权按钮 -->
<p><center><a href="<?=$code_url?>"><img src="login.png" title="点击进入授权页面" alt="点击进入授权页面" border="0" /></a></center></p>
</body>
</html>