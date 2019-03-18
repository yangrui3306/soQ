<?php
namespace App\Common;

/* --------------   余弦函数   ----------------- */

/**
 * 余弦函数
 * @author goodtimp
 */
class Cosines
{
  /**
 * 合并数组并去重
 * @param {数组} arr1 
 * @param {数组} arr2 
 */
  function uniqueArr($arr1, $arr2)
  {
    return array_keys(array_flip($arr1) + array_flip($arr2));
  }

  /**
 * 得到 词集
 * @param {} arrya 
 * @param {*} arryb
 */
  function getWordSet($arrya, $arryb)
  {
    $keys = $this->uniqueArr($arrya["Keys"], $arryb["Keys"]);

    $arrya["Keys"] = $keys;
    for ($i = count($arrya["Weight"]); $i < count($arrya["Keys"]); $i++) $arrya["Weight"][$i] = 0; //合并a

    $temp = array(
      'Keys' => $keys,
      'Weight' => []
    );

    for ($i = 0; $i < count($keys); $i++) {
      for ($j = 0; $j < count($arryb["Keys"]); $j++) {
        if ($keys[$i] == $arryb["Keys"][$j]) {
          $temp["Weight"][$i] = $arryb["Weight"][$j];
          break;
        }
      }
      if ($j == count($arryb["Keys"])) {
        $temp["Weight"][$i] = 0;
      }
    } ////合并b
    return [$arrya, $temp];
  }

  /**
 * 余弦定理计算
 * @param {} a 
 * @param {*} b 
 */
  function calculate($a, $b)
  {
    $molecule = 0.0;
    $denominator = 0.0;
    $tempa = 0;
    $tempb = 0;
    for ($i = 0; $i < count($a["Weight"]); $i++) {
      if(is_numeric($a["Weight"][$i])&&is_numeric($b["Weight"][$i]))
        $molecule += ($a["Weight"][$i] * $b["Weight"][$i]);
      if(is_numeric($a["Weight"][$i]))
        $tempa += ($a["Weight"][$i] * $a["Weight"][$i]);
      if(is_numeric($b["Weight"][$i]))
        $tempb += ($b["Weight"][$i] * $b["Weight"][$i]);
    }
    $denominator = sqrt($tempa) * sqrt($tempb);
    
    if ($denominator == 0) return 0;
    return sqrt($molecule) / $denominator;
  }
  /**
 * 运行入口,传入两个数组，数组内keys元素不能重复
 * @param {Keys:{1,2...},Weight:{1.0,2.0..}} a 
 * @param {*} b 
 */
  public function run($a, $b)
  {
    $lena = count($a["Keys"]);
    $lenb = count($b["Keys"]);
    $arry = $this->getWordSet($a, $b);
   
    if((count($arry[0]["Keys"])==$lena+$lenb)) return 0;
 
    return $this->calculate($arry[0],$arry[1]);
  }
}


// $a = array(
//   "Keys"=> array(1, 2, 5),
//   "Weight"=> array(1.7, 1.2, 0.1)
// );


// $b = array(
//   "Keys"=> array(3, 2, 4, 5),
//   "Weight"=> array(2.8, 1.1, 0.9, 0.2)
// );

// $cos =new Cosines();
// echo $cos->run($a,$b);
