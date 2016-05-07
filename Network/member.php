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
	$result = mysql_query('SELECT * FROM Block_IP_List');
	if (!$result)
	{
        echo '執行SQL資料表查詢失敗:'.mysql_error();
    	exit;
    }
	
	if( isset($_GET["web"]) && ($_GET["web"]=="home"))
	{
		if(isset($_GET["action"]) && ($_GET["action"]=="process"))
		{
			$process_time = date("Y-m-d H:i:s");
			$process = '已處理';
			$sn = $_GET['id'];
			$ip_process = "update Block_IP_List set process='$process',process_time='$process_time' where sn='$sn'"; 
			mysql_query($ip_process) or die('Update fail' . mysql_error());	
			header("Location: member.php");
			die;
		}
		elseif(isset($_GET["action"]) && ($_GET["action"]=="again"))
		{
			$process = '待處理';
			$sn = $_GET['id'];
			$ip_process = "update Block_IP_List set process='$process',process_time='0000-00-00 00:00:00' where sn='$sn'"; 
			mysql_query($ip_process) or die('Update fail' . mysql_error());	
			header("Location: member.php");
			die;
		}
	}
	
    if( isset($_GET["action"]) && ($_GET["action"]=="process"))
	{
		$process_time = date("Y-m-d H:i:s");
		$process = '已處理';
		$sn = $_GET['id'];
		$ip_process = "update Block_IP_List set process='$process',process_time='$process_time' where sn='$sn'"; 
	    mysql_query($ip_process) or die('Update fail' . mysql_error());	
	    header("Location: member.php?id=not_process");
		die;
	}	
	 
	if( isset($_GET["action"]) && ($_GET["action"]=="again"))
	{
		$process = '待處理';
		$sn = $_GET['id'];
		$ip_process = "update Block_IP_List set process='$process',process_time='0000-00-00 00:00:00' where sn='$sn'"; 
		mysql_query($ip_process) or die('Update fail' . mysql_error());	
	    header("Location: member.php?id=complete");
		die;
	}
	
	if( isset($_GET["action"]) && ($_GET["action"]=="del_ip_info"))
	{
		$sn = $_GET['id'];
		$del_ip_info = "delete from IP_Information where sn='$sn'"; 
		mysql_query($del_ip_info) or die('delete fail' . mysql_error());	
	    header("Location: member.php?id=ip_info");
		die;
	}
	
	if( isset($_GET["action"]) && ($_GET["action"]=="cancel"))
	{
		$sn = $_GET['id'];
		$page = $_GET['page'];
		
		$query_del = "update  IP_Information set `unlimited`='',`apply_time`= '',`expire_time`= '' where sn = '$sn'";                  
	    mysql_query($query_del) or die('update data fail' . mysql_error());		
	    
		header("Location: member.php?id=ip_info&page=".$page);
		die;
	}	
	
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
		
		$auto_insert_row = mysql_fetch_assoc($auto_insert_result);
		$department = $auto_insert_row['department'];
		$name = $auto_insert_row['name'];   
		$title = $auto_insert_row['title'];
		$tel = $auto_insert_row['tel'];
		$email = $auto_insert_row['email'];
		
		$query = "insert into Block_IP_List (block_time,reason,ip,department,name,title,tel,email,process,note)
		          values('$date','$reason','$ip','$department','$name','$title','$tel','$email','$process','$note')";                  
		mysql_query($query) or die('Insert data fail' . mysql_error());	
		header("Location: member.php");
	}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>中興大學校園網路管理系統</title>

<link href="style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
function process_sure()
{
    if (confirm('\n您確定要申請開通嗎?')) 
	return true;
    return false;
}
function again_sure()
{
    if (confirm('\n您確定要回復阻斷嗎?')) 
	return true;
    return false;
}
function del_sure()
{
    if (confirm('\n您確定要刪除這筆資料嗎?')) 
	return true;
    return false;
}
function cancel()
{
	if (confirm('\n您確定要取消申請嗎?')) 
	return true;
	return false;
}
</script>
</head>
<body>
    <table width="1200" border="0" align="center" cellpadding="4" cellspacing="0">
        <tr>
            <td class="tdbline"><img src="images/top.jpg"  width="1200" height="100"></td>
        </tr>
        <tr>
			<td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="10">
        <tr valign="top">
			<td class="tdrline">
    
