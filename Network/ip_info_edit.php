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
	
	if(isset($_POST["action"]) && ($_POST["action"]=="edit"))
	{
		$page = $_GET['page'];
		$time = date("Y-m-d H:i:s");
		$use = $_POST['use'];
		$title = $_POST['title'];
		$tel = $_POST['tel'];
		$owner = $_POST['owner'];
		
		$query = "update IP_Information  set `owner`='$owner',`title`='$title',`tel`='$tel',`use`='$use',`time`='$time' where sn=".$_GET["id"];                  
		mysql_query($query) or die('Insert data fail' . mysql_error());	
		header("Location: member.php?id=ip_info&page=".$page);
	}
	
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
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
      
	  <p  class="title">新增IP資料</p>
      <div class="dataDiv">		  
      <hr size="1" />
	  <p><strong>IP　位址</strong>：
               <input name="ip1" type="text" size="5"  maxlength="3" value="140" readonly >
			   <strong>.</strong>
               <input name="ip2" type="text" size="5"  maxlength="3" value="120" readonly >
               <strong>.</strong>
               <input name="ip3" type="text" size="5" maxlength="3" class="normalinput" id="ip3" value="<?php echo $ip3;?> "readonly>
              <strong>.</strong>
               <input name="ip4" type="text" size="5" maxlength="3" class="normalinput" id="ip4" value="<?php echo $ip4;?>" readonly>
            </p>
			
			<p>
				
				<strong>用　　途</strong>：
				
				<select name="use">
				<option <?php  if($ip_info_row['use']=="個人電腦")
							   {
							      echo "selected";
							   }
						?> value="個人電腦">個人電腦</option>
				<option <?php  if($ip_info_row['use']=="伺服器")
							   {
									echo "selected";
							   }
						?> value="伺服器">伺服器</option>
						
				<option <?php  if($ip_info_row['use']=="IP分享器")
							   {
									echo "selected";
							   }?> value="IP分享器">IP分享器</option>
				<option <?php  if($ip_info_row['use']=="交換器")
							    {
									echo "selected";
								}?> value="交換器">交換器</option>
				<option <?php  if($ip_info_row['use']=="無線基地台")
							    {
									echo "selected";
								}?> value="無線基地台">無線基地台</option>		
				<option <?php  if($ip_info_row['use']=="其他網路設備")
							    {
									echo "selected";
								}?> value="其他網路設備">其他網路設備</option>					
				</select>  
            
			</p>
			
			<p><strong>負 責 人</strong>：
            <input name="owner" type="text" class="normalinput" id="owner" value="<?php echo $ip_info_row['owner'];?>">
            </p>  
 
			<p>
			<strong>身　　分</strong>：
            <select name="title">
			
			<option <?php  if($ip_info_row['title']=="教師")
							    {
									echo "selected";
								}?> value="教師">教師</option>
								
			<option <?php  if($ip_info_row['title']=="職員")
							    {
									echo "selected";
								}?> value="職員">職員</option>
			<option <?php  if($ip_info_row['title']=="學生")
							    {
									echo "selected";
								}?> value="學生">學生</option>					
  			</select>  
            </p>
			
			<p>
			<strong>電　　話</strong>：
            <input name="tel" type="text" class="normalinput" id="tel" value="<?php echo $ip_info_row['tel'];?>">
            </p> 
			
			<hr size="1" />
            
			<p align="center">
            <input name="action" type="hidden" id="action" value="edit">
            <input type="submit" name="Submit2" value="送出資料">
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
