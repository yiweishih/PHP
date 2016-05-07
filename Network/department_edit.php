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
	
	
	$account = $_SESSION['account'];
	
	$account_query = "SELECT * FROM  Account_Management  WHERE  sn =".$_GET["id"];  
	$account_query_result = mysql_query($account_query);
	$account_query_row = mysql_fetch_assoc($account_query_result);

if(isset($_POST["action"])&&($_POST["action"]=="edit"))
{
	//找尋帳號是否已經註冊
	/*
	$query_RecFindUser = "SELECT `account` FROM `Account_Management` WHERE `account`='".$_POST["account"]."'";
	$RecFindUser=mysql_query($query_RecFindUser);
	$query_RecFind_department = "SELECT `department` FROM `Account_Management` WHERE `department`='".$_POST["department"]."'";
	$RecFind_department=mysql_query($query_RecFind_department);
	
	if (mysql_num_rows($RecFindUser)>0){
		header("Location: department_edit.php?errMsg=1&username=".$_POST["account"]);
	}
	
	
	
	elseif (mysql_num_rows($RecFind_department)>0){
		header("Location: department_edit.php?errMsg=2&department=".$_POST["department"]);
	}
	*/
	
	
	
	
	//若沒有執行新增的動作	
	
		$old_department = $account_query_row['department'];
		
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
		$sn = $_POST['sn'];
	
		
	$query = "update Account_Management set account='$account',password='$password',level='$level',department='$department',name='$name',title='$title', tel='$tel', email='$email',note='$note' where sn ='$sn'";                  
	$query2 = "update Department_Allocate_IP set department='$department',name='$name',title='$title', tel='$tel', email='$email' where department ='$old_department'";
	$query3 = "update Block_IP_List set department='$department',name='$name',title='$title', tel='$tel', email='$email' where department ='$old_department'";
	mysql_query($query) or die('Insert data fail' . mysql_error());	
	mysql_query($query2) or die('Insert data fail' . mysql_error());
    mysql_query($query3) or die('Insert data fail' . mysql_error());		
	header("Location: department_edit.php?loginStats=1");
		
	
}
	
	


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>中興大學校園網路管理系統</title>
<link href="style.css" rel="stylesheet" type="text/css">
<script language="javascript">
function checkForm(){
	if(document.formJoin.account.value==""){		
		alert("請填寫帳號!");
		document.formJoin.account.focus();
		return false;
	}else{
		uid=document.formJoin.account.value;
		if(uid.length<5 || uid.length>20){
			alert( "您的帳號長度只能5至20個字元!" );
			document.formJoin.account.focus();
			return false;
		}
		/*
		if(!(uid.charAt(0)>='a' && uid.charAt(0)<='z')){
			alert("您的帳號第一字元只能為小寫字母!" );
			document.formJoin.account.focus();
			return false;
		}
		for(idx=0;idx<uid.length;idx++){
			
			if(uid.charAt(idx)>='A'&&uid.charAt(idx)<='Z'){
				alert("帳號不可以含有大寫字元!" );
				document.formJoin.account.focus();
				return false;
			}
			
			if(!(( uid.charAt(idx)>='a'&&uid.charAt(idx)<='z')||(uid.charAt(idx)>='0'&& uid.charAt(idx)<='9')||( uid.charAt(idx)=='_'))){
				alert( "您的帳號只能是數字,英文字母及「_」等符號,其他的符號都不能使用!" );
				document.formJoin.account.focus();
				return false;
			}
			
			if(uid.charAt(idx)=='_'&&uid.charAt(idx-1)=='_'){
				alert( "「_」符號不可相連 !\n" );
				document.formJoin.account.focus();
				return false;				
			}
		}
	   */
	}
	if(!check_passwd(document.formJoin.password.value,document.formJoin.passwdrecheck.value)){
		document.formJoin.password.focus();
		return false;
	}	
	if(document.formJoin.name.value==""){
		alert("請填寫姓名!");
		document.formJoin.name.focus();
		return false;
	}
	if(document.formJoin.title.value==""){
		alert("請填寫職稱!");
		document.formJoin.title.focus();
		return false;
	}
	if(document.formJoin.email.value==""){
		alert("請填寫電子郵件!");
		document.formJoin.email.focus();
		return false;
	}
	if(!checkmail(document.formJoin.email)){
		document.formJoin.email.focus();
		return false;
	}
	return confirm('確定送出嗎？');
}
function check_passwd(pw1,pw2){
	if(pw1==''){
		alert("密碼不可以空白!");
		return false;
	}
	for(var idx=0;idx<pw1.length;idx++){
		if(pw1.charAt(idx) == ' ' || pw1.charAt(idx) == '\"'){
			alert("密碼不可以含有空白或雙引號 !\n");
			return false;
		}
		if(pw1.length<5 || pw1.length>10){
			alert( "密碼長度只能5到10個字母 !\n" );
			return false;
		}
		if(pw1!= pw2){
			alert("密碼二次輸入不一樣,請重新輸入 !\n");
			return false;
		}
	}
	return true;
}
function checkmail(myEmail) {
	var filter  = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(filter.test(myEmail.value)){
		return true;
	}
	alert("電子郵件格式不正確");
	return false;
}
</script>
</head>

