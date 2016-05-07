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
	
	if(isset($_POST["action"]) && ($_POST["action"]=="add")) 	
	{
     	   	if( ($_POST['ip3']=="" ) || ($_POST['ip4']=="")) 
		     {                                                //真是笨阿~~直接用""就好了
	#if(is_null(($_POST['ip3']) || ($_POST['ip4']))){         //為什麼要is_null呢?? 因為如果用empty的話你輸入0他的結果是true，在這邊我需要輸入0
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
	/*
		if(empty($_POST['name'])){
		   echo '<p align="center"><a href="javascript:history.go(-1);">返回上一頁</a></p></br>';
		   echo '<font color="#FF0000"><h1 align="center">請輸入負責人</h1></font>';
		   die;  		 
	  }
	   	
		if(empty($_POST['tel'])){
		   echo '<meta http-equiv=REFRESH CONTENT=1;url=add.php>';
		   die("請輸校內分機");
	   }
		if(empty($_POST['mtel'])){
		   echo '<meta http-equiv=REFRESH CONTENT=1;url=add.php>';
		   die("請輸入手機號碼");
	   }elseif(!eregi("^(09)[0-9]{8}$",$_POST['mtel'])){
		   echo '<meta http-equiv=REFRESH CONTENT=1;url=add.php>';
		   die("您的手機號碼輸入不正確，請填寫正確的手機號碼");	    
	   }
		if(empty($_POST['email'])){
		   echo '<meta http-equiv=REFRESH CONTENT=1;url=add.php>';
		   die("請輸入E-mail");
	   }elseif(!eregi("^[_.0-9a-z-]+@([0-9a-z-]+.)+[a-z]{2,3}$",$_POST['email'])){
		   echo '<meta http-equiv=REFRESH CONTENT=1;url=add.php>';
		   die("您的E-mail格式有問題，請填寫正確E-mail格式");		    
	   }
	*/ 


		$a1 = $_POST['ip1'];
		$a2 = $_POST['ip2'];
		$a3 = $_POST['ip3'];
		$a4 = $_POST['ip4'];
		$a5 = $_POST['ip5'];
		$a6 = $_POST['ip6'];
		$a7 = $_POST['ip7'];
		$a8 = $_POST['ip8'];
		$a9 = $_POST['ip9'];
		$a10 = $_POST['ip10'];
		$a11 = $_POST['ip11'];
		$a12 = $_POST['ip12'];
		$b1 = $_POST['submask1'];
		$b2 = $_POST['submask2'];
		$b3 = $_POST['submask3'];
		$b4 = $_POST['submask4'];
		$c = $_POST['department'];
		$auto_insert = mysql_query("SELECT * FROM Account_Management where department = '$c' ");
		$auto_insert_result = mysql_fetch_assoc($auto_insert);
		$name = $auto_insert_result['name'];
		$tel = $auto_insert_result['tel'];
		$email = $auto_insert_result['email'];
		$title = $auto_insert_result['title'];
		$location = $auto_insert_result['location'];
		
		
		$i = $_POST['switch'];
		$j = $_POST['port_no'];
		$k = $_POST['link_switch'];
		$l = $_POST['note'];
		

	

	
	mysql_query("SET NAMES 'utf8'");	//這行很重要，不然MYSQL裡會顯示亂碼
	
	if($b3 !=255)
	{                      
	          $cal1 = ((int)$a3&(int)$b3);
	          $cal2 =  $cal1 + (255-$b3);
		      $query = "insert into Department_Allocate_IP (start_ip,end_ip,submask,department,name,tel,email,location,switch,port_no,port_ip,link_switch,link_ip,memo) 
              values('$a1.$a2.$cal1.1','$a1.$a2.$cal2.254','$b1.$b2.$b3.$b4','$c','$d','$e','$g','$h','$i','$j','$a5.$a6.$a7.$a8','$k','$a9.$a10.$a11.$a12','$l')";
	}
	elseif($b3==0)
	{
		     $cal = ((int)$a2&(int)$b2);
		     $query = "insert into Department_Allocate_IP (start_ip,end_ip,submask,department,name,tel,email,location,switch,port_no,port_ip,link_switch,link_ip,memo) 
	         values('$a1.$cal.0.1','$a1.$cal.255.254','$b1.$b2.$b3.$b4','$c','$d','$e','$g','$h','$i','$j','$a5.$a6.$a7.$a8','$k','$a9.$a10.$a11.$a12','$l')"; 
	}
	elseif($b3==255)
	{                                          //搞了很久的 $cal運算 $a和$b前面要加上(int)指定其為整數，可能是
	         $cal1= ((int)$a4&(int)$b4)+1;     //我在form 那邊沒有先指定a1~4,b1~4為整數，他在if裡面把他判斷成
             $cal2=$cal1+(253-$b4);            //字串所以無法做AND運算，下次要注意，為了這個搞半天                                          
	     	 $ip_start = ("$a1.$a2.$a3.$cal1");
	         $ip_end = ("$a1.$a2.$a3.$cal2");
	         $judge_result = mysql_query("select * from Department_Allocate_IP");
	         #$judge = mysql_fetch_assoc($judge_result);
	 while ($judge = mysql_fetch_assoc($judge_result))
	{ 
		
 		if ((ip2long($ip_start) >= ip2long($judge['start_ip'])) && (ip2long($ip_start) <= ip2long($judge['end_ip']))
		#	||  (ip2long($ip_end) >= ip2long($judge['start_ip'])) && (ip2long($ip_end) <= ip2long($judge['end_ip'])) 
			||  (ip2long($judge['start_ip']) > ip2long($ip_start) && ip2long($judge['end_ip']) < ip2long($ip_end)))
		
			
		{      
	            echo '<p align="center"><a href="javascript:history.go(-1);">返回上一頁</a></p></br>';
		   		echo '<font color="#FF0000"><h1  align="center">此IP區段重複!!</h1></font>';
		   		die; 
		} 
		
		
	}		 
		$query = "insert into Department_Allocate_IP (start_ip,end_ip,submask,department,name,title,tel,email,location,switch,switch_port_no,switch_port_ip,link_switch,link_ip,note) 
     			      values('$a1.$a2.$a3.$cal1','$a1.$a2.$a3.$cal2','$b1.$b2.$b3.$b4','$c','$name','$title','$tel','$email','$location','$i','$j','$a5.$a6.$a7.$a8','$k','$a9.$a10.$a11.$a12','$l')"; 
	     
	}	
	
	 mysql_query($query) or die('Insert data fail' . mysql_error());	
	 echo '新增資料成功!!';
     echo '<meta http-equiv=REFRESH CONTENT=2;url=add_list.php>';
	 #header("Location: add_list.php");
	}		 
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>中興大學校園網路管理系統</title>
<link href="style.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php if(isset($_GET["loginStats"]) && ($_GET["loginStats"]=="1")){?>
<script language="javascript">
alert('IP資料新增成功。');
window.location.href='adm.php';		  
</script>
<?php }?>
<table width="1200" border="0" align="center" cellpadding="4" cellspacing="0">
  <tr>
    <td class="tdbline"><img src="images/top.jpg"  width="1200" height="100"></td>
  </tr>
  
  <tr>
    <td class="tdbline"><table width="100%" border="0" cellspacing="0" cellpadding="10">
      <tr valign="top">
        <td class="tdrline"><form action="" method="POST" name="formJoin" id="formJoin" onSubmit="return checkForm();">
          <p class="title">新增IP資料</p>
		 
          <div class="dataDiv">		  
            <hr />
			
            
            <table width="700" border="0">
              <tr>
                <th width="150" scope="col"><div align="justify"><strong>IP   資料 </strong>：</div></th>
                <th width="135" scope="col"> <div align="left">
                  <input name="ip1" type="text" size="1" maxlength="3" value="140" readonly >
                  <strong>.</strong>
                  <input name="ip2" type="text" size="1" maxlength="3" value="120" readonly >
                  <strong>.</strong>
                  <input name="ip3" type="text" size="1" maxlength="3" class="normalinput" id="ip3">
                  <strong>.</strong>                  
                  <input name="ip4" type="text" size="1" maxlength="3" class="normalinput" id="ip4">
                </div></th>
              </tr>
              <tr>
                <td><div align="justify"><strong>Submask</strong>：</div></td>
                <td><div align="left">
                  <input name="submask1" type="text" size="1" maxlength="3" value="255" readonly >
                  <strong>.</strong>
                  <input name="submask2" type="text" size="1" maxlength="3" value="255" readonly >
                  <strong>.</strong>
                  <input name="submask3" type="text" size="1" maxlength="3" value="255" readonly>
                  <strong>.</strong>
                  <select name="submask4" >
                    <option value="0">0</option>
                    <option value="128">128</option>
                    <option value="192">192</option>
                    <option value="224">224</option>
                    <option value="240">240</option>
                    <option value="248">248</option>
                    <option value="252">252</option>
                  </select>
                </div></td>
              </tr>
              <tr>
                <td><div align="justify" ><strong>系所單位</strong>：</div></td>
                <td><div align="left">
                  <select name="department" >
                    <?php
		    $result_option = mysql_query('SELECT * FROM Account_Management');
		    while ($row_option = mysql_fetch_assoc($result_option))
		    {
		       echo "<option value=".$row_option['department'].">".$row_option['department']."</option>";  
	        } 
      	  ?>
                  </select>
                </div></td>
              </tr>
              <tr>
                <td><div align="justify"><strong> Core Switch </strong>：</div></td>
                <td><div align="left">
                  <select name="switch">
                    <option vlan="6513-2">6513-2</option>
                    <option vlan="6513">6513</option>
					<option vlan="6509">6509</option>
                    <option vlan="3750">3750</option>
                    <option vlan="3750cc">3750cc</option>
                    <option vlan="3750dorm">3750dorm</option>
		    <option vlan="3550adm">3550adm</option>
                    <option vlan="3560winpin">3560雲平</option>
                  </select>
                </div></td>
              </tr>
              <tr>
                <td><div align="justify"><strong>Core Switch  port no</strong>：</div></td>
                <td><div align="left">
                  <input name="port_no" type="text"  size="5">
                </div></td>
              </tr>
              <tr>
                <td><div align="justify"><strong>Core Switch  port ip </strong>：</div></td>
                <td><div align="left">
                  <input name="ip5" type="text" size="1"  maxlength="3" value="140" readonly >
                  <strong>.</strong>
                  <input name="ip6" type="text" size="1"  maxlength="3" value="120" readonly >
                  <strong>.</strong>
                  <input name="ip7" type="text" size="1" maxlength="3" >
                  <strong>.</strong>
                  <input name="ip8" type="text" size="1" maxlength="3" >
                </div></td>
              </tr>
              <tr>
                <td><div align="justify"><strong>連線單位設備</strong>：</div></td>
                <td><div align="left">
                  <input name="link_switch" type="text"  size="15">
                </div></td>
              </tr>
              <tr>
                <td><div align="justify"><strong>與Core Switch所連結的 port ip</strong>：</div></td>
                <td><div align="left">
                  <input name="ip9" type="text" size="1"  maxlength="3" value="140" readonly >
                  <strong>.</strong>
                  <input name="ip10" type="text" size="1"  maxlength="3" value="120" readonly >
                  <strong>.</strong>
                  <input name="ip11" type="text" size="1" maxlength="3" >
                  <strong>.</strong>
                  <input name="ip12" type="text" size="1" maxlength="3" >
                </div></td>
              </tr>
              <tr>
                <td><div align="justify"><strong>備註</strong>：</div></td>
                <td><div align="left">
                  <textarea name="note" rows="5" cols="50" ></textarea>
                </div></td>
              </tr>
            </table>
        
            
       
         
          <hr />
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
			<font color='FF9933'>新增IP資料</font>
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
