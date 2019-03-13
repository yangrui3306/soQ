<?php
namespace App\Api;

use PhalApi\Api;

use App\Model\KeyWord as ModelKeyword;
use App\Common\MyStandard;

/**
 * 关键字
 * @author: goodtimp 2019-03-13
 */
class Keyword extends Api
{
  public function getRules()
  {
    return array(
      'add' => array(
        'Word' => array('name' => 'Word', 'require' => true, 'desc' => '关键字'),
        'Weight' => array('name' => 'Weight', 'require' => true,  'desc' => '权重'),  
        'CategoryId' => array('name' => 'CategoryId', 'default'=>10,  'desc' => '分类id'),  
      ),
    );
  }
  /**
     * 添加关键字信息
     * @return id，-1为已经关注,
     */
  public function add()
  {
    $data=array(
      'Word'=>$this->Word,
      'Weight'=>$this->Weight,
      'CategoryId'=>$this->CategoryId
    );
    $dm = new ModelKeyword();
    $re = $dm->addKeyword($data);
    return MyStandard::gReturn(0, $re);
  }

}
