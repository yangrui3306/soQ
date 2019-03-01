<?php

/**
 * @author : goodtimp
 * @time : 2019-3-1
 */
namespace App\Common;


class Match {
      /** 
     * 　　DNA分析 　　拼字检查 　　语音辨识 　　抄袭侦测 
     *  
     * @createTime 2012-1-12 
     */  
    public function levenshtein($str1,$str2) {  
      //计算两个字符串的长度。  
      
      $len1 = strlen($str1);
      $len2 = strlen($str2);  
      //建立上面说的数组，比字符长度大一个空间  
      $dif=array();
      for ($a = 0; $a <= $len1; $a++) {  
          $dif[$a] = array();
          $dif[$a][0]=$a; 
      }  
      for ($a = 0; $a <= $len2; $a++) {  
        $dif[0][$a]=$a; 
      }  
      $temp = 0;
      for ($i = 1; $i <= $len1; $i++) {  
        for ($j = 1; $j <= $len2; $j++) {  
            if ($str1[$i - 1] == $str2[$j - 1]) {  
                $temp = 0;  
            } else {  
                $temp = 1;  
            }  
            //取三个值中最小的  
            $dif[$i][$j] = min($dif[$i - 1][$j - 1] + $temp, $dif[$i][$j - 1] + 1, $dif[$i - 1][$j] + 1);  
        }  
      }
      $similarity = 1 -  $dif[$len1][$len2]*1.0 / max(strlen($str1), strlen($str2));  
      return $similarity;
  }  
}
