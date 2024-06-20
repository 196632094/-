<?php
// 网站标题设置
$Site_title = 'MDP Blog Pro 超简约的博客';
require_once 'Parsedown.php';
$parser = new Parsedown();
$files = scandir('文章');
$list = array();
foreach ($files as $file) {
    // 排除含有 ".." 和 "." 的文件
    if ($file != "." && $file != "..") {
        $modifiedTime = filemtime('文章/' . $file);
        $md = explode('.md', $file);
        $title = $md[0];
        $time = date('Y-m-d H:i:s', $modifiedTime);
        $list[] = array('title' => $title, 'time' => $time);
    }
}

if (isset($_GET['md'])) {
    $md = $_GET['md'];

    // 检查文件是否存在
    if (!file_exists('文章/' . $md . '.md')) {
        header('Content-type:text/json');
        die(json_encode(['code' => '-1', 'text' => '-404, File does not exist']));
    }

    $markdown = file_get_contents('文章/' . $md . '.md');
    $html = $parser->text($markdown);
    echo <<<EOF
<!DOCTYPE html>
<html class="mdui-theme-auto">

<head>
<title>$Site_title — $md</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
<link rel="stylesheet" href="https://unpkg.com/mdui@2.0.1/mdui.css">
<script src="https://unpkg.com/mdui@2.0.1/mdui.global.js"></script>
<!-- Rounded -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

<!-- 加载动画CSS -->
<style>
.loader{
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #FFF;
    z-index: 99;
    opacity: 1; /* 初始透明度为1（完全不透明） */
    transition: opacity 0.5s, z-index 0.5s; /* 添加过渡效果，时长为1秒 */
}
.loader.hidden {
    opacity: 0; /* 当添加.hidden类时，透明度变为0 */
    z-index: 0; /* 同时将z-index设置为0 */
}

.loader>img {
    height: 96px;/*根据自身需求修改图片大小*/
}
</style>

<!-- 加载动画JS -->
<script>
window.addEventListener('load', function () {
        // 延迟0.5秒后隐藏加载动画
        setTimeout(function() {
            const loader = document.querySelector('.loader');
            loader.classList.add('hidden');
        }, 500);
    });
</script>


</head>

<body>
<!-- 加载动画 -->
<div class="loader" style="flex-direction: column;"><mdui-button-icon loading icon="search" variant="tonal"></mdui-button-icon></div>




<div class="mdui-prose scroll-behavior-elevate">
$html
</div>






</body>
</html>
EOF;
} else {
    $json = json_encode($list);
    $data = json_decode($json, true);
    echo <<<EOF
<!DOCTYPE html>
<html class="mdui-theme-auto">

<head>
<title>$Site_title</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
<link rel="stylesheet" href="https://unpkg.com/mdui@2.0.1/mdui.css">
<script src="https://unpkg.com/mdui@2.0.1/mdui.global.js"></script>
<!-- Rounded -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

<!-- 加载动画CSS -->
<style>
.loader{
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background-color: #FFF;
    z-index: 99;
    opacity: 1; /* 初始透明度为1（完全不透明） */
    transition: opacity 0.5s, z-index 0.5s; /* 添加过渡效果，时长为1秒 */
}
.loader.hidden {
    opacity: 0; /* 当添加.hidden类时，透明度变为0 */
    z-index: 0; /* 同时将z-index设置为0 */
}

.loader>img {
    height: 96px;/*根据自身需求修改图片大小*/
}
</style>

<!-- 加载动画JS -->
<script>
window.addEventListener('load', function () {
        // 延迟0.5秒后隐藏加载动画
        setTimeout(function() {
            const loader = document.querySelector('.loader');
            loader.classList.add('hidden');
        }, 500);
    });
</script>


</head>

<body>
<!-- 加载动画 -->
<div class="loader" style="flex-direction: column;"><mdui-button-icon loading icon="search" variant="tonal"></mdui-button-icon></div>


<mdui-top-app-bar
scroll-behavior="elevate"
scroll-target=".mdui-container-fluid"
>
<mdui-badge variant="small"></mdui-badge>
<mdui-button-icon icon="home_work--rounded" href="https://www.miaoi.shop"></mdui-button-icon>
<mdui-top-app-bar-title>$Site_title</mdui-top-app-bar-title>
<div style="flex-grow: 1"></div>

</mdui-top-app-bar>
<div class="example-scroll-behavior-elevate" style="height: 60px;overflow: auto;">
</div>




<div class="mdui-container-fluid">
<mdui-divider></mdui-divider>


EOF;
    foreach ($data as $item) {
        $title = $item['title'];
        $time = $item['time'];
        echo <<<EOF
  <mdui-list>
  <mdui-list-item end-icon="arrow_right--rounded" href="?md=$title">$title
<span slot="description">发布时间：$time</span>
</mdui-list-item>
</mdui-list>
EOF;
    }
    echo <<<EOF
</div>
</body>
</html>
EOF;
}
?>
