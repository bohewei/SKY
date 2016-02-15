<?php
/**
 * Created by PhpStorm.
 * User: 337962552@qq.com
 * Date: 2016/1/22
 * Time: 11:21
 */
header ("content-type:text/html;charset=utf8");

include_once( 'config.php' );
include_once( 'sky.class.php' );

$re= new SkyHandle( WB_AKEY , WB_SKEY ,$_COOKIE['token'] );

//获取文件ID
$id = $_REQUEST['fileId'];

if($_REQUEST['act']=="upload_url") {
//获取文件下载地址
    if (!empty($id) && isset($id)) {
        echo  $re->getFileDownloadUrl($_COOKIE['token'], $id);
    }
}elseif($_REQUEST['act']=="delete_file") {
//删除文件
    if (!empty($id) && isset($id)) {
        echo $re->deleteFile($_COOKIE['token'], $id);
    }
}