<body>
<?php if(isset($_GET["loginStats"]) && ($_GET["loginStats"]=="1")){?>
<script language="javascript">
alert('會員編輯成功。');
window.location.href='department_account.php';		  
</script>
<?php }?>
<table width="1200" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr>
    <td class="tdbline"><img src="images/logo.jpg" alt="會員系統" width="551" height="66"></td>
  </tr>
  <tr>
    <td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="10">
      <tr valign="top">
        <td class="tdrline"><form action="" method="POST" name="formJoin" id="formJoin" onSubmit="return checkForm();">
          <p class="title">新增會員</p>

          <div class="dataDiv">
            <hr size="1" />
            <p class="heading">帳號資料</p>
            <p><strong>使用帳號</strong>：
                <input name="account" type="text" class="normalinput" id="account" value="<?php echo $account_query_row['account'];?>" readonly>
                <br>
                <span class="smalltext">請填入5~12個字元以內的小寫英文字母、數字、以及_ 符號。</span></p>
            <p><strong>使用密碼</strong>：
                <input name="password" type="password" class="normalinput" id="password" value="<?php echo $account_query_row['password'];?>">
               <br>
                <span class="smalltext">請填入5~10個字元以內的英文字母、數字、以及各種符號組合，</span></p>
            <p><strong>確認密碼</strong>：
                <input name="passwdrecheck" type="password" class="normalinput" id="passwdrecheck" value="<?php echo $account_query_row['password'];?>">
                <br>
                <span class="smalltext">再輸入一次密碼</span></p>
            <hr size="1" />
            <p class="heading">個人資料</p>
            <p><strong>姓　　名</strong>：
               <input name="name" type="text" class="normalinput" id="name" value="<?php echo $account_query_row['name']; ?>">
                 </p>
            <p><strong>權　　限</strong>：
               <select name="level">
			<option <?php   if($account_query_row['level']=="adm")
						    {
							    echo "selected";
							}
					?> value="adm">管理者</option>
            <option <?php   if($account_query_row['level']=="network")
						    {
							    echo "selected";
							}
					?> value="network">網路組</option>
  		    <option <?php   if($account_query_row['level']=="member")
						    {
							    echo "selected";
							}
					?> value="member">單位網管</option>
					</select> 
					</p>
					
            <p><strong>職　　稱</strong>：
               <select name="title">
 		    <option <?php   if($account_query_row['title']=="老師")
						    {
							    echo "selected";
							}
					?> value="老師">老師</option>
            <option <?php   if($account_query_row['title']=="助教")
						    {
							    echo "selected";
							}
					?>
			value="助教">助教</option>
  		    <option <?php   if($account_query_row['title']=="同學")
						    {
							    echo "selected";
							}
					?>
			value="同學">同學</option>
  			<option <?php   if($account_query_row['title']=="先生")
						    {
							    echo "selected";
							}
					?>  value="先生">先生</option>
			<option <?php   if($account_query_row['title']=="技術師")
						    {
							    echo "selected";
							}
					?>  value="技術師">技術師</option>	
			<option <?php   if($account_query_row['title']=="技士")
						    {
							    echo "selected";
							}
					?>  value="技士">技士</option>
			<option <?php   if($account_query_row['title']=="助理")
						    {
							    echo "selected";
							}
					?>  value="助理">助理</option>				
			<option <?php   if($account_query_row['title']=="小姐")
						    {
							    echo "selected";
							}
					?>  value="小姐">小姐</option>
  			</select>  
               </p>
			   <p><strong>系所單位</strong>：
                <input name="department" type="text" class="normalinput" id="department" value="<?php echo $account_query_row['department'];?>" readonly>
              </p> 
			   
            <p><strong>電子郵件</strong>：
                <input name="email" type="text" class="normalinput" id="email" value="<?php echo $account_query_row['email'];?>">
              </p> 
         
           
            <p><strong>連略方式</strong>：
               <input name="tel" type="text" class="normalinput" id="tel" value="<?php echo $account_query_row['tel'];?>">
				
			</p>
			
			
            
			
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="73" valign="top"><p> <strong>備　　註：</strong> </p></td>
                <td valign="top"><textarea name="note" rows="5" cols="50"><?php echo $account_query_row['note'];?></textarea></td>
              </tr>
            </table>
          </div>
          <hr size="1" />
          <p align="center">
            <input name="action" type="hidden" id="action" value="edit">
			<input name="sn" type="hidden" value="<?php echo $account_query_row['sn'];?>">
            <input type="submit" name="Submit2" value="編輯資料">
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
