<?php
namespace App\Controller\Component;

// 全てのコンポーネントはCake\Controller\Componentを継承しなければならない。
use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;

class MathComponent extends Component {
  public function doComplexOperation($amount1, $amount2) {
    return $amount1 + $amount2;
  }

  protected $_defaultConfig = [];
}
