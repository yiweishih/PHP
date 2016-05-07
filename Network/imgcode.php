<?php
session_start();
Header("Content-type: image/gif");
/*
* 初始化 
*/
$border = 1; //是否要邊框 1要:0不要
$how = 5; //驗證碼位數
$w = $how*16; //圖片寬度
$h = 22; //圖片高度
$fontsize = 10; //字體大小
$alpha = "abcdefghijkmnpqrstuvwxyz"; //驗證碼內容1:字母
$number = "23456789"; //驗證碼內容2:數字
$randcode = ""; ///驗證碼字符串初始化
srand((double)microtime()*1000000); //初始化隨機數種子

$im = ImageCreate($w, $h); //創建驗證圖片

/*繪製基本框架*/
$bgcolor = ImageColorAllocate($im, 255, 255, 255); //設置背景顏色
ImageFill($im, 0, 0, $bgcolor); //填充背景色
if($border)
{
    $black = ImageColorAllocate($im, 0, 0, 0); //設置邊框顏色
    ImageRectangle($im, 0, 0, $w-1, $h-1, $black);//繪製邊框
}

/*
* 逐位產生隨機字符
*/
for($i=0; $i<$how; $i++)
{   
    $alpha_or_number = mt_rand(0, 1); //字母還是數字
    $str = $alpha_or_number ? $alpha : $number;
    $which = mt_rand(0, strlen($str)-1); //取哪個字符
    $code = substr($str, $which, 1); //取字符
    $j = !$i ? 4 : $j+15; //繪字符位置
    $color3 = ImageColorAllocate($im, mt_rand(0,100), mt_rand(0,100), mt_rand(0,100)); //字符隨即顏色
    ImageChar($im, $fontsize, $j, 3, $code, $color3); //繪字符
    $randcode .= $code; //逐位加入驗證碼字符串
}


/* 添加干擾 */
for($i=0; $i<5; $i++)//背景干擾線
{   
    $color1 = ImageColorAllocate($im, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255)); //干擾線顏色
    ImageArc($im, mt_rand(-5,$w), mt_rand(-5,$h), mt_rand(20,300), mt_rand(20,200), 55, 44, $color1); //干擾線
}
/*

/* 繪背景干擾點
for($i=0; $i<$how*40; $i++)//背景干擾
{   
    $color2 = ImageColorAllocate($im, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255)); //干擾點顏色 
    ImageSetPixel($im, mt_rand(0,$w), mt_rand(0,$h), $color2); //干擾點
}
*/

//例如：$_POST['randcode'] = $_SESSION['randcode']
$_SESSION['randcode'] = $randcode;

Imagegif($im);
ImageDestroy($im);
?> 