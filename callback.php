<?php
/**
 * Created by PhpStorm.
 * User: 337962552@qq.com
 * Date: 2016/1/17
 * Time: 16:16
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<title>天翼云盘</title>
</head>
<body>
</body>
</html>

<?php
session_start();

include_once( 'config.php' );
include_once( 'sky.class.php' );

$re=new Sky(WB_AKEY , WB_SKEY);

if (isset($_REQUEST['code'])) {
    $keys = array();
    $keys['code'] = $_REQUEST['code'];
    $keys['redirect_uri'] = WB_CALLBACK_URL;
    try {
        $token = $re->getAccessToken( 'code', $keys ) ;
    } catch (OAuthException $e) {
    }
}
if ($token) {
    setcookie('token',$token);
    ?>
    授权完成,<a href="sky.php">进入您的天翼云盘页面</a><br />
<?php
} else {

    ?>
    授权失败。
<?php
}
?>