<?php     
		$name = $_SESSION['name'];
		$dep =  $_SESSION['department'];
		$Identity_check = ("SELECT * FROM Account_Management where name = '$name'");  //多餘，但是先這樣
		$Identity_check_result = mysql_query($Identity_check);
		$Identity_check_row = mysql_fetch_assoc($Identity_check_result);
			
		if(isset($_GET["id"]) && ($_GET["id"]=="history"))
		{
			
			$history_account_check = ("SELECT * FROM Block_IP_History where department = '$dep'");
			$history_account_result = mysql_query($history_account_check);
			$pageRow_records = 10;
			$num_pages = 1;				
			if (isset($_GET['page']))
			{
				$num_pages = $_GET['page'];
			}			
			
			$startRow_records = ($num_pages -1) * $pageRow_records;
			$query_limit_records = $history_account_check." LIMIT ".$startRow_records.", ".$pageRow_records; 
			$limit_records = mysql_query($query_limit_records);
			$total_records = mysql_num_rows($history_account_result);
			$total_pages = ceil( $total_records/$pageRow_records );    
			
			echo '<table width="1000" border="0" class="mytable">
					  <tr>
					  <th height="30" bgcolor="#0E4655" scope="col" width="120"><div align="center">IP位址</div></th>
					  <th height="30" bgcolor="#0E4655" scope="col" width="160"><div align="center">阻斷時間</div></th>
					  <th height="30" bgcolor="#0E4655" scope="col" width="160"><div align="center">處理時間</div></th>
					  <th height="30" bgcolor="#0E4655" scope="col" width="160"><div align="center">開通時間</div></th>
					  <th height="30" bgcolor="#0E4655" scope="col" width="100"><div align="center">開通時間</div></th>
					  <th height="30" bgcolor="#0E4655" scope="col" width="300"><div align="center">備註</div></th>
					  </tr>';
			
			
			while ($row = mysql_fetch_assoc($limit_records))
			{
			    echo "<tr>";
			    echo "<td height='30'><div align='center'>".$row['ip']."</div></td>";
			    echo "<td height='30'><div align='center'>".$row['block_time']."</div></td>";
			    echo "<td height='30'><div align='center'>".$row['process_time']."</div></td>";
			    echo "<td height='30'><div align='center'>".$row['resume_time']."</div></td>";
			    echo "<td height='30'><div align='center'>".$row['reason']."</div></td>"; 
			    echo "<td height='30'><div align='leftr'>".$row['note']."</div></td>";
				echo "</tr>";
			}
			echo "</table>";	
			echo '<hr size="1" />
			      <table width="98%" border="0" align="center" cellpadding="4" cellspacing="0">
				  <tr>';
            echo "<td valign='middle'><p>資料筆數：$total_records </p></td>";
            echo "<td align='right'><p>";
            
			$num1= $num_pages-1;
			$num2= $num_pages+1;
			if ($num_pages > 1) 
			{   
                echo "<a href='member.php?id=history&page=1'>第一頁</a>";
				echo "|";
				echo "<a href='member.php?id=history&page=".$num1."'>上一頁</a>";
				echo "|"; 
            }
 
            if ( $num_pages < $total_pages ) 
			{
                echo "<a href='member.php?id=history&page=".$num2."'>下一頁</a>";
			    echo "|";
				echo "<a href='member.php?id=history&page=".$total_pages."'>最末頁</a>";
            }
				echo'</p></td>
					 </tr>
					 </table><p>&nbsp;</p>';
				echo "<td width='200'>";
				echo "<div class='regbox'>";
				echo "<p class='heading'>用戶資料</p>";
				echo "<p><strong>";
				echo $name;
				echo $Identity_check_row['title'];
				echo ",您好！";
				echo "</strong>";
				echo "<br>";
				echo "您負責的單位為：";
				echo "<br>";
				echo "<font color=FF9933>".$Identity_check_row['department']."</font>";
				echo "<br>";
				echo "目前所在的頁面：";
				echo "<br>";
				echo "<font color='FF9933'>歷史資料清單</font>";
				echo "</p>";
				echo "<hr size='1'/>";
				echo "<p class='heading'>功能選單</p>";
				echo "<li class='function'><a href='member.php'>所有阻斷資料</a></li>";
				echo "<li class='function'><a href='member.php?id=not_process'>待處理資料</a></li>";
				echo "<li class='function'><a href='member.php?id=complete'>待開通資料</a></li>";
				echo "<li class='function'><a href='member.php?id=history'>歷史資料</a></li>";
				echo "<li class='function'><a href='member.php?id=ip_info'>IP清冊管理</a></li>";
				echo "<li class='function'><a href='member.php?logout=true'>登出</a></li>";
			    echo '</div>
					  </td>
					  </tr>
					  </table>
					  </td>
					  </tr>';
		}
				
		elseif(isset($_GET["id"]) && ($_GET["id"]=="not_process"))
		{
		   	$process_check = ("SELECT * FROM Block_IP_List where department = '$dep' and process='待處理'");
			$process_check_result = mysql_query($process_check);
			$pageRow_records = 10;
			$num_pages = 1;				
			if (isset($_GET['page']))
			{
				$num_pages = $_GET['page'];
			}			
			
			$startRow_records = ($num_pages -1) * $pageRow_records;
			$query_limit_records = $process_check." LIMIT ".$startRow_records.", ".$pageRow_records; 
			$limit_records = mysql_query($query_limit_records);
			$total_records = mysql_num_rows($process_check_result);
			$total_pages = ceil( $total_records/$pageRow_records );
			
			
			echo '<table width="1000" border="0" class="mytable">
				  <tr>
				  <th height="30" bgcolor="#0E4655" scope="col" width="80"><div align="center">功能</div></th>
				  <th height="30" bgcolor="#0E4655" scope="col" width="160"><div align="center">阻斷時間</div></th>
			      <th height="30" bgcolor="#0E4655" scope="col" width="120"><div align="center">IP位址</div></th>
				  <th height="30" bgcolor="#0E4655" scope="col" width="80"><div align="center">阻斷原因</div></th>
			      <th height="30" bgcolor="#0E4655" scope="col" width="80"><div align="center">目前狀態</div></th>
				  <th height="30" bgcolor="#0E4655" width="160"><div align="center">處理時間</div></th>
				  <th height="30" bgcolor="#0E4655" width="320"><div align="center">備註</div></th>
				  </tr>';
					
			while ($row = mysql_fetch_assoc($limit_records))
		    {        
				echo "<tr>";				
				echo "<input name='sn' type='hidden' value=".$row['sn'].">";
				echo "<td>";
				echo "<a href='member.php?action=process&id=".$row['sn']."' onClick='return process_sure();'><div align='center'>申請開通</div></a>";
				echo "</td>";
			    echo "<td height='30'><div align='center'>".$row['block_time']."</div></td>";
			    echo "<td height='30'><div align='center'>".$row['ip']."</div></td>";
			    echo "<td height='30'><div align='center'>".$row['reason']."</div></td>";
			    echo "<td height='30'><div align='center'>".$row['process']."</div></td>";
				echo "<td height='30'><div align='center'>".$row['process_time']."</div></td>";
				echo "<td height='30'><div align='leftr'>".$row['note']."</div></td>";
				echo "</tr>";
			}
				echo "</table>";
				echo '<hr size="1" />
					  <table width="98%" border="0" align="center" cellpadding="4" cellspacing="0">
					  <tr>';
                echo "<td valign='middle'><p>資料筆數：$total_records </p></td>";
                echo "<td align='right'><p>";
                $num1 = $num_pages-1;
				$num2 = $num_pages+1;
				if ($num_pages > 1) 
				{   
                    echo "<a href='member.php?id=not_process&page=1'>第一頁</a>";
					echo "|";
					echo "<a href='member.php?id=not_process&page=".$num1."'>上一頁</a>";
					echo "|"; 
                }
                if ( $num_pages < $total_pages ) 
				{
                    echo "<a href='member.php?id=not_process&page=".$num2."'>下一頁</a>";
				    echo "|";
				    echo "<a href='member.php?id=not_process&page=".$total_pages."'>最末頁</a>";
                }
				echo'</p></td>
					 </tr>
					 </table><p>&nbsp;</p>';
					
				echo "<td width='200'>";
				echo "<div class='regbox'>";
				echo "<p class='heading'>用戶資料</p>";
				
				echo "<p><strong>";
				echo $name;
				echo $Identity_check_row['title'];
				echo ",您好！";
				echo "</strong>";
				echo "<br>";
				echo "您負責的單位為：";
				echo "<br>";
				echo "<font color=FF9933>".$Identity_check_row['department']."</font>";
				echo "<br>";
				echo "目前所在的頁面：";
				echo "<br>";
				echo "<font color='FF9933'>待處理資料</font>";
				echo "</p>";
				echo "<hr size='1'/>";
				echo "<p class='heading'>功能選單</p>";
				echo "<li class='function'><a href='member.php'>所有阻斷資料</a></li>";
				echo "<li class='function'><a href='member.php?id=not_process'>待處理資料</a></li>";
				echo "<li class='function'><a href='member.php?id=complete'>待開通資料</a></li>";
				echo "<li class='function'><a href='member.php?id=history'>歷史資料</a></li>";
				echo "<li class='function'><a href='member.php?id=ip_info'>IP清冊管理</a></li>";
				echo "<li class='function'><a href='member.php?logout=true'>登出</a></li>";
			    echo '</div>
					  </td>
					  </tr>
					  </table>
					  </td>
					  </tr>';
                } 
			
		elseif(isset($_GET["id"]) && ($_GET["id"]=="complete"))
		{
			$process_check = ("SELECT * FROM Block_IP_List where department = '$dep' and process='已處理'");
			$process_check_result = mysql_query($process_check);
			$pageRow_records = 10;
			$num_pages = 1;				
			if (isset($_GET['page']))
			{
				$num_pages = $_GET['page'];
			}			
			
			$startRow_records = ($num_pages -1) * $pageRow_records;
			$query_limit_records = $process_check." LIMIT ".$startRow_records.", ".$pageRow_records; 
			$limit_records = mysql_query($query_limit_records);
			$total_records = mysql_num_rows($process_check_result);
			$total_pages = ceil( $total_records/$pageRow_records );
			echo '<table width="1000" border="0" class="mytable">
				  <tr>
				  <th height="30" bgcolor="#0E4655" scope="col" width="80"><div align="center">功能</div></th>
				  <th height="30" bgcolor="#0E4655" scope="col" width="160"><div align="center">阻斷時間</div></th>
				  <th height="30" bgcolor="#0E4655" scope="col" width="120"><div align="center">IP位址</div></th>
				  <th height="30" bgcolor="#0E4655" scope="col" width="80"><div align="center">阻斷原因</div></th>
				  <th height="30" bgcolor="#0E4655" scope="col" width="80"><div align="center">目前狀態</div></th>
				  <th height="30" bgcolor="#0E4655" width="160"><div align="center">處理時間</div></th>
				  <th height="30" bgcolor="#0E4655" width="320"><div align="center">備註</div></th>
				  </tr>';
			
			while ($row = mysql_fetch_assoc($limit_records))
			{        
				echo "<tr>";				
				echo "<input name='sn' type='hidden' value=".$row['sn'].">";
				echo "<td>";
				echo "<a href='member.php?action=again&id=".$row['sn']."' onClick='return again_sure();'><div align='center'>回復阻斷</div></a>"; 
				echo "</td>";
		        echo "<td height='30'><div align='center'>".$row['block_time']."</div></td>";
			    echo "<td height='30'><div align='center'>".$row['ip']."</div></td>";
			    echo "<td height='30'><div align='center'>".$row['reason']."</div></td>";
			    echo "<td height='30'><div align='center'>".$row['process']."</div></td>";
				echo "<td height='30'><div align='center'>".$row['process_time']."</div></td>";
				echo "<td height='30'><div align='leftr'>".$row['note']."</div></td>";
				echo "</tr>";
			}
				
			echo "</table>";
			echo '<hr size="1" />
				  <table width="98%" border="0" align="center" cellpadding="4" cellspacing="0">
				  <tr>';
            echo "<td valign='middle'><p>資料筆數：$total_records </p></td>";
            echo "<td align='right'><p>";
            $num1= $num_pages-1;
			$num2= $num_pages+1;
			if ($num_pages > 1) 
			{   
                echo "<a href='member.php?id=complete&page=1'>第一頁</a>";
				echo "|";
				echo "<a href='member.php?id=complete&page=".$num1."'>上一頁</a>";
				echo "|"; 
            }
            if ( $num_pages < $total_pages ) 
			{
                echo "<a href='member.php?id=complete&page=".$num2."'>下一頁</a>";
			    echo "|";
			    echo "<a href='member.php?id=complete&page=".$total_pages."'>最末頁</a>";
            }
			echo'</p></td>
				 </tr>
				 </table><p>&nbsp;</p>';
			
			echo "<td width='200'>";
			echo "<div class='regbox'>";
			echo "<p class='heading'>用戶資料</p>";
			echo "<p><strong>";
			echo $name;
			echo $Identity_check_row['title'];
			echo ",您好！";
			echo "</strong>";
			echo "<br>";
			echo "您負責的單位為：";
			echo "<br>";
			echo "<font color=FF9933>".$Identity_check_row['department']."</font>";
			echo "<br>";
			echo "目前所在的頁面：";
			echo "<br>";
			echo "<font color='FF9933'>待開通資料</font>";
			echo "</p>";
			echo "<hr size='1'/>";
			echo "<p class='heading'>功能選單</p>";
			echo "<li class='function'><a href='member.php'>所有阻斷資料</a></li>";
			echo "<li class='function'><a href='member.php?id=not_process'>待處理資料</a></li>";
			echo "<li class='function'><a href='member.php?id=complete'>待開通資料</a></li>";
			echo "<li class='function'><a href='member.php?id=history'>歷史資料</a></li>";
			echo "<li class='function'><a href='member.php?id=ip_info'>IP清冊管理</a></li>";
			echo "<li class='function'><a href='member.php?logout=true'>登出</a></li>";
				
            echo '</div>
				  </td>
				  </tr>
				  </table>
				  </td>
				  </tr>';
        }	
