<?php
    session_start();
    if(!isset($_SESSION["account"]) || ($_SESSION["account"]==""))
	{
        header("Location: login.php");
    }
	if(!isset($_SESSION["level"]) || ($_SESSION["level"]!="adm"))
	{
        header("Location: login.php");
    }
    if(isset($_GET["logout"]) && ($_GET["logout"]=="true"))
	{
	    session_unset();
	    header("Location: login.php");
	}
    
	include("mysql_connect.php");
	if(isset($_GET["action"]) && ($_GET["action"]=="del"))	
	{  
	    $query = "delete  from Account_Management where sn = ".$_GET['id'];
        mysql_query($query) or die('DELETE fail' . mysql_error());
	    header("Location: department_account.php");
		die;		
	}
	
	$account = $_SESSION['account'];
	
	
/*
if(isset($_POST["action"])&&($_POST["action"]=="add"))
{
	//找尋帳號是否已經註冊
	$query_RecFindUser = "SELECT `account` FROM `Account_Management` WHERE `account`='".$_POST["account"]."'";
	$RecFindUser=mysql_query($query_RecFindUser);
	$query_RecFind_department = "SELECT `department` FROM `Account_Management` WHERE `department`='".$_POST["department"]."'";
	$RecFind_department=mysql_query($query_RecFind_department);
	
	if (mysql_num_rows($RecFindUser)>0){
		header("Location: department_add.php?errMsg=1&username=".$_POST["account"]);
	}
	
	
	
	elseif (mysql_num_rows($RecFind_department)>0){
		header("Location: department_add.php?errMsg=2&department=".$_POST["department"]);
	}
	
	
	
	
	else{
	//若沒有執行新增的動作	
		$account = $_POST['account'];
		$password = $_POST['password'];
		$level = $_POST['level'];
		$department = $_POST['department'];
		$name = $_POST['name'];   
		$title = $_POST['title'];
		$tel = $_POST['tel'];
		$email = $_POST['email'];
		$location = $_POST['location'];
		$note = $_POST['note'];
	
		
	$query = "insert into Account_Management (account,password,level,department,name,title,tel,email,note)
					  values('$account','$password','$level','$department','$name','$title','$tel','$email','$note')";                  
			mysql_query($query) or die('Insert data fail' . mysql_error());	
			header("Location: department_add.php?loginStats=1");
		
	}
	*/

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>中興大學校園網路管理系統</title>
<link href="style.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
function del_sure()
{
    if (confirm('\n您確定要刪除這個帳號嗎?')) 
	return true;
    return false;
}
</script>
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
	        <th height="30" bgcolor="#0E4655" width="70"><div align="center">功能</div></th>
			<th height="30" bgcolor="#0E4655" width="100"><div align="center">帳號</div></th>
			<th height="30" bgcolor="#0E4655" width="100"><div align="center">姓名</div></th>
			<th height="30" bgcolor="#0E4655" width="70"><div align="center">職稱</div></th>
			<th height="30" bgcolor="#0E4655" width="160"><div align="center">系所單位</div></th>
			<th height="30" bgcolor="#0E4655" width="150"><div align="center">聯絡方式</div></th>
			<th height="30" bgcolor="#0E4655" width="150"><div align="center">E-Mail</div></th>
			<th height="30" bgcolor="#0E4655" width="200"><div align="center">備註</div></th>
		</tr>
		
<?php
    
	$all_account_list = ("SELECT * FROM Account_Management");
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
		    while ($row = mysql_fetch_assoc($limit_records))
			{
		    	echo "<tr>";				
				echo  "<input name='sn' type='hidden' value=".$row['sn'].">";
				echo "<td>";
				
					echo  "<a href='department_account.php?action=del&id=".$row['sn']."' onClick='return del_sure();'><div align='center'>刪除</div></a>";
				
					echo  "<a href='department_edit.php?id=".$row['sn']."'><div align='center'>編輯</div></a>";
					
					
					echo "</td>";
					echo "<td height='30'><div align='center'>".$row['account']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['name']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['title']."</div></td>";					
					echo "<td height='30'><div align='center'>".$row['department']."</div></td>";					
					echo "<td height='30'><div align='center'>".$row['tel']."</div></td>";					
					echo "<td height='30'><div align='center'>".$row['email']."</div></td>";
					echo "<td height='30'><div align='center'>".$row['note']."</div></td>";
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
				echo "<a href='department_account.php?page=1'>第一頁</a>";
				echo "|";
				echo "<a href='department_account.php?page=".$num1."'>上一頁</a>";
				echo "|"; 
			}
			if ( $num_pages < $total_pages ) 
			{
				echo "<a href='department_account.php?page=".$num2."'>下一頁</a>";
				echo "|";
				echo "<a href='department_account.php?page=".$total_pages."'>最末頁</a>";
			}
				echo'</p></td>
					 </tr>
				</table><p>&nbsp;</p>';
					 
					 
					 

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
			<font color='FF9933'>帳號資料清單</font>
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
