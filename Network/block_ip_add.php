<?php
    include("../phpmailer/class.phpmailer.php"); 
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
	
	if(isset($_POST["action"]) && ($_POST["action"]=="add"))
	{
		if( ($_POST['ip3']=="" ) || ($_POST['ip4']==""))   //真是笨阿~~直接用""就好了
		{                                                
	       	echo '<p align="center"><a href="javascript:history.go(-1);">返回上一頁</a></p></br>';      
		   	echo '<font color="#FF0000"><h1  align="center">請輸入IP位址</h1></font>';
		   	die;  		                                     
	    }
	    elseif(!((0<=$_POST['ip3'] && $_POST['ip3']<=255)&&(0<=$_POST['ip4'] && $_POST['ip4']<=255)))
		{        
			echo '<p align="center"><a href="javascript:history.go(-1);">返回上一頁</a></p></br>';
			echo '<font color="#FF0000"><h1  align="center">您的ip位址格式不正確，請填寫正確的ip位址</h1></font>';
			die;  		                                                                                       
	    }
		
		$a1 = $_POST['ip1'];
		$a2 = $_POST['ip2'];
		$a3 = $_POST['ip3'];
		$a4 = $_POST['ip4'];
		
		$ip = "$a1.$a2.$a3.$a4";
		$date = date("Y-m-d H:i:s");
		$reason = $_POST['reason'];
		$note = $_POST['note'];
		$process = '待處理';
		
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
		
		
		$mail = new PHPMailer(); 
		$mail->IsSMTP();
		$mail->Host = "dragon.nchu.edu.tw";
		$mail->Port = 25;
		$mail->From = "chris@nchu.edu.tw"; 
		$mail->FromName = "chris"; 
		$mail->AddAddress("$email");
		#$mail->AddBCC("chris@nchu.edu.tw");
		#$mail->AddBCC("woody@nchu.edu.tw");
		$mail->AddBCC("yiwei0301@nchu.edu.tw");
		$mail->CharSet="utf-8";
		$mail->Encoding = "base64";
		$mail->IsHTML(true);
		$mail->Subject = "中興大學計資中心網路組--違規事件通知";	
		$mail->Body = "
			  <html><body>
              
			    網管同仁 您好：<br><br>
			  
			    1.貴單位所屬ip $ip 發生 $reason 行為，為避免影響校園網路正常運作，本中心已經於 $date 將此IP停權。<br>
			    2.煩請網管通知該使用者，瞭解 超流 行為發生原因，並請使用者進行處理。<br>
		        3.確定該使用者已經將問題解決，可連結至 http://netcc.nchu.edu.tw ，並進行IP開通申請。 <br><br>
			  
			    謝謝您的協助<br><br>
 
				計資中心 資訊網路組 敬上
			  
			  			  
              </body></html>";
		if(!$mail->Send()) 

		{ 

			echo "錯誤!信件無法送出<br>"; 

			echo "Mailer 錯誤訊息>>>> " . $mail->ErrorInfo; 

			exit; 

		} 
	
	    header("Location: adm.php");
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
      
	  <p  class="title">新增阻斷資料</p>
      
	  <?php if(isset($_GET["errMsg"]) && ($_GET["errMsg"]=="1")){?>
      <div class="errDiv">IP位址 <?php echo $_GET["ip"];?> 已經被鎖了！</div>
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
				<p><strong>原　　因</strong>：
               <select name="reason">
 		    <option value="超流">超流</option>
            <option value="攻擊行為">攻擊行為</option>
  		    <option value="流量異常">流量異常</option>
  			<option value="疑似侵權">疑似侵權</option>
  			</select>  
               </p>
			   <p><strong>備　　註</strong>：
               <textarea name="note" rows="8" cols="30"></textarea>
               </p> 
			   
			   <hr size="1" />
          <p align="center">
            <input name="action" type="hidden" id="action" value="add">
            <input type="submit" name="Submit2" value="送出申請">
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
			<font color='FF9933'>新增阻斷資料</font>
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
			<li class='function'><a href='adm.php?process=ip_info'>IP清冊管理</a></li>
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
