<?php
/**
 * Created by PhpStorm.
 * User: 337962552@qq.com
 * Date: 2016/1/21
 * Time: 11:45
 */
header ("content-type:text/html;charset=utf8");

include_once( 'config.php' );
include_once( 'sky.class.php' );

$re= new SkyHandle( WB_AKEY , WB_SKEY ,$_COOKIE['token'] );

if(empty($_COOKIE['token'])){
    echo "<script>alert('access_token的值不能为空');location.href='http://www.sky.com/index.php';</script>";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>接口调用列表</title>
    <style>
        h2 {color:blue;}
        h3 {color:red;}
        ul {color:blue;}
        .file {color:blue; align-content: center;text-align: center}
    </style>
</head>
<body>


<h1>接口调用列表</h1>
<h3>注意：点击按钮调用接口功能，需要请求时间，切勿连点</h3>
<form action="" method="post" enctype="multipart/form-data" >
    <table border="2">
    <tr>
        <td>刷新:</td>
        <td><input type="submit" value="刷新"/><input type="button" value="退回主页" onclick="quit()" style="margin-left: 30px"/></td>
    </tr>
    <tr>
        <td>获取用户基本信息:</td>
        <td><input type="submit" value="getUserInfo" name="getUserInfo"/></td>
    </tr>
    <tr>
        <td>获取用户扩展信息:</td>
        <td><input type="submit" value="getUserInfoExt" name="getUserInfoExt"/></td>
    </tr>
    <tr>
        <td>获取文件夹信息:</td>
        <td><input type="submit" value="getFolderInfo" name="getFolderInfo"/></td>
    </tr>
    <tr>
        <td>获取文件列表:</td>
        <td><input type="submit" value="listFiles" name="listFiles"/></td>
    </tr>
    <tr>
        <td>获取文件的下载地址:</td>
        <td><input type="submit" value="getFileDownloadUrl" name="getFileDownloadUrl"/></td>
    </tr>
     <tr>
         <td>上传文件(文件小于4M):</td>
         <td><input type="file" name="file"/><input type="submit" value="上传"/></td>
     </tr>
     <tr>
         <td>上传文件(断点续传):</td>
         <td><input type="file" name="big_file"/><input type="submit" value="上传"/></td>
     </tr>
     <tr>
         <td>文件搜索:</td>
         <td><input type="text" name="searchFiles"/><input type="submit" value="搜索"/></td>
     </tr>
     <tr>
         <td>删除文件:</td>
         <td><input type="submit" value="deleteFile" name="deleteFile"/></td>
     </tr>
    </table>
</form>
</body>
</html>


<?php
//print_r($_REQUEST);

if(isset($_REQUEST['getUserInfo'])&&$_REQUEST['getUserInfo']=="getUserInfo"){

    //获取用户基本信息
    $xml = $re->getUserInfo($_COOKIE['token']);
    echo "<h2>用户基本信息</h2>";
    echo "<ul>";
    echo "<li>用户登录名 : ".$xml->loginName."</li>";
    echo "<li>用户存储总容量(字节) : ".$xml->capacity."</li>";
    echo "<li>剩余可用空间(字节) : ".$xml->available."</li>";
    echo "<li>单个上传文件最大值(字节) : ".$xml->maxFilesize."</li>";
    echo "<li>mail189UsedSize : ".$xml->mail189UsedSize."</li>";
    echo "<li>普通用户 : ".$xml->orderAmount."</li>";
    echo "<li>provinceCode : ".$xml->provinceCode."</li>";
    echo "</ul>";

}
elseif(isset($_REQUEST['getUserInfoExt'])&&$_REQUEST['getUserInfoExt']=="getUserInfoExt"){

    //获取用户扩展信息
    $xml=$re->getUserInfoExt($_COOKIE['token']);
    echo "<h2>用户扩展信息</h2>";
    echo "<ul>";
    echo "<li>用户登录名  Id : ".$xml->loginName."</li>";
    echo "<li>safeQustion : ".$xml->safeQustion."</li>";
    echo "</ul>";

}
elseif(isset($_REQUEST['getFolderInfo'])&&$_REQUEST['getFolderInfo']=="getFolderInfo"){

    //获取文件夹信息
    $xml=$re->getFolderInfo($_COOKIE['token']);
    echo "<h2>文件夹信息</h2>";
    echo "<ul>";
    echo "<li>文件夹 Id : ".$xml->id."</li>";
    echo "<li>父文件夹 Id : ".$xml->parentFolderId."</li>";
    echo "<li>文件夹路径 : ".$xml->path."</li>";
    echo "<li>文件夹名称 : ".$xml->name."</li>";
    echo "<li>文件夹创建日期 : ".$xml->createDate."</li>";
    echo "<li>文件夹最后操作时间 : ".$xml->lastOpTime."</li>";
    echo "<li>文件夹版本号 : ".$xml->rev."</li>";
    echo "</ul>";

}
elseif(isset($_REQUEST['listFiles'])&&$_REQUEST['listFiles']=="listFiles"){

    //获取文件列表
    $xml=$re->listFiles($_COOKIE['token']);
    echo "<h2>获取文件列表</h2>";
    echo "<ul>";
    echo "<li>用户存储最新版本号 : ".$xml->lastRev."</li>";
    echo "<li>子文件夹和子文件文件总数量 : ".$xml->fileList->count."</li>";
    echo "</ul>";
    echo "<h2>文件信息</h2>";
    echo "<table border='1' class='file'>";
    echo "<th>文件Id</th><th>文件名称</th><th>文件大小</th> <th>缩略图</th> <th>文件创建日期</th><th>文件最后操作时间</th><th>文件媒体类型</th><th>文件版本号</th>";
    foreach($xml->fileList->file as $k=>$v){
        echo "<tr class='file'>";
        echo "<td>".$v->id."</td>";
        echo "<td>".$v->name."</td>";
        if ($v->size > 1024 * 1024) {
            $size = (round($v->size * 100 / (1024 * 1024)) / 100) .  'MB';
        }else {
            $size = (round($v->size * 100 / 1024) / 100)  .'KB';
        }
        echo "<td>".$size."</td>";
//        echo "<td>".$v->md5."</td>";
        foreach($v->icon as $kk) {
            echo "<td><img src='$kk->smallUrl' width='100px'/></td>";
        }
        echo "<td>".$v->createDate."</td>";
        echo "<td>".$v->lastOpTime."</td>";
        $type=$v->mediaType;
        if($type==1){
            echo "<td>图片</td>";
        }elseif($type==2){
            echo "<td>音乐</td>";
        }elseif($type==3){
            echo "<td>视频</td>";
        }elseif($type==4){
            echo "<td>文档</td>";
        }
        echo "<td>".$v->rev."</td>";
        echo "</tr>";
    }
    echo "</table>";

}
elseif(isset($_REQUEST['getFileDownloadUrl'])&&$_REQUEST['getFileDownloadUrl']=="getFileDownloadUrl"){

    //获取文件下载地址
    $xml=$re->listFiles($_COOKIE['token']);
    echo "<h2>获取文件下载地址</h2>";
    echo "<table border='1' class='file'>";
    echo "<th>文件Id</th><th>文件名称</th><th>文件大小</th> <th>缩略图</th><th>文件媒体类型</th><th>下载</th>";
    foreach($xml->fileList->file as $k=>$v) {
        echo "<tr class='file'>";
        echo "<td>" . $v->id . "</td>";
        echo "<td>" . $v->name . "</td>";
        if ($v->size > 1024 * 1024) {
            $size = (round($v->size * 100 / (1024 * 1024)) / 100) .  'MB';
        }else {
            $size = (round($v->size * 100 / 1024) / 100)  .'KB';
        }
        echo "<td>".$size."</td>";
//        echo "<td>".$v->md5."</td>";
        foreach($v->icon as $kk) {
            echo "<td><img src='$kk->smallUrl' width='100px'/></td>";
        }
        $type=$v->mediaType;
        if($type==1){
            echo "<td>图片</td>";
        }elseif($type==2){
            echo "<td>音乐</td>";
        }elseif($type==3){
            echo "<td>视频</td>";
        }elseif($type==4){
            echo "<td>文档</td>";
        }
        echo "<td><a href='javascript:void(0)' onclick=upload_url('$v->id') class='url'>下载地址</a></td>";
        echo "</tr>";
    }
    echo "</table>";

}
elseif(isset($_FILES['file']['name'])&&!empty($_FILES['file']['name'])){

    //一次性 PUT 上传操作
    if($_FILES['file']['size']>4194304){
        echo "您的文件过大，请上传小于4M的文件";die;
    }

    $xml=$re->uploadFile($_COOKIE['token'],$_FILES['file']);
    echo "<h2>上传并创建文件信息</h2>";
    echo "<ul>";
    echo "<li>文件 Id  : ".$xml->userId."</li>";
    echo "<li>文件名称  : ".$xml->name."</li>";
    echo "<li>文件大小  : ".$xml->size."</li>";
    echo "<li>文件的 MD5 码 : ".$xml->md5."</li>";
    echo "<li>文件创建日期 : ".$xml->createDate."</li>";
    echo "<li>文件夹版本号 : ".$xml->rev."</li>";
    echo "</ul>";

}
elseif(isset($_FILES['big_file']['name'])&&!empty($_FILES['big_file']['name'])){

    //断点续传操作
    $xml=$re->createUploadFile($_COOKIE['token'],$_FILES['big_file']);
    echo "<h2>断点续传文件信息</h2>";
    echo "<ul>";
    echo "<li>userId  : ".$xml->userId."</li>";
    echo "<li>文件 Id  : ".$xml->id."</li>";
    echo "<li>文件名称  : ".$xml->name."</li>";
    echo "<li>文件大小  : ".$xml->size."</li>";
    echo "<li>文件的 MD5 码 : ".$xml->md5."</li>";
    echo "<li>文件创建日期 : ".$xml->createDate."</li>";
    echo "<li>文件夹版本号 : ".$xml->rev."</li>";
    echo "</ul>";

}
elseif(isset($_REQUEST['searchFiles'])&&!empty($_REQUEST['searchFiles'])){

    //文件搜索操作
    $sel_val=$_REQUEST['searchFiles'];
    $xml=$re->searchFiles($_COOKIE['token'],$sel_val);

    echo "<h2>搜索结果($xml->count 条)</h2>";
    if($xml->count==0){
        echo "<p style='color: red'>没有搜索到相关的内容哦！</p>";die;
    }
    echo "<table border='1' class='file'>";
    if(isset($xml->file)) {
        echo "<th>文件Id</th><th>文件名称</th><th>文件大小</th> <th>缩略图</th><th>文件媒体类型</th><th>下载</th>";
        foreach ($xml->file as $k => $v) {
            echo "<tr class='file'>";
            echo "<td>" . $v->id . "</td>";
            echo "<td>" . $v->name . "</td>";
            if ($v->size > 1024 * 1024) {
                $size = (round($v->size * 100 / (1024 * 1024)) / 100) . 'MB';
            } else {
                $size = (round($v->size * 100 / 1024) / 100) . 'KB';
            }
            echo "<td>" . $size . "</td>";
//        echo "<td>".$v->md5."</td>";
            foreach ($v->icon as $kk) {
                echo "<td><img src='$kk->smallUrl' width='100px'/></td>";
            }
            $type = $v->mediaType;
            if ($type == 1) {
                echo "<td>图片</td>";
            } elseif ($type == 2) {
                echo "<td>音乐</td>";
            } elseif ($type == 3) {
                echo "<td>视频</td>";
            } elseif ($type == 4) {
                echo "<td>文档</td>";
            }
            echo "<td><a href='javascript:void(0)' onclick=upload_url('$v->id') class='url'>下载地址</a></td>";
            echo "</tr>";
        }
    }elseif(isset($xml->folder)) {
        echo "<th>文件夹ID</th><th>父文件夹Id</th><th>文件夹名称</th> <th>文件夹创建日期</th><th>文件夹最后操作时间</th>";
        foreach ($xml->folder as $k => $v) {
            echo "<tr class='file'>";
            echo "<td>" . $v->id . "</td>";
            echo "<td>" . $v->parentId . "</td>";
            echo "<td>" . $v->name . "</td>";
            echo "<td>" . $v->createDate . "</td>";
            echo "<td>" . $v->lastOpTime . "</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
}
elseif(isset($_REQUEST['deleteFile'])&&$_REQUEST['deleteFile']=="deleteFile"){

    //删除文件操作
    $xml=$re->listFiles($_COOKIE['token']);
    echo "<h2>删除文件</h2>";
    echo "<table border='1' class='file'>";
    echo "<th>文件Id</th><th>文件名称</th><th>文件大小</th> <th>缩略图</th><th>文件媒体类型</th><th>删除操作</th>";
    foreach($xml->fileList->file as $k=>$v){
        echo "<tr class='file'>";
        echo "<td>".$v->id."</td>";
        echo "<td>".$v->name."</td>";
        if ($v->size > 1024 * 1024) {
            $size = (round($v->size * 100 / (1024 * 1024)) / 100) .  'MB';
        }else {
            $size = (round($v->size * 100 / 1024) / 100)  .'KB';
        }
        echo "<td>".$size."</td>";
//        echo "<td>".$v->md5."</td>";
        foreach($v->icon as $kk) {
            echo "<td><img src='$kk->smallUrl' width='100px'/></td>";
        }
        $type=$v->mediaType;
        if($type==1){
            echo "<td>图片</td>";
        }elseif($type==2){
            echo "<td>音乐</td>";
        }elseif($type==3){
            echo "<td>视频</td>";
        }elseif($type==4){
            echo "<td>文档</td>";
        }
        echo "<td><a href='javascript:void(0)' onclick=delete_file('$v->id') class='url'>删除文件</a></td>";
        echo "</tr>";
    }
    echo "</table>";

}

?>


<script src="jq.js"></script>
<script>

    //退出，退到index页面
    function quit(){
        location.href='index.php';
    }


    //获取下载地址
    function upload_url(id){
        $.ajax({
            url:'verify.php?act=upload_url',
            data:'fileId='+id,
            type:'post',
            success:function(msg){
                location.href=msg;
                //测试中视频格式文件无法弹窗下载，可采用直接弹出下载地址
                //alert(msg);
            }
        })
    }

    //删除文件
    function delete_file(id){
        $.ajax({
            url:'verify.php?act=delete_file',
            data:'fileId='+id,
            type:'post',
            success:function(msg){
                if(msg==""){
                    window.location.reload();
                }else{
                    alert(msg);
                }
            }
        })
    }
</script>
