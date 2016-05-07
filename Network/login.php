<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>中興大學校園網路管理系統</title>
<style type="text/css">
<!--

p,ol {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 10pt;
	line-height: 150%;
	margin-top: 0px;
	margin-bottom: 5px;
}
form {
	margin: 0px;
}
a {
	text-decoration: none;
}
a:link {
	color: #0066CC;
}
a:visited {
	color: #0066CC;
}
a:hover {
	color: #FF0000;
}


.title {
	font-family: "微軟正黑體";
	font-size: 24pt;
	font-weight: bolder;
	color: #FF3300;
}
.heading {
	font-family: "微軟正黑體";
	font-size: 13pt;
	color: #0066CC;
	line-height: 150%;
	font-weight: bold;
}
.smalltext {
	font-size: 11px;
	color: #999999;
	font-family: Georgia, "Times New Roman", Times, serif;
	vertical-align: middle;
}
.trademark {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 8pt;
	color: #0099FF;
}
.regbox {
	border: 1px solid #656565;
	padding: 15px;
	background-color: #F0F0F0;
}
.boxtl {
	background-image: url(images/tl.gif);
	float: left;
	top: -1px;
	height: 4px;
	width: 4px;
	background-repeat: no-repeat;
}
.boxtr {
	background-image: url(images/tr.gif);
	float: right;
	top: -1px;
	height: 4px;
	width: 4px;
	background-repeat: no-repeat;
}
.boxbl {
	background-image: url(images/bl.gif);
	float: left;
	top: -1px;
	height: 4px;
	width: 4px;
	background-repeat: no-repeat;
	margin-top: -4px;
}
.boxbr {
	background-image: url(images/br.gif);
	float: right;
	top: -1px;
	height: 4px;
	width: 4px;
	background-repeat: no-repeat;
	margin-top: -4px;
}
.tdrline {
	border-right-width: 1px;
	border-right-style: dotted;
	border-right-color: #999999;
}
.tdbline {
	border-bottom-width: 1px;
	border-bottom-style: dotted;
	border-bottom-color: #999999;
}
.clear {
	clear: both;
}
.logintextbox {
	width: 150px;
}
.errDiv {
	font-family: "微軟正黑體";
	font-size: 10pt;
	color: #FFFFFF;
	background-color: #FF0000;
	padding: 4px;
	text-align: center;
}
-->
</style>
<script type="text/javascript">
/*
 * 刷新驗證碼
 */
function reloadcode(){
var d = new Date();
document.getElementById('safecode2').src="imgcode.php?t="+d.toTimeString()
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
        <td class="tdrline"><p class="title">中興大學校園網路管理系統</p>
          <p>感謝各位網管來到本系統， 所有的功能都必須經由登入後才能使用，請您在右方視窗中執行登入動作。</p>
          <p class="heading"> 本系統擁有以下的功能：</p>
          <ol>
            <li>各系所單位負責網管查詢。</li>
            <li>各系所單位IP區段查詢。</li>
            <li>各系所單位阻斷IP查詢。</li>
            <li>各系所單位阻斷IP申請解鎖。</li>
            <li>各系所單位阻斷IP歷史紀錄。</li>
          </ol>
          <p class="heading">中興大學校園網路規範： </p>
          <ol>
            <li> 每日上傳與下載流量分別不得超過2GB。</li>
            <li> 若阻斷原因為異常網路行為(攻擊、流量異常)，請重灌電腦。</li>
            <li> 本系統帳號申請以系所為單位，每一單位僅能申請一組帳號</li>
            <li> 各系所單位帳號僅供網管使用 </li>
            <li> 本系統尚未開發及測試完成...。</li>
          </ol></td>
        <td width="200">
        
        <div class="regbox">
          <p class="heading">登入會員系統</p>
          <form name="form1" method="post" action="<?=$_SERVER['PHP_SELF']?>" >
              <p>帳號:<br>
                  <input name="account" type="text" class="logintextbox" id="account" size="10">
              </p>
              <p>密碼：<br>
                  <input name="password" type="password" class="logintextbox" id="password" >
              </p> 
              <p>驗證碼：<br>
                  <input name="safecode" type="text" id="safecode" size="10" maxlength="10" class="required"/>
				  <img id="safecode2" src="imgcode.php" onclick="reloadcode()" title="看不清楚嗎?請點此切換!" /> 
              </p>
             
              
              <br>
              <p align="center">
                  <input type="submit" name="button" id="button" value="登入系統">             
                  <input type="hidden" name="MM_insert" value="form1">
			  </p>
          </form>          
          </div>
             
       
        </td>
      </tr>
    </table></td>
  </tr>
  <tr>
    <td align="center" background="images/album_r2_c1.jpg" class="trademark">© 版權所有<a href='http://cc.nchu.edu.tw'>國立中興大學計算機及資訊網路中心</a></td>
</tr>
</table>
<?php
    
    if(isset($_POST['button']))
    {
        session_start();
	    if(($_SESSION['randcode'] == $_POST['safecode']) && (!empty($_SESSION['randcode']))) 
		{
			if(isset($_POST['account']) && isset($_POST['password']))
			{
				include("mysql_connect.php");
				$account = $_POST['account'];
				$password = $_POST['password'];
				$query = "select * from Account_Management where account = '$account'"; 
				$result = mysql_query($query);
				$row = mysql_fetch_assoc($result);
				if($account == $row['account'] && $password == $row['password'])
				{
					$_SESSION['account'] = $account;
					echo "<script>alert('登入成功！！')</script>";                
					if($row['level'] == member )
					{
						$_SESSION['level'] = 'member';
						$_SESSION['name']  = $row['name'];
						$_SESSION['department']  = $row['department'];
						echo '<meta http-equiv=REFRESH CONTENT=1;url=member.php>';
					}
					elseif($row['level'] == network )
					{
						$_SESSION['level'] = 'network';
						$_SESSION['account']  = $row['account'];
						echo '<meta http-equiv=REFRESH CONTENT=1;url=network.php>';
					}
					else
					{
						$_SESSION['level'] = 'adm';
						$_SESSION['account']  = $row['account'];
						echo '<meta http-equiv=REFRESH CONTENT=1;url=adm.php>';
					}			
				}
            		  
				else
				{
					$_SESSION['count']++;
					if($_SESSION['count']>4)
					{
						echo '<script>alert("你已經沒有權限登入！");</script>';
						exit();
					}	        
				
					$num=5-$_SESSION['count'];
					$msg = '登入失敗！\n帳號或密碼錯誤！\n\n您還有'.$num.'次登入機會';
					echo '<script>alert("'.$msg.'");</script>';
				}
			}
		}
		else
		{
			echo "<script>alert('驗證碼錯誤！！')</script>";
			if(isset($_POST["MM_insert"]))
			{
				unset($_POST["MM_insert"]);
			}
			if(isset($_POST["MM_update"]))
			{
				unset($_POST["MM_update"]);
			}
		}
           
    }
?>	
</body>
</html>
