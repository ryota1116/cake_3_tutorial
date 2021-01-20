<?php

namespace App\Model\Table;

use Cake\ORM\Table;
// Textクラス
use Cake\Utility\Text;
// Validator クラスをインポート
use Cake\Validation\Validator;

class ArticlesTable extends Table
{
  public function initialize(array $config)
  {
      $this->addBehavior('Timestamp');
      $this->belongsToMany('tags');
  }

  public function beforeSave($event, $entity, $options)
  {
    if($entity->isNew() && !$entity->slug) {
      $sluggedTitle = Text::slug($entity->title);
      // スラグをスキーマで定義されている最大長に調整
      $entity->slug = substr($sluggedTitle, 0, 191);
    }
  }

  // validationDefault()メソッドは、save()メソッドが呼ばれる際のデータの検証方法をCakePHPに伝えている
  public function ValidationDefault(Validator $validator) {
    $validator
      ->allowEmptyString('title', false)
      ->minLength('title', 10)
      ->maxLength('title', 255)
      // ->add('title', [
      //   'length' => [
      //     'rule' => ['minLength', 10],
      //     'message' => 'タイトルは10字以上必要です。'
      //   ]
      // ])

      ->allowEmptyString('body', false)
      ->minLength('body', 10);

    return $validator;
  }
}