// test start
		elseif(isset($_GET["id"]) && ($_GET["id"]=="ip_info"))
		{
			echo "<form align='center' action='member.php?id=ip_info'  method='post'>";
			echo "<p>";
			
			echo "<strong>IP位址搜尋: </strong>";
			echo "140.120.";
			echo "<input type='text' size='1' maxlength='3' name='ip3'>";
		    echo ".";
			echo "<input type='text' size='1' maxlength='3' name='ip4'>";
			if( isset($_POST['ip_unlimited_history']) )
			{
			    echo "<input type='submit'  value='搜尋歷史' name='ip_search_history'>";
			}
			else
			{
			    echo "<input type='submit'  value='搜尋' name='ip_search'>";
			}
			echo "&nbsp";
			echo "&nbsp";
			echo "&nbsp";			
			echo "&nbsp";
			echo "&nbsp";
			echo "&nbsp";
			echo "<input type='submit'  value='新增資料' name='ip_info_add'>";	
			echo "&nbsp";
			echo "&nbsp";
			echo "&nbsp";			
			echo "&nbsp";
			echo "&nbsp";
			echo "&nbsp";
			echo "<input type='submit'  value='顯示所有資料'>";	
			echo "&nbsp";
			echo "&nbsp";
			echo "&nbsp";			
			echo "&nbsp";
			echo "&nbsp";
			echo "&nbsp";			
			echo "<input type='submit'  value='超流歷史資料查詢' name='ip_unlimited_history'>";			
			echo "</form>";
			echo "</p>";		
			echo "<hr size='1'/>";
					
			
			
			#if((isset($_POST['ip_search'])) || (isset($_GET['search_ip'])))
			if( isset($_POST['ip_search']) )
			{
				$ip = "140.120.".$_POST['ip3'].".".$_POST['ip4'];
				
				if( isset($_GET['search_ip']) !="")
				{
					$ip = $_GET['search_ip'];
					$identity_check = "select * from Block_IP_List where ip ='$ip'";
					$identity_check_query = mysql_query($identity_check);
					$row = mysql_fetch_assoc($identity_check_query);
					echo "<p align='center'><strong>".$ip."</strong>負責網管為：<strong>".$row['name'].$row['title']."</strong>聯絡方式為：".$row['tel']."</p>";
				}
				
				$sql = "select * from IP_Information where department='$dep' and ip='$ip'";
				#$sql = "select * from Block_IP_List where ip ='$ip'"; 
				$result = mysql_query($sql);
				$row = mysql_fetch_assoc($result);
				if (mysql_num_rows($result) == 0 )
				{
					$total_records = mysql_num_rows($result);
					echo "<p align='center'>"; 
					echo "這個IP在清單中沒有資料喔！！";
					echo "</p>";
				}
				else
				{
					echo '<table width="1000" border="0" class="mytable">
						  <tr>
						  <th height="30" bgcolor="#0E4655" scope="col" width="80"><div align="center">功能</div></th>
						  <th height="30" bgcolor="#0E4655" scope="col" width="120"><div align="center">IP位址</div></th>
						  <th height="30" bgcolor="#0E4655" scope="col" width="80"><div align="center">負責人</div></th>
						  <th height="30" bgcolor="#0E4655" scope="col" width="80"><div align="center">身分</div></th>
						  <th height="30" bgcolor="#0E4655" scope="col" width="160"><div align="center">校內分機</div></th>
						  <th height="30" bgcolor="#0E4655" scope="col" width="160"><div align="center">用途</div></th>
						  <th height="30" bgcolor="#0E4655" scope="col" width="160"><div align="center">超流申請</div></th>
						  <th height="30" bgcolor="#0E4655" width="160"><div align="center">修改日期</div></th>
						  </tr>';
					echo "<tr>";				
					echo "<input name='sn' type='hidden' value=".$row['sn'].">";
					echo "<td>";
				    echo "<a href='ip_info_edit.php?id=".$row['sn']."&page=".$num_pages."'><div align='center'>編輯</div></a>";     
					echo "<a href='member.php?action=del_ip_info&id=".$row['sn']."' onClick='return del_sure();'><div align='center'>刪除</div></a>";
					echo "</td>";
					echo "<td height='30'><div align='center'>".$row['ip']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['owner']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['title']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['tel']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['use']."</div></td>";
 				    echo "<td>";
					if($row['unlimited']=='Y')
					{
						$time = explode("-",$row['expire_time']);
						$a1 = $time[0];
						$a2 = $time[1];
						$a3 = $time[2];
						$now = explode("-",date("Y-m-d"));
						$b1 = $now[0];
						$b2 = $now[1];
						$b3 = $now[2];
	
						if(  ($b1>$a1)  ||   (($b1==$a1)&&($b2>$a2))  ||  (($b1==$a1)&&($b2==$a2)&&($b3>$a3))  )
						{
   
							$owner = $row['owner'];
							$ip = $row['ip'];
							$title = $row['title'];
							$tel  = $row['tel'];
							$use =  $row['use'];
							$apply_time = $row['apply_time'];
							$expire_time = $row['expire_time'];
							$department = $row['department'];
							$memo = $row['memo'];
	           
							$query = "insert into Unlimited_Quota_History (`ip`,`owner`,`title`,`tel`,`use`,`apply_time`,`expire_time`,`department`,`memo`) values ('$ip','$owner','$title','$tel','$use','$apply_time','$expire_time','$department','$memo')";
							mysql_query($query) or die('Insert data fail' . mysql_error());	
							$query_del = "update  IP_Information set `unlimited`='',`apply_time`= '',`expire_time`= '' where ip = '$ip'";                  
							mysql_query($query_del) or die('update data fail' . mysql_error());	
										
							echo  "<a href='ip_unlimited.php?page=".$num_pages."&id=".$row['sn']."'><div align='center'>申請超流</div></a>";
					
						}
						else
						{
							echo  "<div align='center'>已申請超流</div>";  
						}  
					}
					elseif($row['unlimited']=='W')
					{
						echo  "<div align='center'>";
						echo  "審核中";  
						echo  "<a href='member.php?action=cancel&page=".$num_pages."&id=".$row['sn']."' onClick='return cancel();'>(取消申請)</a>";  
						echo  "</div>";
					}
					else
					{
						echo  "<a href='ip_unlimited.php?page=".$num_pages."&id=".$row['sn']."'><div align='center'>申請超流</div></a>";
					}				
					echo "</td>";
     				echo "<td height='30'><div align='center'>".$row['time']."</div></td>";
					echo "</tr>";
					echo "</table>";
				}			   
			}		
			
			//test start 1119
			elseif( isset($_POST['ip_search_history']) )
			{
			    $ip = "140.120.".$_POST['ip3'].".".$_POST['ip4'];
				$sql = ("SELECT * FROM Unlimited_Quota_History where department='$dep' and ip='$ip'");
				
			    $result = mysql_query($sql);
				$row = mysql_fetch_assoc($result);
				if (mysql_num_rows($result) == 0 )
				{
					$total_records = mysql_num_rows($result);
					echo "<p align='center'>"; 
					echo "這個IP在超流歷史清單中沒有資料喔！！";
					echo "</p>";
				}
				else
				{
					
					echo '<table width="100%" border="0" class="mytable">
						  <tr>				     
						  <th height="30" bgcolor="#0E4655" scope="col" width="12%"><div align="center">IP位址</div></th>
						  <th height="30" bgcolor="#0E4655" scope="col" width="8%"><div align="center">負責人</div></th>
						  <th height="30" bgcolor="#0E4655" scope="col" width="8%"><div align="center">職稱</div></th>
						  <th height="30" bgcolor="#0E4655" scope="col" width="16%"><div align="center">校內分機</div></th>
						  <th height="30" bgcolor="#0E4655" scope="col" width="16%"><div align="center">用途</div></th>
						  <th height="30" bgcolor="#0E4655" scope="col" width="10%"><div align="center">超流日期</div></th>
						  <th height="30" bgcolor="#0E4655" scope="col" width="10%"><div align="center">截止日期</div></th>
						  <th height="30" bgcolor="#0E4655" scope="col" width="20%"><div align="center">備註</div></th>
						  </tr>';
					while($row = mysql_fetch_assoc($result))
					{
					    echo "<tr>";				
						echo "<input name='sn' type='hidden' value=".$row['sn'].">";		
						echo "<td height='30'><div align='center'>".$row['ip']."</div></td>";
						echo "<td height='30'><div align='center'>".$row['owner']."</div></td>";
						echo "<td height='30'><div align='center'>".$row['title']."</div></td>";
						echo "<td height='30'><div align='center'>".$row['tel']."</div></td>";
						echo "<td height='30'><div align='center'>".$row['use']."</div></td>";
						echo "<td height='30'><div align='center'>".$row['apply_time']."</div></td>";
						echo "<td height='30'><div align='center'>".$row['expire_time']."</div></td>";
						echo "<td height='30'><div align='center'>".$row['memo']."</div></td>";
						echo "</tr>";			
				    }
				    $total_records = mysql_num_rows($result);
				}
			}
			
			//test end 1119
						
			elseif( isset($_POST['ip_unlimited_history']) )
			{
			    $ip_info = ("SELECT * FROM Unlimited_Quota_History where department = '$dep'");
			    $ip_info_result = mysql_query($ip_info);		
			    $pageRow_records = 10;
			    $num_pages = 1;				
			    if (isset($_GET['page']))
			    {
				    $num_pages = $_GET['page'];
			    }			
			    $startRow_records = ($num_pages -1) * $pageRow_records;
			    $query_limit_records = $ip_info." LIMIT ".$startRow_records.", ".$pageRow_records; 
    			$limit_records = mysql_query($query_limit_records);
			    $total_records = mysql_num_rows($ip_info_result);
			    $total_pages = ceil( $total_records/$pageRow_records );
			    echo '<table width="1000" border="0" class="mytable">
				      <tr>				     
			          <th height="30" bgcolor="#0E4655" scope="col" width="120"><div align="center">IP位址</div></th>
				      <th height="30" bgcolor="#0E4655" scope="col" width="80"><div align="center">負責人</div></th>
				      <th height="30" bgcolor="#0E4655" scope="col" width="80"><div align="center">職稱</div></th>
				      <th height="30" bgcolor="#0E4655" scope="col" width="160"><div align="center">校內分機</div></th>
				      <th height="30" bgcolor="#0E4655" scope="col" width="160"><div align="center">用途</div></th>
				      <th height="30" bgcolor="#0E4655" scope="col" width="100"><div align="center">超流日期</div></th>
				      <th height="30" bgcolor="#0E4655" scope="col" width="100"><div align="center">截止日期</div></th>
					  <th height="30" bgcolor="#0E4655" scope="col" width="200"><div align="center">備註</div></th>
				      </tr>';
				while ($row = mysql_fetch_assoc($limit_records))
				{
					echo "<tr>";				
					echo  "<input name='sn' type='hidden' value=".$row['sn'].">";		
					echo "<td height='30'><div align='center'>".$row['ip']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['owner']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['title']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['tel']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['use']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['apply_time']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['expire_time']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['memo']."</div></td>";
					echo "</tr>";			
				}
				#echo "</table>";
			}			
            elseif(isset($_POST['ip_info_add']))
			{
			    header("Location: ip_info_add.php");
			}
			
			else
			{
			    $ip_info = ("SELECT * FROM IP_Information where department = '$dep'");
			    $ip_info_result = mysql_query($ip_info);		
			    $pageRow_records = 10;
			    $num_pages = 1;				
			    if (isset($_GET['page']))
			    {
				    $num_pages = $_GET['page'];
			    }			
			    $startRow_records = ($num_pages -1) * $pageRow_records;
			    $query_limit_records = $ip_info." LIMIT ".$startRow_records.", ".$pageRow_records; 
    			$limit_records = mysql_query($query_limit_records);
			    $total_records = mysql_num_rows($ip_info_result);
			    $total_pages = ceil( $total_records/$pageRow_records );
			    echo '<table width="1000" border="0" class="mytable">
				      <tr>
				      <th height="30" bgcolor="#0E4655" scope="col" width="80"><div align="center">功能</div></th>
			          <th height="30" bgcolor="#0E4655" scope="col" width="120"><div align="center">IP位址</div></th>
				      <th height="30" bgcolor="#0E4655" scope="col" width="80"><div align="center">負責人</div></th>
				      <th height="30" bgcolor="#0E4655" scope="col" width="80"><div align="center">職稱</div></th>
				      <th height="30" bgcolor="#0E4655" scope="col" width="160"><div align="center">校內分機</div></th>
				      <th height="30" bgcolor="#0E4655" scope="col" width="160"><div align="center">用途</div></th>
				      <th height="30" bgcolor="#0E4655" scope="col" width="160"><div align="center">超流申請</div></th>
				      <th height="30" bgcolor="#0E4655" width="160"><div align="center">修改日期</div></th>
				      </tr>';
				while ($row = mysql_fetch_assoc($limit_records))
				{
					echo "<tr>";				
					echo "<input name='sn' type='hidden' value=".$row['sn'].">";
					echo "<td>";
					echo "<a href='ip_info_edit.php?id=".$row['sn']."&page=".$num_pages."'><div align='center'>編輯</div></a>";    
					echo "<a href='member.php?action=del_ip_info&id=".$row['sn']."' onClick='return del_sure();'><div align='center'>刪除</div></a>";
					echo "</td>";
					echo "<td height='30'><div align='center'>".$row['ip']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['owner']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['title']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['tel']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['use']."</div></td>";
					echo "<td>";
					if($row['unlimited']=='Y')
					{					
						$time = explode("-",$row['expire_time']);
						$a1 = $time[0];
						$a2 = $time[1];
						$a3 = $time[2];
						$now = explode("-",date("Y-m-d"));
						$b1 = $now[0];
						$b2 = $now[1];
						$b3 = $now[2];
	
						if(  ($b1>$a1)  ||   (($b1==$a1)&&($b2>$a2))  ||  (($b1==$a1)&&($b2==$a2)&&($b3>$a3))  )
						{
							$owner = $row['owner'];
							$ip = $row['ip'];
							$title = $row['title'];
							$tel  = $row['tel'];
							$use =  $row['use'];
							$apply_time = $row['apply_time'];
							$expire_time = $row['expire_time'];
							$department = $row['department'];
							$memo = $row['memo'];
							$query = "insert into Unlimited_Quota_History (`ip`,`owner`,`title`,`tel`,`use`,`apply_time`,`expire_time`,`department`,`memo`) values ('$ip','$owner','$title','$tel','$use','$apply_time','$expire_time','$department','$memo')";
							
							mysql_query($query) or die('Insert data fail' . mysql_error());	
							$query_del = "update  IP_Information set `unlimited`='',`apply_time`= '',`expire_time`= '' where ip = '$ip'";                  
							mysql_query($query_del) or die('update data fail' . mysql_error());	
							
							echo  "<a href='ip_unlimited.php?page=".$num_pages."&id=".$row['sn']."'><div align='center'>申請超流</div></a>";
						}
						else
						{
							echo  "<div align='center'>已申請超流</div>";  
						}				
					}
					
					elseif($row['unlimited']=='W')
					{
						echo  "<div align='center'>";
						echo  "審核中";  
						echo  "<a href='member.php?action=cancel&page=".$num_pages."&id=".$row['sn']."' onClick='return cancel();'>(取消申請)</a>";  
						echo  "</div>";
					}
					
					else
					{
						echo  "<a href='ip_unlimited.php?page=".$num_pages."&id=".$row['sn']."'><div align='center'>申請超流</div></a>";
					}			
				
					echo "</td>";
					echo "<td height='30'><div align='center'>".$row['time']."</div></td>";
					echo "</tr>";
				}
		    
			
			}
			
			
			#echo "</table>";
			echo '<table width="98%" border="0" align="center" cellpadding="4" cellspacing="0">
			      <tr>
				  <hr size="1" />';
			
			echo "<td valign='middle'><p>資料筆數：$total_records </p></td>";
			echo "<td align='right'><p>";
			$num1= $num_pages-1;
			$num2= $num_pages+1;
			if ($num_pages > 1) 
			{   
				echo "<a href='member.php?id=ip_info&page=1'>第一頁</a>";
				echo "|";
				echo "<a href='member.php?id=ip_info&page=".$num1."'>上一頁</a>";
				echo "|"; 
			}
			if ( $num_pages < $total_pages ) 
			{
				echo "<a href='member.php?id=ip_info&page=".$num2."'>下一頁</a>";
				echo "|";
				echo "<a href='member.php?id=ip_info&page=".$total_pages."'>最末頁</a>";
			}
				
			echo'</p></td>
				 </tr>
			     </table><p>&nbsp;</p>';
					
			echo "<td width='200'>";
			echo "<div class='regbox'>";
			echo "<p class='heading'>用戶資料</p>";
			echo "<p><strong>";
			echo $name;
			echo $Identity_check_row['title'];
			echo ",您好！";	
			echo "</strong>";
			echo "<br>";
			echo "您負責的單位為：";
			echo "<br>";
			echo "<font color=FF9933>".$Identity_check_row['department']."</font>";
			echo "<br>";
			echo "目前所在的頁面：";
			echo "<br>";
			echo "<font color='FF9933'>所有阻斷資料</font>";
			echo "</p>";
			echo "<hr size='1'/>";
			echo "<p class='heading'>功能選單</p>";
			echo "<li class='function'><a href='member.php'>所有阻斷資料</a></li>";
			echo "<li class='function'><a href='member.php?id=not_process'>待處理資料</a></li>";
			echo "<li class='function'><a href='member.php?id=complete'>待開通資料</a></li>";
			echo "<li class='function'><a href='member.php?id=history'>歷史資料</a></li>";
			echo "<li class='function'><a href='member.php?id=ip_info'>IP清冊管理</a></li>";
			echo "<li class='function'><a href='member.php?logout=true'>登出</a></li>";
			echo '</div>
				  </td>
			      </tr>
			      </table>
			      </td>
			      </tr>';
        }	
		

