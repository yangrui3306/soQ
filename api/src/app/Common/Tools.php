<?php

/**
 * @author : goodtimp
 * @time : 2019-3-2
 */
namespace App\Common;

use App\Model\KeyWord as KeyWord;

class  Tools
{
  /** 
     * 关键字提取并根据出现次数排序 静态方法 "::"调用
     * @param $text 待提取的文字
     * @return 关键字Id和Word数组
     */
  public static function ExtractKeyWords($text)
  {
    $kw = new KeyWord();
    $keys = $kw->gesAllKeyWord();

    // 数据库查询语句，判断题目中存在的关键字
    $command = "select Id,Word from keyword where '" . $text . "' like concat('%',Word,'%')";
    $keyarr = $keys->queryAll($command);

    //排序
    $reslut = array();
    $temp = array();
    foreach ($keyarr as $key) {

      $cnt = substr_count($text, $key['Word']);
      for ($i = 0; $i < count($reslut); $i++) {
        if ($temp[$i] < $cnt) break;
      }

      array_splice($reslut, $i, 0, $key);

      array_splice($temp, $i, 0, $cnt); //向 $temp 的$i下标处插入$cnt数据
    }
    return $reslut;
  }

  /**
   * 得到Array内指定的键，并声称相应的数组
   */
  public static function GetValueByKey($arr,$key)
  {
    try{
      $reslut=array();
      foreach($arr as $temp)
      {
        array_push($reslut,$temp[$key]);
      }
      return $reslut;
    }
    catch(Exception $e){
      return [];
    }
    
  }
}
