<?php

/**
 * @author : goodtimp
 * @time : 2019-3-1
 */
namespace App\Common;

use App\Common\Tools as Tools;

class Match
{
  /** 
   * 字符串相似度算法
  */
  public static function levenShtein($str1, $str2)
  {
    //计算两个字符串的长度。  
    $len1 = strlen($str1);
    $len2 = strlen($str2);
    //建立上面说的数组，比字符长度大一个空间  
    $dif = array();
    for ($a = 0; $a <= $len1; $a++) {
      $dif[$a] = array();
      $dif[$a][0] = $a;
    }
    for ($a = 0; $a <= $len2; $a++) {
      $dif[0][$a] = $a;
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
    $similarity = 1 -  $dif[$len1][$len2] * 1.0 / max(strlen($str1), strlen($str2), 1);
    return $similarity;
  }

  /** 题目相似度匹配出最大的前n个
   * @param $q 匹配的题目
   * @param $qs 待匹配的题目数组
   * @param $n 前n个相似度最大的
   * @return 前n个题目数组，若输入数组数量不够n直接输出
   */
  public static function qLevenShtein($q, $qs, $num = 3)
  {
    //排序
    $reslut = array();
    $leven = array();
    foreach ($qs as $mq) {
      $re = Match::levenShtein($q["Text"], $mq["Text"]);

      for ($i = 0; $i < count($reslut); $i++) {
        if ($leven[$i] < $re) break;
      }
      Tools::insertArray($reslut, $i, $mq);
      Tools::insertArray($leven, $i, $re); //向 $temp 的$i下标处插入$cnt数据
    }
    if (count($qs) <= $num) return $reslut;
    return array_slice($reslut, 0, $num);
  }
  /**
   * 关键字匹配，输入关键字输出为可用户数据库模糊查找的字符串
   * @param $keys 例如：质量
   * @return string 质量->%质%量%
   */
  public static function AllWordMatch($keys)
  {
    $s = "%";
    $arr=Tools::chToArray($keys);
   
    for ($i = 0; $i < count($arr); $i++) {
        $s = $s . $arr[$i] . "%";
      }
    return $s;
  }

}
