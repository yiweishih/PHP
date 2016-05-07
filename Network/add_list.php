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
	
	
	$account =$_SESSION['account'];
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
        <td class="tdbline"><img src="images/top.jpg" alt="會員系統" width="1200" height="100"></td>
    </tr>
    <tr>
    <td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="10">
        <tr valign="top">
        <td class="tdrline">
		<table width="100%" border="0" class="mytable">
		<tr>
	        <th height="30" bgcolor="#0E4655" width="120"><div align="center">Start_ip</div></th>
			<th height="30" bgcolor="#0E4655" width="120"><div align="center">End_ip</div></th>
			<th height="30" bgcolor="#0E4655" width="160"><div align="center">系所單位</div></th>
			<th height="30" bgcolor="#0E4655" width="100"><div align="center">網管</div></th>
			<th height="30" bgcolor="#0E4655" width="100"><div align="center">職稱</div></th>
			<th height="30" bgcolor="#0E4655" width="100"><div align="center">聯絡方式</div></th>
			<th height="30" bgcolor="#0E4655" width="150"><div align="center">E-Mail</div></th>
			
		</tr>  
		<?php
    
	$all_account_list = ("SELECT * FROM Department_Allocate_IP");
	$all_account_list_result = mysql_query($all_account_list);
	
    
    $pageRow_records = 10;
			$num_pages = 1;				
			if (isset($_GET['page']))
			{
				$num_pages = $_GET['page'];
			}			
			$startRow_records = ($num_pages -1) * $pageRow_records;
			$query_limit_records = $all_account_list." LIMIT ".$startRow_records.", ".$pageRow_records; 
			$limit_records = mysql_query($query_limit_records);
			$total_records = mysql_num_rows($all_account_list_result);
			$total_pages = ceil( $total_records/$pageRow_records );
			
			echo "<p align='center'>";
			echo "<form align='center' action='add_list.php'  method='post'>";
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
			echo "<hr size='1'/>";
			
			if( isset($_POST['ip_search']) )
			{
			    $ip = $_POST['ip'];	
				$sql = "select * from Department_Allocate_IP where inet_aton('$ip') between inet_aton(start_ip) and inet_aton(end_ip)"; 
				$result = mysql_query($sql);
				$row = mysql_fetch_assoc($result);
				
				if (mysql_num_rows($result) == 0 )
				{
					echo "<p align='center'>"; 
					echo "此ip不屬於任何ip區段";
					echo "</p>";
				}
				else
				{				    
					echo "<tr>";			
					echo "<td height='30'><div align='center'>".$row['start_ip']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['end_ip']."</div></td>";										
					echo "<td height='30'><div align='center'>".$row['department']."</div></td>";	
					echo "<td height='30'><div align='center'>".$row['name']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['title']."</div></td>";					
					echo "<td height='30'><div align='center'>".$row['tel']."</div></td>";					
					echo "<td height='30'><div align='center'>".$row['email']."</div></td>";
			    	echo "</tr>";
				}
					echo "</table>";
			}
			elseif(isset($_POST['department']))
			{
				$department= $_POST['department'];
				$sql = "select * from Department_Allocate_IP where department='$department'"; 
				$result = mysql_query($sql);
				while($row=mysql_fetch_assoc($result))
				{
				echo "<tr>";			
					echo "<td height='30'><div align='center'>".$row['start_ip']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['end_ip']."</div></td>";										
					echo "<td height='30'><div align='center'>".$row['department']."</div></td>";	
					echo "<td height='30'><div align='center'>".$row['name']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['title']."</div></td>";					
					echo "<td height='30'><div align='center'>".$row['tel']."</div></td>";					
					echo "<td height='30'><div align='center'>".$row['email']."</div></td>";
			    	echo "</tr>";
				
				}
				echo "</table>";
			}
			else
			{
				while ($row = mysql_fetch_assoc($limit_records))
			{
		    	echo "<tr>";				
				
					echo "<td height='30'><div align='center'>".$row['start_ip']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['end_ip']."</div></td>";										
					echo "<td height='30'><div align='center'>".$row['department']."</div></td>";	
					echo "<td height='30'><div align='center'>".$row['name']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['title']."</div></td>";					
					echo "<td height='30'><div align='center'>".$row['tel']."</div></td>";					
					echo "<td height='30'><div align='center'>".$row['email']."</div></td>";
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
				echo "<a href='add_list.php?page=1'>第一頁</a>";
				echo "|";
				echo "<a href='add_list.php?page=".$num1."'>上一頁</a>";
				echo "|"; 
			}
			if ( $num_pages < $total_pages ) 
			{
				echo "<a href='add_list.php?page=".$num2."'>下一頁</a>";
				echo "|";
				echo "<a href='add_list.php?page=".$total_pages."'>最末頁</a>";
			}
				

			echo'</p></td>
					 </tr>
				</table><p>&nbsp;</p>';
			}		 
					 
					 

?>
		
		
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
        <td width="200">
        <div class="boxtl"></div><div class="boxtr"></div>
<div class="regbox">
            <p class="heading"><strong>用戶資料：</strong></p>
		  
		  
			<p>
			<strong><?php echo  $account; ?>,您好！</strong>
			<br>	
			目前所在的頁面：
			<br>
			<font color='FF9933'>IP資料清單</font>
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
