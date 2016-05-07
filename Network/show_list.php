<?php
    include("mysql_connect.php");
	$result = mysql_query('SELECT * FROM Block_IP_List');
	if (!$result)
	{
        echo '執行SQL資料表查詢失敗:'.mysql_error();
    	exit;
    }
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>中興大學校園網路管理系統</title>
<link href="style.css" rel="stylesheet" type="text/css" />
</head>
<body>
    <table width="1200" border="0" align="center" cellpadding="4" cellspacing="0">
        <tr> 
		    <td class="tdbline"><img src="images/top.jpg"  width="1200" height="100"></td>
        </tr>
        <tr>
		    <td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="10">
        <tr valign="top">
		    <td >
<?php     
    $all_list = ("SELECT * FROM Block_IP_List order by block_time desc");
	$all_list_result = mysql_query($all_list);		
	$pageRow_records = 20;
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
	echo "<form align='center' action='show_list.php'  method='post'>";
	echo "<p>";
	echo "<strong>IP位址搜尋: </strong>";
	echo "140.120.";
	echo "<input type='text' size='1 maxlength='3 name='ip3'>";
	echo ".";
	echo "<input type='text' size='1 maxlength='3 name='ip4'>";
	echo "<input type='submit'  value='搜尋' name='ip_search'>";
	echo "&nbsp";
	echo "&nbsp";
	echo "&nbsp";
	echo "<input type='submit'  value='顯示所有資料'>";	
	echo "</form>";
	echo "</p>";			
			
    if( isset($_GET["action"]) && ($_GET["action"]=="show_info") )
	{
		$sn = $_GET['id'];
		$identity_check = "select * from Block_IP_List where sn ='$sn'";
		$identity_check_query = mysql_query($identity_check);
		$row = mysql_fetch_assoc($identity_check_query);
		echo "<p align='center'><strong>".$row['department']."</strong>&nbsp負責網管為：&nbsp<strong>".$row['name'].$row['title']."&nbsp</strong>聯絡方式為：&nbsp".$row['tel']."</p>";
	}
	
	echo "<hr size='1'/>";		
	if( (isset($_POST['ip_search'])) || (isset($_GET['search_ip'])))
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
			echo '<table width="100%" border="1" class="mytable">
			
				  <tr>
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
			echo "<td height='30'><div align='center'><p>".$row['block_time']."</p></div></td>";
			echo "<td height='30'><div align='center'>".$row['ip']."</div></td>";
			echo "<td height='30'><a href='show_list.php?search_ip=".$row['ip']."'><div align='center'>".$row['department']."</div></a></td>";
			echo "<td height='30'><div align='center'>".$row['reason']."</div></td>";
			echo "<td height='30'><div align='center'>".$row['process']."</div></td>";
			echo "<td height='30'><div align='center'>".$row['process_time']."</div></td>";
			echo "<td height='30'><div align='left'>".$row['note']."</div></td>";
			echo "</tr>";
			echo "</table>";
		}
	}
	
	else
	{
		echo '<table width="100%" border="1" class="mytable">
		
	 		  <tr>					  
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
			echo "<td height='30'><div align='center'>".$row['block_time']."</div></td>";
			echo "<td height='30'><div align='center'>".$row['ip']."</div></td>";
			echo "<td height='30'><a href='show_list.php?page=".$num_pages."&action=show_info&id=".$row['sn']."'><div align='center'>".$row['department']."</div></a></td>";
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
			echo "<a href='show_list.php?page=1'>第一頁</a>";
			echo "|";
			echo "<a href='show_list.php?page=".$num1."'>上一頁</a>";
			echo "|"; 
		}
		if ( $num_pages < $total_pages ) 
		{
			echo "<a href='show_list.php?page=".$num2."'>下一頁</a>";
			echo "|";
			echo "<a href='show_list.php?page=".$total_pages."'>最末頁</a>";
		}

		echo'</p></td>
			 </tr>
		     </table>';
	}	
	echo '</tr>
		  </table>
		  </td>
		  </tr>';
?>		
<tr>
	<td align="center" background="images/album_r2_c1.jpg" class="trademark">© 版權所有 國立中興大學計算機及資訊網路中心</td>
</tr>
</table>
</body>
</html>
