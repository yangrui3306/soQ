<?php
namespace App\Common;

/* --------------   图形处理类   ----------------- */

/**
 * GD库处理图形 
 * @author ipso
 */
class GD {

	private $colors = array( //四种默认颜色
    array(
        "R" => "45",
        "G" => "140",
        "B" => "240"
    ),
    array(
        "R" => "25",
        "G" => "190",
        "B" => "107"
    ),
    array(
        "R" => "255",
        "G" => "153",
        "B" => "0"
    ),
    array(
        "R" => "237",
        "G" => "63",
        "B" => "20"
    ),
  );
  private $bgColor = array(
    "R" => "255",
    "G" => "255",
    "B" => "255"
  );

	/**
   * @desc   生成随机验证码
	 * @author ipso
   * @num    生成验证码的位数
   * @return 返回一个数组，包含生成的验证码以及对应的base64格式的验证码图片信息
   */
  public function getUserVerificationCodeRandom($num){
    /* 创建画布 */
    $width     = 150;
    $height    = 40;
    $canvas    =  imagecreate($width, $height);

    // 将默认的背景有黑色改为白色
    $bgColor   = ImageColorAllocate($canvas,255,255,255);
    imagefill($canvas, 0, 0, $bgColor);

    /* 验证码设计 */
    $codeArr   = []; // 保存验证码，用来与用户验证码对比
    $code      = "";
    $fontStyle = 'font/Exo-ExtraBold.ttf';
    for($i = 0; $i < $num; $i++){
        $fontSize  = 20;
        $fontColor = ImageColorAllocate($canvas,10,10,10);
        $codeDataSource      = 'abcdefghijklmnopqrsguvwxyz0123456789'; // 用来生成随机的数字与字母的混合验证码
        $letter      = substr($codeDataSource, mt_rand(0,strlen($codeDataSource) - 1),1);
        $code .= $letter;
        // 每个验证码之间的间隔
        $x     = ($i*$width/4) + rand(5,10);
        $y     = rand(18,28);
        imagettftext($canvas, $fontSize, 0, $x, $y, $fontColor, $fontStyle, $letter);
    }
    $codeArr['code'] = $code;

    /* 生成3条线干扰 */
    $randSpot = rand(10,22); // 用于生成非直线干扰线
    $randSpot1 = rand(10,22);
    $color = $this->colors; // 获取默认的四种颜色作为干扰线颜色
    $randcolor = $color[rand(0,3)];
    $lineWidth = 2;
    for($i = 0;$i < 5; $i++){
        $lineColor = imageColorAllocate($canvas, $randcolor['R'], $randcolor['G'], $randcolor['B']);
        if($i == 2){
            imagesetthickness($canvas,$lineWidth);
            imageline($canvas, 1, rand(10,22), 50, $randSpot,$lineColor);
        }elseif($i == 3){ // 折线的第一个转折点
            imagesetthickness($canvas,$lineWidth);
            imageline($canvas, 50, $randSpot, 55, $randSpot1, $lineColor);
        }elseif($i == 4){ // 折线的第二个转折点
            imagesetthickness($canvas,$lineWidth);
            imageline($canvas, 55, $randSpot1, 149, rand(10,22), $lineColor);
        }
        else{
            imageline($canvas, rand(5,140), rand(18,25), rand(5,140), rand(18,25),$lineColor);
        }
    }

    /* 截取base64 */
    ob_start();
        imagepng($canvas);
        $img_base64 = ob_get_contents();
        //销毁图片
        imagedestroy($canvas);
    ob_end_clean();
    $res = '';
    $res .= chunk_split(base64_encode($img_base64));
    $codeArr['pic'] = $res;

    return $codeArr;
	}

	/**
	 * 生成用户随机头像
	 * @param  str  用户名
	 */
		public function getUserDefaultAvatarByName($str) {
        $text = mb_substr($str,0,1,'utf-8'); //截取第一个字符

        // if($text > '@' && $text < '{') //英文字符
        //     $text = strtoupper($text);
        // else {
        //     $words = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        //     $text = $words[rand(0,25)];
        // }
        //图片宽高
        $width = 25;
        $height = 25;
        //创建画布
        $canvas = imagecreate($width, $height);
        //字体颜色
        $color = rand(0, 3);
        //设置背景颜色 白色
				$background_color = ImageColorAllocate($canvas, 0, 0, 0);
				
				//设置字体
				$font = 'font/Exo-ExtraBold.ttf';
        //设置字体大小
        $fontSize = 16;
        //设置字体颜色
        $paint = imagecolorallocate($canvas, $this->colors[$color]["R"], $this->colors[$color]["G"], $this->colors[$color]["B"]);
        //字体高度
        $textWidth = imagefontwidth($fontSize);
        //字体宽度
        $textHeight = imagefontheight($fontSize);
        //绘制文字
				imagettftext($canvas, $fontSize, 0, 5, 20, $paint, $font, $text);
				// imagearc($canvas,100, 100, 150, 150, 0, 360," #bbbbbb");
        ob_start();
            imagepng($canvas);
            $img_base64 = ob_get_contents();
            //销毁图片
            imagedestroy($canvas);
        ob_end_clean();
        $res = '';
        $res .= chunk_split(base64_encode($img_base64));

        return $res;
    }
}