<?php
	/*
	 * 函数：绘制验证码
	 * 输入参数：宽度（可选），高度（可选），验证码个数（可选），干扰点个数（可选）
	 * 输出：输出PNG格式的验证码图片
	 */
	function createCheckCode($width = 80, $height = 30, $num_code = 5, $num_disturb_points = 100){
		/* 创建画布 */
		$img = imagecreate($width, $height);								//创建图像句柄
		/* 绘制背景和边框 */
		$bg_color = imagecolorallocate($img, 255, 255, 255);				//背景色
		$border_color = imagecolorallocate($img, 0, 0, 0);					//边框色
		imagerectangle($img, 0, 0, $width-1, $height-1, $border_color);		//绘制边框
		/* 产生随机码 */
		$rand_num = rand();													//产生一个随机数
		$str = md5($rand_num);												//取得该随机数的MD5值
		$str_code = strtoupper(substr($str, 0, $num_code));					//从MD5值中截取字符作为验证码
		/* 绘制随机码 */
		for($i = 0; $i < $num_code; ++$i){
			$str_color = imagecolorallocate($img, rand(0,255), rand(0,255), rand(0,255));	//随机字体颜色
			$font_size = 5;																	//字体大小
			$str_x = floor(($width / $num_code)* $i) + rand(0,5);							//随机字体定位x坐标
			$str_y = rand(2, $height - 15);													//随机字体定位y坐标
			
			imagechar($img, $font_size, $str_x, $str_y, $str_code[$i], $str_color);			//绘制单个字符
		}
		/* 绘制干扰点 */
		for($i = 0; $i < $num_disturb_points; ++$i){
			$point_color =  imagecolorallocate($img, rand(0,255), rand(0,255), rand(0,255));	//随机干扰点颜色
			$point_x = rand(2, $width - 2);														//随机干扰点位置x坐标
			$point_y = rand(2, $height - 2);													//随机干扰点位置y坐标
		
			imagesetpixel($img, $point_x, $point_y, $point_color);								//绘制干扰点
		}
		/* 输出图片 */
		header("Content-type: image/png");									//发送Header信息	
		imagepng($img);														//输出图像
		imagedestroy($img);													//释放与图像关联的内存
		
		return $str_code;
	}
	
	/* 输出验证码 */
	session_start();								//启动会话
	$_SESSION['code'] = createCheckCode();			//保存验证码，以便与用户输入的验证码进行比对
?>