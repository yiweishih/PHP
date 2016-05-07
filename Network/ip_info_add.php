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
	
	if(isset($_POST["action"]) && ($_POST["action"]=="add"))
	{
		if( ($_POST['ip3']=="" ) || ($_POST['ip4']==""))   //真是笨阿~~直接用""就好了
		{                                                
	        /**
			echo '<p align="center"><a href="javascript:history.go(-1);">返回上一頁</a></p></br>';      
		   	echo '<font color="#FF0000"><h1  align="center">請輸入IP位址</h1></font>';
		   	die;  		                                     
	        **/
			header("Location: ip_info_add.php?errMsg=3");
			die;
		}
	    elseif(!((0<=$_POST['ip3'] && $_POST['ip3']<=255)&&(0<=$_POST['ip4'] && $_POST['ip4']<=255)))
		{        
		
            /*
			echo '<p align="center"><a href="javascript:history.go(-1);">返回上一頁</a></p></br>';
			echo '<font color="#FF0000"><h1  align="center">您的ip位址格式不正確，請填寫正確的ip位址</h1></font>';
			die;  		                                                                                       
			*/
		    header("Location: ip_info_add.php?errMsg=3");
			die;
		
		}
		
		$a1 = $_POST['ip1'];
		$a2 = $_POST['ip2'];
		$a3 = $_POST['ip3'];
		$a4 = $_POST['ip4'];
		
		$ip = "$a1.$a2.$a3.$a4";
		$date = date("Y-m-d H:i:s");
		$use = $_POST['use'];
		$owner = $_POST['owner'];
		$title = $_POST['title'];
		$tel = $_POST['tel'];
		
		$ip_scope = mysql_query("select * from Department_Allocate_IP where department = '$dep'");
		$total = mysql_num_rows($ip_scope);
		
		while($row = mysql_fetch_assoc($ip_scope))
		{
		    $start_ip_explode = explode(".",$row['start_ip']);
			$end_ip_explode = explode(".",$row['end_ip']);
						
			if ($a3 == $start_ip_explode[2])
			{
			    if($start_ip_explode[3] <= $a4 && $a4 <= $end_ip_explode[3])
				{
                    $checkip = "select * from IP_Information where ip ='$ip'";
					$checkip_result = mysql_query($checkip);
			
					if (mysql_num_rows($checkip_result)>0)
					{
						header("Location: ip_info_add.php?errMsg=1&ip=".$ip);
						die;
					}
					else
					{
						$query = "insert into IP_Information (`ip`,`owner`,`title`,`tel`,`use`,`time`,`department`) values('$ip','$owner','$title','$tel','$use','$date','$dep')";                  
						mysql_query($query) or die('Insert data fail' . mysql_error());										
						header("Location: member.php?id=ip_info");
						die;
				    }
				}	
				else
				{
				    #echo '<p align="center"><a href="javascript:history.go(-1);">返回上一頁</a></p></br>';
					#echo '<font color="#FF0000"><h1  align="center">這個IP位址不是您負責管理的喔！！</h1></font>';
					header("Location: ip_info_add.php?errMsg=2&ip=".$ip);
					die;
				}
			    
			}
			else
			{
			    if($total==1)
				{
				    #echo '<p align="center"><a href="javascript:history.go(-1);">返回上一頁</a></p></br>';
				    #echo '<font color="#FF0000"><h1  align="center">這個IP位址不是您負責管理的喔@@</h1></font>';
				    header("Location: ip_info_add.php?errMsg=2&ip=".$ip);
					die;
				} 
			    else
				{
				    $total--;
				}
			}		    
		}
		
		/*
		$auto_insert_result = mysql_query("SELECT * FROM Department_Allocate_IP where inet_aton('$ip') between inet_aton(start_ip) and inet_aton(end_ip)");
		if (!$auto_insert_result)
		{
			echo '執行SQL資料表查詢失敗:'.mysql_error();
			exit;
		}
		
		$checkip = "select * from Block_IP_List where ip ='$ip'";
		$checkip_result = mysql_query($checkip);
		if (mysql_num_rows($checkip_result)>0)
		{
		    header("Location: block_ip_add.php?errMsg=1&ip=".$ip);
	        die;
		}
				
		
		    $auto_insert_row = mysql_fetch_assoc($auto_insert_result);
			$department = $auto_insert_row['department'];
			$name = $auto_insert_row['name'];   
			$title = $auto_insert_row['title'];
			$tel = $auto_insert_row['tel'];
			$email = $auto_insert_row['email'];
		
			$query = "insert into Block_IP_List (block_time,reason,ip,department,name,title,tel,email,process,note)
					  values('$date','$reason','$ip','$department','$name','$title','$tel','$email','$process','$note')";                  
			mysql_query($query) or die('Insert data fail' . mysql_error());	
		    header("Location: member.php?id=ip_info");
		*/
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
      
	  <?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="1")){?>
      <div class="errDiv">IP位址 <?php echo $_GET["ip"];?> 重覆了！</div>
      <?php }?>	  
	  <?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="2")){?>
      <div class="errDiv">IP位址 <?php echo $_GET["ip"];?> 不是您負責管理的！</div>
      <?php }?>
	  <?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="3")){?>
      <div class="errDiv">IP位址的格式不正確！</div>
      <?php }?>
	  
	  
	  
	  <div class="dataDiv">		  
      <hr size="1" />
	  <p><strong>IP　位址</strong>：
               <input name="ip1" type="text" size="5"  maxlength="3" value="140" readonly >
			   <strong>.</strong>
               <input name="ip2" type="text" size="5"  maxlength="3" value="120" readonly >
               <strong>.</strong>
               <input name="ip3" type="text" size="5" maxlength="3" class="normalinput" id="ip3">
               <strong>.</strong>
               <input name="ip4" type="text" size="5" maxlength="3" class="normalinput" id="ip4">
            </p>
			
			<p>
				<strong>用　　途</strong>：
				<select name="use">
				<option value="個人電腦">個人電腦</option>
				<option value="伺服器">伺服器</option>
				<option value="IP分享器">IP分享器</option>
				<option value="交換器">交換器</option>
				<option value="無線基地台">無線基地台</option>
				<option value="其他網路設備">其他網路設備</option>
				</select>  
            </p>
			
			<p><strong>負  責   人</strong> ：
            <input name="owner" type="text" class="normalinput" id="owner">
            </p>  
 
			<p>
			<strong>身　　分</strong>：
            <select name="title">
			<option value="教師">教師</option>
            <option value="職員">職員</option>
  		    <option value="學生">學生</option>
			</select>  
            </p>
			
			<p>
			<strong>電　　話</strong>：
            <input name="tel" type="text" class="normalinput" id="tel">
            </p> 
			
			<hr size="1" />
            
			<p align="center">
            <input name="action" type="hidden" id="action" value="add">
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
