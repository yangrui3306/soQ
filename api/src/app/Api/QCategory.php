<?php
namespace App\Api;

use PhalApi\Api;
use App\Common\Match as Match;

use App\Common\Tools as Tools;
use App\Model\KeyWord as KeyWord;
use App\Domain\Question\Upload;
use App\Domain\Question\Recommend;
use App\Domain\Question\Basic;
use App\Model\Category as ModelCategory;
use App\Model\Question\Search as ModelSearchQ;
use App\Common\MyStandard;
use App\Domain\Behavior\Statistics as ModelStatistics;

/**
 * 题目分类部分
 * @author: goodtimp 2019-03-06
 */
class QCategory extends Api
{
  public function getRules()
  {
    return array(
      'getcates' => array(),
    );
  }

   /**
     * 得到所有题目分类信息
     * @desc 得到所有题目分类信息
     * @return 题目分类数组
     */

  public function getcates()
  {
    $mc = new ModelCategory();
    return MyStandard::gReturn(0,$mc->getAllCategories());
  }
}
