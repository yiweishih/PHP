<?php
    session_start();
    if(!isset($_SESSION["level"]) || ($_SESSION["account"]==""))
	{
        header("Location: login.php");
    }
    if(isset($_GET["logout"]) && ($_GET["logout"]=="true"))
	{
	    session_unset();
	    header("Location: login.php");
	}    
	include("mysql_connect.php");
	$dep=$_SESSION['department'];
	
	
	$ip_info = "SELECT * FROM IP_Information where sn =".$_GET["id"];
	$ip_info_result = mysql_query($ip_info);
    $ip_info_row = mysql_fetch_assoc($ip_info_result);
	
	$ip = explode(".",$ip_info_row['ip']);
	$ip3 = $ip[2];
	$ip4 = $ip[3];
		
	if(isset($_POST["action"]) && ($_POST["action"]=="unlimited"))
	{
		$page = $_GET['page'];
		$apply_time = $_POST['start_date'];
		$expire_time = $_POST['end_date'];
		
		$a = explode("-",$apply_time);
		$b = explode("-",$expire_time);
	
		$a1=$a[0];
		$a2=$a[1];
		$a3=$a[2];
	
		$b1=$b[0];
		$b2=$b[1];
		$b3=$b[2];
		
		if(($b1<$a1)  ||   (($b1==$a1)&&($b2<$a2))  ||  (($b1==$a1)&&($b2==$a2)&&($b3<$a3)))
		{
			header("Location: ip_unlimited.php?errMsg=1&id=".$_GET["id"]."&page=".$page);
			die;
		}
		else
		{
			$query = "update IP_Information  set `apply_time`='$apply_time',`expire_time`='$expire_time',`unlimited`='W' where sn=".$_GET["id"];                  
			mysql_query($query) or die('Insert data fail' . mysql_error());	
			header("Location: member.php?id=ip_info&page=".$page);
	    }
	}
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />



<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/hot-sneaks/jquery-ui.css" rel="stylesheet">  <!--剪掉日歷就沒有背景顏色了-->  
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>       <!--剪掉日歷就沒有了-->  
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script> <!--剪掉日歷就沒有了-->  
<script language="JavaScript">

    $(document).ready(function(){ 
    $("#start_date").datepicker({firstDay: 1,minDate: new Date(),dateFormat : 'yy-mm-dd'});
    });	  
 
    $(document).ready(function(){ 
    $("#end_date").datepicker({firstDay: 1,minDate: new Date(),dateFormat : 'yy-mm-dd'}); 
    });
	
	function unlimited_sure()
	{
		if (confirm('\n 請記得填寫IP超流申請表交至計資中心網路組 賴麗妃小姐！！')) 
		return true;
		return false;
	}
    
  </script>


<title>中興大學校園網路管理系統</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="1200" border="0" align="center" cellpadding="4" cellspacing="0">
    <tr>
        <td class="tdbline"><img src="images/top.jpg" width="1200" height="100"></td>
    </tr>
	<tr>
    <td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="10">
    <tr valign="top">
    <td class="tdrline"><form action="" method="POST" name="formJoin" id="formJoin">
      
	  <p  class="title">超流申請</p>
	  <?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="1")){?>
      <div class="errDiv">日期格式不正確喔！！</div>
      <?php }?>
      <div class="dataDiv">		  
      <hr size="1" />
	  <p><strong>IP　位址</strong>：
               <input name="ip1" type="text" size="5"  maxlength="3" value="140" readonly >
			   <strong>.</strong>
               <input name="ip2" type="text" size="5"  maxlength="3" value="120" readonly >
               <strong>.</strong>
               <input name="ip3" type="text" size="5" maxlength="3" class="normalinput" id="ip3" value="<?php echo $ip3;?>" readonly>
               <strong>.</strong>
               <input name="ip4" type="text" size="5" maxlength="3" class="normalinput" id="ip4" value="<?php echo $ip4;?>" readonly>
            </p>
			
			<p>
				<strong>用　　途</strong>：
				<input name="use" type="text" class="normalinput" id="use" value="<?php echo $ip_info_row['use'];?>" readonly>  
            </p>
			
			<p><strong>負  責   人</strong> ：
            <input name="owner" type="text" class="normalinput" id="owner" value="<?php echo $ip_info_row['owner'];?>" readonly>
            </p>  
 
			<p>
			<strong>身　　分</strong>：
            <input name="title" type="text" class="normalinput" id="title" value="<?php echo $ip_info_row['title'];?>" readonly>
			</p>
			
			<p>
			<strong>電　　話</strong>：
            <input name="tel" type="text" class="normalinput" id="tel" value="<?php echo $ip_info_row['tel'];?>" readonly>
            </p> 
			
			<p>
			<strong>開始日期</strong>：
            <input name="start_date" type="text" class="normalinput" id="start_date">
            </p> 
			<p>
			<strong>結束日期</strong>：
            <input name="end_date" type="text" class="normalinput" id="end_date">
            </p> 
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="73" valign="top"><p> <strong>備　　註：</strong> </p></td>
                <td valign="top"><textarea name="note" rows="5" cols="50"></textarea></td>			

              </tr>
            </table>
            
			
			<hr size="1" />
            
			<p align="center">
            <input name="action" type="hidden" id="action" value="unlimited">
            <input type="submit" name="Submit2" value="送出資料" onClick="return unlimited_sure();">
            <input type="reset" name="Submit3" value="重設資料">
            </p>
			</form></td>
    
 
  
        <td width="200">
        <div class="boxtl"></div><div class="boxtr"></div>
<div class="regbox">
                 <p class="heading"><strong>用戶資料：</strong></p>
		  
		  
			<p>
			<strong><?php echo  $account; ?>,您好！</strong>
			<br>	
			目前所在的頁面：
			<br>
			<font color='FF9933'>新增IP資料</font>
			</p>
			<hr size='1'/>
            			
			<p class='heading'>功能選單</p>			
			<li class='function'><a href='member.php'>所有阻斷資料</a></li>
			<li class='function'><a href='member.php?id=not_process'>待處理資料</a></li>
			<li class='function'><a href='member.php?id=complete'>待開通資料</a></li>
			<li class='function'><a href='member.php?id=history'>歷史資料</a></li>
			<li class='function'><a href='member.php?id=ip_info'>IP清冊管理</a></li>
			<li class='function'><a href='member.php?logout=true'>登出</a></li>
          
          </div>
        <div class="boxbl"></div><div class="boxbr"></div></td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" background="images/album_r2_c1.jpg" class="trademark">© 版權所有 國立中興大學計算機及資訊網路中心</td>
  </tr>
</table>
</body>
</html>
