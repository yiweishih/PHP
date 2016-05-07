<?php
    session_start();
    if(!isset($_SESSION["account"]) || ($_SESSION["account"]==""))
	{
        header("Location: login.php");
    }
    if(isset($_GET["logout"]) && ($_GET["logout"]=="true"))
	{
	    session_unset();
	    header("Location: login.php");
	}
    
	include("mysql_connect.php");
	
	$account = $_SESSION['account'];
	
	$ip_query = "SELECT * FROM Block_IP_List where sn =".$_GET["id"];
	$ip_query_result = mysql_query($ip_query);
    $ip_query_row = mysql_fetch_assoc($ip_query_result);
	$ip = explode(".",$ip_query_row['ip']);
	$ip3 = $ip[2];
	$ip4 = $ip[3];
	
	if(isset($_POST["action"]) && ($_POST["action"]=="edit"))
	{
		$page = $_GET['page'];
		$reason = $_POST['reason'];
		$note = $_POST['note'];

		
		$query = "update Block_IP_List set reason='$reason',note='$note' where sn=".$_GET["id"];                  
		mysql_query($query) or die('Insert data fail' . mysql_error());	
		header("Location: adm.php?page=".$page);
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
        <td class="tdbline"><img src="images/logo.jpg" width="551" height="66"></td>
    </tr>
	<tr>
    <td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="10">
    <tr valign="top">
    <td class="tdrline"><form action="" method="POST" name="formJoin" id="formJoin">
      
	  <p  class="title">編輯阻斷資料</p>
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
				<p><strong>原　　因</strong>：
               <select name="reason">
				<option <?php  if($ip_query_row['reason']=="超流")
							   {
							      echo "selected";
							   }
						?> value="超流">超流</option>
				<option <?php  if($ip_query_row['reason']=="攻擊行為")
							   {
									echo "selected";
							   }
						?>
							   value="攻擊行為">攻擊行為</option>
				<option <?php  if($ip_query_row['reason']=="流量異常")
							   {
									echo "selected";
							   }?> value="流量異常">流量異常</option>
				<option <?php  if($ip_query_row['reason']=="疑似侵權")
							   {
									echo "selected";
								}?> value="疑似侵權">疑似侵權</option>
  			</select>  
               </p>
			   <p><strong>備　　註</strong>：
               <textarea name="note" rows="8" cols="30"><?php echo $ip_query_row['note'];?></textarea>
               </p> 
			   
		  <hr size="1" />
          <p align="center">
            <input name="action" type="hidden" id="action" value="edit">
            <input type="submit" name="Submit2" value="更新資料">
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
			<font color='FF9933'>編輯阻斷資料</font>
			</p>
			<hr size='1'/>
            
			
			<p class='heading'>功能選單</p>			
			<li class='function'><a href='department_add.php'>新增帳號資料</a></li>
			<li class='function'><a href='department_account.php'>帳號資料清單</a></li>	
			<li class='function'><a href='add.php'>新增IP資料</a></li>
			<li class='function'><a href='add_list.php'>IP資料清單</a></li>
			<li class='function'><a href='block_ip_add.php'>新增阻斷資料</a></li>			
            <li class='function'><a href='adm.php'>阻斷資料清單</a></li>
			<li class='function'><a href='adm.php?process=not_process'>待處理資料</a></li>
			<li class='function'><a href='adm.php?process=complete'>待開通資料</a></li>
			<li class='function'><a href='adm.php?process=history'>歷史資料</a></li>
			<li class='function'><a href='adm.php?logout=true'>登出</a></li>
          
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