//test end		
		else
		{
			$all_list = ("SELECT * FROM Block_IP_List where department = '$dep'");
			$all_list_result = mysql_query($all_list);		
			$pageRow_records = 10;
			$num_pages = 1;				
			if (isset($_GET['page']))
			{
				$num_pages = $_GET['page'];
			}			
			$startRow_records = ($num_pages -1) * $pageRow_records;
			$query_limit_records = $all_list." LIMIT ".$startRow_records.", ".$pageRow_records; 
			$limit_records = mysql_query($query_limit_records);
			$total_records = mysql_num_rows($all_list_result);
			$total_pages = ceil( $total_records/$pageRow_records );
			echo '<table width="1000" border="0" class="mytable">
				  <tr>
				  <th height="30" bgcolor="#0E4655" scope="col" width="80"><div align="center">功能</div></th>
			      <th height="30" bgcolor="#0E4655" scope="col" width="160"><div align="center">阻斷時間</div></th>
				  <th height="30" bgcolor="#0E4655" scope="col" width="120"><div align="center">IP位址</div></th>
				  <th height="30" bgcolor="#0E4655" scope="col" width="80"><div align="center">阻斷原因</div></th>
				  <th height="30" bgcolor="#0E4655" scope="col" width="80"><div align="center">目前狀態</div></th>
				  <th height="30" bgcolor="#0E4655" width="160"><div align="center">處理時間</div></th>
				  <th height="30" bgcolor="#0E4655" width="320"><div align="center">備註</div></th>
				  </tr>';
			while ($row = mysql_fetch_assoc($limit_records))
			{
		    	echo "<tr>";				
				echo  "<input name='sn' type='hidden' value=".$row['sn'].">";
				echo "<td>";
				if($row['process']=='已處理')
				{
					echo  "<a href='member.php?action=again&web=home&id=".$row['sn']."' onClick='return again_sure();'><div align='center'>回復阻斷</div></a>";  
				}
				else
				{
					echo "<a href='member.php?action=process&web=home&id=".$row['sn']."' onClick='return process_sure();'><div align='center'>申請開通</div></a>";
				}
					echo "</td>";
					echo "<td height='30'><div align='center'>".$row['block_time']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['ip']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['reason']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['process']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['process_time']."</div></td>";
					echo "<td height='30'><div align='leftr'>".$row['note']."</div></td>";
					echo "</tr>";
			}
			
			echo "</table>";
			echo '<hr size="1" />
				  <table width="98%" border="0" align="center" cellpadding="4" cellspacing="0">
			      <tr>';
			echo "<td valign='middle'><p>資料筆數：$total_records </p></td>";
			echo "<td align='right'><p>";
			$num1= $num_pages-1;
			$num2= $num_pages+1;
			if ($num_pages > 1) 
			{   
				echo "<a href='member.php?page=1'>第一頁</a>";
				echo "|";
				echo "<a href='member.php?page=".$num1."'>上一頁</a>";
				echo "|"; 
			}
			if ( $num_pages < $total_pages ) 
			{
				echo "<a href='member.php?page=".$num2."'>下一頁</a>";
				echo "|";
				echo "<a href='member.php?page=".$total_pages."'>最末頁</a>";
			}
				echo'</p></td>
					 </tr>
				     </table><p>&nbsp;</p>';
					
			echo "<td width='200'>";
			echo "<div class='regbox'>";
			echo "<p class='heading'>用戶資料</p>";
			echo "<p><strong>";
			echo $name;
			echo $Identity_check_row['title'];
			echo ",您好！";	
			echo "</strong>";
			echo "<br>";
			echo "您負責的單位為：";
			echo "<br>";
			echo "<font color=FF9933>".$Identity_check_row['department']."</font>";
			echo "<br>";
			echo "目前所在的頁面：";
			echo "<br>";
			echo "<font color='FF9933'>所有阻斷資料</font>";
			echo "</p>";
			echo "<hr size='1'/>";
			echo "<p class='heading'>功能選單</p>";
			echo "<li class='function'><a href='member.php'>所有阻斷資料</a></li>";
			echo "<li class='function'><a href='member.php?id=not_process'>待處理資料</a></li>";
			echo "<li class='function'><a href='member.php?id=complete'>待開通資料</a></li>";
			echo "<li class='function'><a href='member.php?id=history'>歷史資料</a></li>";
			echo "<li class='function'><a href='member.php?id=ip_info'>IP清冊管理</a></li>";
			echo "<li class='function'><a href='member.php?logout=true'>登出</a></li>";
			echo '</div>
				  </td>
			      </tr>
			      </table>
			      </td>
			      </tr>';
		} 
?>		
<tr>
	<td align="center" background="images/album_r2_c1.jpg" class="trademark">© 版權所有 國立中興大學計算機及資訊網路中心</td>
</tr>
</table>
</body>
</html>
