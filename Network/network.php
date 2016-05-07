<?php
    session_start();
    if(!isset($_SESSION["account"]) || ($_SESSION["account"]==""))
	{
        header("Location: login.php");
    }
	   if(!isset($_SESSION["level"]) || ($_SESSION["level"]=="member"))
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
			header("Location: network.php");
			die;
		}
		elseif(isset($_GET["action"]) && ($_GET["action"]=="again"))
		{
			$process = '待處理';
			$sn = $_GET['id'];
			$ip_process = "update Block_IP_List set process='$process',process_time='0000-00-00 00:00:00' where sn='$sn'"; 
			mysql_query($ip_process) or die('Update fail' . mysql_error());	
			header("Location: network.php");
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
	    header("Location: network.php?process=not_process");
		die;
	}	
	 
	if( isset($_GET["action"]) && ($_GET["action"]=="again"))
	{
		$process = '待處理';
		$sn = $_GET['id'];
		$ip_process = "update Block_IP_List set process='$process',process_time='0000-00-00 00:00:00' where sn='$sn'"; 
		mysql_query($ip_process) or die('Update fail' . mysql_error());	
	    header("Location: network.php?process=complete");
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
		header("Location: network.php");
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
		$Identity_check = ("SELECT * FROM Account_Management where name = '$name'");  //多餘，但是先這樣
		$Identity_check_result = mysql_query($Identity_check);
		$Identity_check_row = mysql_fetch_assoc($Identity_check_result);
			
		if(isset($_GET["process"]) && ($_GET["process"]=="history"))
		{
			
			$history_account_check = ("SELECT * FROM Block_IP_History order by block_time desc");
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
			
			if( isset($_GET["action"]) && ($_GET["action"]=="show_info") )
			{
				$sn = $_GET['id'];
				$identity_check = "select * from Block_IP_History where sn ='$sn'";
				$identity_check_query = mysql_query($identity_check);
				$row = mysql_fetch_assoc($identity_check_query);
				echo "<p align='center'><strong>".$row['department']."</strong>負責網管為：<strong>".$row['name'].$row['title']."</strong>聯絡方式為：".$row['tel']."</p>";
			}		
			
			echo '<table width="100%" border="0" class="mytable">
					  <tr>
					  <th height="30" bgcolor="#0E4655" width="120"><div align="center">IP位址</div></th>
					  <th height="30" bgcolor="#0E4655" width="100"><div align="center">阻斷時間</div></th>
					  <th height="30" bgcolor="#0E4655" width="100"><div align="center">處理時間</div></th>
					  <th height="30" bgcolor="#0E4655" width="100"><div align="center">開通時間</div></th>
					  <th height="30" bgcolor="#0E4655" width="180"><div align="center">系所單位</div></th>
					  <th height="30" bgcolor="#0E4655" width="100"><div align="center">阻斷原因</div></th>
					  <th height="30" bgcolor="#0E4655" width="300"><div align="center">備註</div></th>
					  </tr>';
			
			
			while ($row = mysql_fetch_assoc($limit_records))
			{
			    echo "<tr>";
			    echo "<td height='30'><div align='center'>".$row['ip']."</div></td>";
			    echo "<td height='30'><div align='center'>".$row['block_time']."</div></td>";
			    echo "<td height='30'><div align='center'>".$row['process_time']."</div></td>";
			    echo "<td height='30'><div align='center'>".$row['resume_time']."</div></td>";
				echo "<td height='30'><a href='network.php?page=".$num_pages."&action=show_info&process=history&id=".$row['sn']."'><div align='center'>".$row['department']."</div></a></td>";
			    echo "<td height='30'><div align='center'>".$row['reason']."</div></td>"; 
			    echo "<td height='30'><div align='left'>".$row['note']."</div></td>";
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
                echo "<a href='network.php?process=history&page=1'>第一頁</a>";
				echo "|";
				echo "<a href='network.php?process=history&page=".$num1."'>上一頁</a>";
				echo "|"; 
            }
 
            if ( $num_pages < $total_pages ) 
			{
                echo "<a href='network.php?process=history&page=".$num2."'>下一頁</a>";
			    echo "|";
				echo "<a href='network.php?process=history&page=".$total_pages."'>最末頁</a>";
            }
				echo'</p></td>
					 </tr>
					 </table><p>&nbsp;</p>';
				echo "<td width='200'>";
				echo "<div class='regbox'>";
				echo "<p class='heading'>用戶資料</p>";
				echo "<p><strong>";
				echo $account;
				echo ",您好！";	
				echo "</strong>";
				echo "<br>";
				echo "目前所在的頁面：";
				echo "<br>";
				echo "<font color='FF9933'>歷史資料清單</font>";
				echo "</p>";
				echo "<hr size='1'/>";
				echo "<p class='heading'>功能選單</p>";
				echo "<li class='function'><a href='add_list_network.php'>IP資料清單</a></li>";
				echo "<li class='function'><a href='network.php'>阻斷資料清單</a></li>";
				echo "<li class='function'><a href='network.php?process=not_process'>待處理資料</a></li>";
				echo "<li class='function'><a href='network.php?process=complete'>待開通資料</a></li>";
				echo "<li class='function'><a href='network.php?process=history'>歷史資料</a></li>";
				echo "<li class='function'><a href='network.php?logout=true'>登出</a></li>";
			    echo '</div>
					  </td>
					  </tr>
					  </table>
					  </td>
					  </tr>';
		}
				
		elseif(isset($_GET["process"]) && ($_GET["process"]=="not_process"))
		{
		   	$process_check = ("SELECT * FROM Block_IP_List where  process='待處理' order by block_time desc");
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
			
			
			if( isset($_GET["action"]) && ($_GET["action"]=="show_info") )
			{
				$sn = $_GET['id'];
				$identity_check = "select * from Block_IP_List where sn ='$sn'";
				$identity_check_query = mysql_query($identity_check);
				$row = mysql_fetch_assoc($identity_check_query);
				echo "<p align='center'><strong>".$row['department']."</strong>負責網管為：<strong>".$row['name'].$row['title']."</strong>聯絡方式為：".$row['tel']."</p>";
			}
			echo '<table width="100%" border="0" class="mytable">
				  <tr>
				  <th height="30" bgcolor="#0E4655" width="8%"><div align="center">功能</div></th>
				  <th height="30" bgcolor="#0E4655" width="10%"><div align="center">阻斷時間</div></th>
				  <th height="30" bgcolor="#0E4655" width="12%"><div align="center">IP位址</div></th>
				  <th height="30" bgcolor="#0E4655" width="14%"><div align="center">系所單位</div></th>
				  <th height="30" bgcolor="#0E4655" width="8%"><div align="center">阻斷原因</div></th>
				  <th height="30" bgcolor="#0E4655" width="8%"><div align="center">目前狀態</div></th>
				  <th height="30" bgcolor="#0E4655" width="10%"><div align="center">處理時間</div></th>
				  <th height="30" bgcolor="#0E4655" width="30%"><div align="center">備註</div></th>
				  </tr>';
					
			while ($row = mysql_fetch_assoc($limit_records))
		    {        
				echo "<tr>";				
				echo "<input name='sn' type='hidden' value=".$row['sn'].">";
				echo "<td>";
				echo "放啥好";
				echo "</td>";
			    echo "<td height='30'><div align='center'>".$row['block_time']."</div></td>";
			    echo "<td height='30'><div align='center'>".$row['ip']."</div></td>";
				echo "<td height='30'><a href='network.php?page=".$num_pages."&action=show_info&process=not_process&id=".$row['sn']."'><div align='center'>".$row['department']."</div></a></td>";
			    echo "<td height='30'><div align='center'>".$row['reason']."</div></td>";
			    echo "<td height='30'><div align='center'>".$row['process']."</div></td>";
				echo "<td height='30'><div align='center'>".$row['process_time']."</div></td>";
				echo "<td height='30'><div align='left'>".$row['note']."</div></td>";
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
                    echo "<a href='network.php?process=not_process&page=1'>第一頁</a>";
					echo "|";
					echo "<a href='network.php?process=not_process&page=".$num1."'>上一頁</a>";
					echo "|"; 
                }
                if ( $num_pages < $total_pages ) 
				{
                    echo "<a href='network.php?process=not_process&page=".$num2."'>下一頁</a>";
				    echo "|";
				    echo "<a href='network.php?process=not_process&page=".$total_pages."'>最末頁</a>";
                }
				echo'</p></td>
					 </tr>
					 </table><p>&nbsp;</p>';
					
				echo "<td width='200'>";
				echo "<div class='regbox'>";
				echo "<p class='heading'>用戶資料</p>";
				echo "<p><strong>";
				echo $account;
				echo ",您好！";	
				echo "</strong>";
				echo "<br>";
				echo "目前所在的頁面：";
				echo "<br>";
				echo "<font color='FF9933'>待處理資料</font>";
				echo "</p>";
				echo "<hr size='1'/>";
				echo "<p class='heading'>功能選單</p>";
				echo "<li class='function'><a href='add_list_network.php'>IP資料清單</a></li>";
				echo "<li class='function'><a href='network.php'>阻斷資料清單</a></li>";
				echo "<li class='function'><a href='network.php?process=not_process'>待處理資料</a></li>";
				echo "<li class='function'><a href='network.php?process=complete'>待開通資料</a></li>";
				echo "<li class='function'><a href='network.php?process=history'>歷史資料</a></li>";
				echo "<li class='function'><a href='network.php?logout=true'>登出</a></li>";
			    echo '</div>
					  </td>
					  </tr>
					  </table>
					  </td>
					  </tr>';
                } 
			
		elseif(isset($_GET["process"]) && ($_GET["process"]=="complete"))
		{
			$process_check = ("SELECT * FROM Block_IP_List where  process='已處理'");
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
			if( isset($_GET["action"]) && ($_GET["action"]=="show_info") )
			{
				$sn = $_GET['id'];
				$identity_check = "select * from Block_IP_List where sn ='$sn'";
				$identity_check_query = mysql_query($identity_check);
				$row = mysql_fetch_assoc($identity_check_query);
				echo "<p align='center'><strong>".$row['department']."</strong>負責網管為：<strong>".$row['name'].$row['title']."</strong>聯絡方式為：".$row['tel']."</p>";
			}
			echo '<table width="100%" border="0" class="mytable">
				  <tr>
				  <th height="30" bgcolor="#0E4655" width="8%"><div align="center">功能</div></th>
				  <th height="30" bgcolor="#0E4655" width="10%"><div align="center">阻斷時間</div></th>
				  <th height="30" bgcolor="#0E4655" width="12%"><div align="center">IP位址</div></th>
				  <th height="30" bgcolor="#0E4655" width="14%"><div align="center">系所單位</div></th>
				  <th height="30" bgcolor="#0E4655" width="8%"><div align="center">阻斷原因</div></th>
				  <th height="30" bgcolor="#0E4655" width="8%"><div align="center">目前狀態</div></th>
				  <th height="30" bgcolor="#0E4655" width="10%"><div align="center">處理時間</div></th>
				  <th height="30" bgcolor="#0E4655" width="30%"><div align="center">備註</div></th>
				  </tr>';
			
			while ($row = mysql_fetch_assoc($limit_records))
			{        
				echo "<tr>";				
				
				echo "<td>";
				echo "放啥好"; 
				echo "</td>";
		        echo "<td height='30'><div align='center'>".$row['block_time']."</div></td>";
			    echo "<td height='30'><div align='center'>".$row['ip']."</div></td>";
				echo "<td height='30'><a href='network.php?page=".$num_pages."&action=show_info&process=complete&id=".$row['sn']."'><div align='center'>".$row['department']."</div></a></td>";
			    echo "<td height='30'><div align='center'>".$row['reason']."</div></td>";
			    echo "<td height='30'><div align='center'>".$row['process']."</div></td>";
				echo "<td height='30'><div align='center'>".$row['process_time']."</div></td>";
				echo "<td height='30'><div align='left'>".$row['note']."</div></td>";
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
                echo "<a href='network.php?process=complete&page=1'>第一頁</a>";
				echo "|";
				echo "<a href='network.php?process=complete&page=".$num1."'>上一頁</a>";
				echo "|"; 
            }
            if ( $num_pages < $total_pages ) 
			{
                echo "<a href='network.php?process=complete&page=".$num2."'>下一頁</a>";
			    echo "|";
			    echo "<a href='network.php?process=complete&page=".$total_pages."'>最末頁</a>";
            }
			echo'</p></td>
				 </tr>
				 </table><p>&nbsp;</p>';
			
			echo "<td width='200'>";
			echo "<div class='regbox'>";
			echo "<p class='heading'>用戶資料</p>";
			echo "<p><strong>";
			echo $account;
			echo ",您好！";	
			echo "</strong>";
			echo "<br>";
			echo "目前所在的頁面：";
			echo "<br>";
			echo "<font color='FF9933'>待開通資料</font>";
			echo "</p>";
			echo "<hr size='1'/>";
			echo "<p class='heading'>功能選單</p>";
			echo "<li class='function'><a href='add_list_network.php'>IP資料清單</a></li>";
			echo "<li class='function'><a href='network.php'>阻斷資料清單</a></li>";
			echo "<li class='function'><a href='network.php?process=not_process'>待處理資料</a></li>";
			echo "<li class='function'><a href='network.php?process=complete'>待開通資料</a></li>";
			echo "<li class='function'><a href='network.php?process=history'>歷史資料</a></li>";
			echo "<li class='function'><a href='network.php?logout=true'>登出</a></li>";
			echo '</div>
				  </td>
				  </tr>
				  </table>
				  </td>
				  </tr>';
        }				
		else
		{
			$all_list = ("SELECT * FROM Block_IP_List order by block_time desc");
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
			
			
			
			echo "<form align='center' action='network.php'  method='post'>";
			echo "<p>";
			echo "<strong>IP位址搜尋: </strong>";
			echo "<input type='text' name='ip'>";
			echo "<input type='submit'  value='搜尋' name='ip_search'>";
			echo "&nbsp";
			echo "<strong>系所單位搜尋: </strong>";
			echo "<select name='department'>";
					$result = mysql_query('SELECT * FROM Account_Management');
					while ($row = mysql_fetch_assoc($result))
					{
						echo "<option value=".$row['department'].">".$row['department']."</option>";  
					} 
      				
		    echo "/select>";
			echo "<input type='submit' value='搜尋' name='department_search'>";
			echo "</form>";
			echo "</p>";			
			
			if( isset($_GET["action"]) && ($_GET["action"]=="show_info") )
			{
				$sn = $_GET['id'];
				$identity_check = "select * from Block_IP_List where sn ='$sn'";
				$identity_check_query = mysql_query($identity_check);
				$row = mysql_fetch_assoc($identity_check_query);
				echo "<p align='center'><strong>".$row['department']."</strong>負責網管為：<strong>".$row['name'].$row['title']."</strong>聯絡方式為：".$row['tel']."</p>";
			}
			echo "<hr size='1'/>";		
			if( (isset($_POST['ip_search'])) || (isset($_GET['search_ip'])))
			{
				$ip = $_POST['ip'];	
				if( isset($_GET['search_ip']) !="")
				{
					$ip = $_GET['search_ip'];
					$identity_check = "select * from Block_IP_List where ip ='$ip'";
					$identity_check_query = mysql_query($identity_check);
					$row = mysql_fetch_assoc($identity_check_query);
					echo "<p align='center'><strong>".$ip."</strong>負責網管為：<strong>".$row['name'].$row['title']."</strong>聯絡方式為：".$row['tel']."</p>";
				}
				$sql = "select * from Block_IP_List where ip ='$ip'"; 
				$result = mysql_query($sql);
				$row = mysql_fetch_assoc($result);
				if (mysql_num_rows($result) == 0 )
				{
					echo "<p align='center'>"; 
					echo "這個IP沒有被鎖喔！！";
					echo "</p>";
				}
				else
				{
					echo '<table width="100%" border="0" class="mytable">
						  <tr>
						  <th height="30" bgcolor="#0E4655" width="8%"><div align="center">功能</div></th>
						  <th height="30" bgcolor="#0E4655" width="10%"><div align="center">阻斷時間</div></th>
						  <th height="30" bgcolor="#0E4655" width="12%"><div align="center">IP位址</div></th>
						  <th height="30" bgcolor="#0E4655" width="14%"><div align="center">系所單位</div></th>
						  <th height="30" bgcolor="#0E4655" width="8%"><div align="center">阻斷原因</div></th>
						  <th height="30" bgcolor="#0E4655" width="8%"><div align="center">目前狀態</div></th>
						  <th height="30" bgcolor="#0E4655" width="10%"><div align="center">處理時間</div></th>
						  <th height="30" bgcolor="#0E4655" width="30%"><div align="center">備註</div></th>
						  </tr>';
					echo "<tr>";				
					echo "<input name='sn' type='hidden' value=".$row['sn'].">";
					echo "<td>";
					
					echo "放啥好";
					
					echo "</td>";
					echo "<td height='30'><div align='center'>".$row['block_time']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['ip']."</div></td>";
					echo "<td height='30'><a href='network.php?search_ip=".$row['ip']."'><div align='center'>".$row['department']."</div></a></td>";
					echo "<td height='30'><div align='center'>".$row['reason']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['process']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['process_time']."</div></td>";
					echo "<td height='30'><div align='left'>".$row['note']."</div></td>";
					echo "</tr>";
					echo "</table>";
				}
			}
			
			elseif( isset($_POST['department_search']) || isset($_GET['search'])) 
			{
				if (isset($_GET['page']))
				{
					$num_pages = $_GET['page'];
				}	
				
				if (isset($_POST['department_search']))				
				{
					$department = $_POST['department'];	
				}
				if (isset($_GET['search']))				
				{
					$department = $_GET['search'];	
				}
				
				$identity_check = "select * from Account_Management where department ='$department'";
				$identity_check_query = mysql_query($identity_check);
				$identity_check_row = mysql_fetch_assoc($identity_check_query);
				echo "<p align='center'><strong>".$identity_check_row['department']."</strong>負責網管為：<strong>".$identity_check_row['name'].$identity_check_row['title']."</strong>聯絡方式為：".$identity_check_row['tel']."</p>";
				
				$sql = "select * from Block_IP_List where department ='$department'"; 
				$result = mysql_query($sql);
				$query_limit_records = $sql." LIMIT ".$startRow_records.", ".$pageRow_records; 
				$limit_records = mysql_query($query_limit_records);
				$total_records = mysql_num_rows($result);
				
				if (mysql_num_rows($result) == 0 )
				{
					echo "<p align='center'>"; 
					echo $department;
					echo "沒有任何IP被鎖喔！！";
					echo "</p>";
				}
				
				else
				{
					echo '<table width="100%" border="0" class="mytable">
						  <tr>
						  <th height="30" bgcolor="#0E4655" width="8%"><div align="center">功能</div></th>
						  <th height="30" bgcolor="#0E4655" width="10%"><div align="center">阻斷時間</div></th>
						  <th height="30" bgcolor="#0E4655" width="12%"><div align="center">IP位址</div></th>
						  <th height="30" bgcolor="#0E4655" width="14%"><div align="center">系所單位</div></th>
						  <th height="30" bgcolor="#0E4655" width="8%"><div align="center">阻斷原因</div></th>
						  <th height="30" bgcolor="#0E4655" width="8%"><div align="center">目前狀態</div></th>
						  <th height="30" bgcolor="#0E4655" width="10%"><div align="center">處理時間</div></th>
						  <th height="30" bgcolor="#0E4655" width="30%"><div align="center">備註</div></th>
						  </tr>';
					
					while($row = mysql_fetch_assoc($limit_records))
					{
						echo "<tr>";				
					    echo "<input name='sn' type='hidden' value=".$row['sn'].">";
						echo "<td>";
						echo "放啥好";
						echo "</td>";
						echo "<td height='30'><div align='center'>".$row['block_time']."</div></td>";
						echo "<td height='30'><div align='center'>".$row['ip']."</div></td>";
						echo "<td height='30'><div align='center'>".$row['department']."</div></td>";
						echo "<td height='30'><div align='center'>".$row['reason']."</div></td>";
						echo "<td height='30'><div align='center'>".$row['process']."</div></td>";
						echo "<td height='30'><div align='center'>".$row['process_time']."</div></td>";
						echo "<td height='30'><div align='left'>".$row['note']."</div></td>";
						echo "</tr>";
					}
						echo "</table>";
						echo '<hr size="1"/>
							  <table width="98%" border="0" align="center" cellpadding="4" cellspacing="0">
							  <tr>';
						echo "<td valign='middle'><p>資料筆數：$total_records </p></td>";
						echo "<td align='right'><p>";
						$num1= $num_pages-1;
						$num2= $num_pages+1;
						if ($num_pages > 1) 
						{   
							echo "<a href='network.php?page=1&search=".$department."'>第一頁</a>";
							echo "|";
							echo "<a href='network.php?page=".$num1."&search=".$department."'>上一頁</a>";
							echo "|"; 
						}
						if ( $num_pages < $total_pages ) 
						{
							echo "<a href='network.php?page=".$num2."&search=".$department."'>下一頁</a>";
							echo "|";
							echo "<a href='network.php?page=".$total_pages."&search=".$department."'>最末頁</a>";
						}
				
						echo '</p></td>
							  </tr>
						      </table><p>&nbsp;</p>';
				}
			}
			else
			{
				echo '<table width="100%" border="0" class="mytable">
			 		  <tr>
					  <th height="30" bgcolor="#0E4655" width="8%"><div align="center">功能</div></th>
			          <th height="30" bgcolor="#0E4655" width="10%"><div align="center">阻斷時間</div></th>
					  <th height="30" bgcolor="#0E4655" width="12%"><div align="center">IP位址</div></th>
					  <th height="30" bgcolor="#0E4655" width="14%"><div align="center">系所單位</div></th>
					  <th height="30" bgcolor="#0E4655" width="8%"><div align="center">阻斷原因</div></th>
					  <th height="30" bgcolor="#0E4655" width="8%"><div align="center">目前狀態</div></th>
					  <th height="30" bgcolor="#0E4655" width="10%"><div align="center">處理時間</div></th>
					  <th height="30" bgcolor="#0E4655" width="30%"><div align="center">備註</div></th>
					  </tr>';
			while ($row = mysql_fetch_assoc($limit_records))
			{
		    	echo "<tr>";				
				echo  "<input name='sn' type='hidden' value=".$row['sn'].">";
				echo "<td>";
				
				
				
					echo "放啥好";
				
					echo "</td>";
					echo "<td height='30'><div align='center'>".$row['block_time']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['ip']."</div></td>";
					
					echo "<td height='30'><a href='network.php?page=".$num_pages."&action=show_info&id=".$row['sn']."'><div align='center'>".$row['department']."</div></a></td>";
					echo "<td height='30'><div align='center'>".$row['reason']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['process']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['process_time']."</div></td>";
					echo "<td height='30'><div align='left'>".$row['note']."</div></td>";
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
				echo "<a href='network.php?page=1'>第一頁</a>";
				echo "|";
				echo "<a href='network.php?page=".$num1."'>上一頁</a>";
				echo "|"; 
			}
			if ( $num_pages < $total_pages ) 
			{
				echo "<a href='network.php?page=".$num2."'>下一頁</a>";
				echo "|";
				echo "<a href='network.php?page=".$total_pages."'>最末頁</a>";
			}
				
				echo'</p></td>
					 </tr>
				     </table><p>&nbsp;</p>';
			}					
			echo "<td width='200'>";
			echo "<div class='regbox'>";
			echo "<p class='heading'>用戶資料</p>";
			echo "<p><strong>";
			echo $account;
			echo ",您好！";	
			echo "</strong>";
			echo "<br>";
			echo "目前所在的頁面：";
			echo "<br>";
			echo "<font color='FF9933'>所有阻斷資料</font>";
			echo "</p>";
			echo "<hr size='1'/>";
			echo "<p class='heading'>功能選單</p>";
			echo "<li class='function'><a href='add_list_network.php'>IP資料清單</a></li>";
			echo "<li class='function'><a href='network.php'>阻斷資料清單</a></li>";
			echo "<li class='function'><a href='network.php?process=not_process'>待處理資料</a></li>";
			echo "<li class='function'><a href='network.php?process=complete'>待開通資料</a></li>";
			echo "<li class='function'><a href='network.php?process=history'>歷史資料</a></li>";
			echo "<li class='function'><a href='network.php?logout=true'>登出</a></li>";
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
