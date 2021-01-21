<?php

namespace App\Model\Table;

use Cake\ORM\Table;
// Textクラス
use Cake\Utility\Text;
// Validator クラスをインポート
use Cake\Validation\Validator;
// Query クラスをインポート
use Cake\ORM\Query;

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

  public function findTagged(Query $query, array $options) {
    $columns = [
      'Articles.id', 'Articles.user_id', 'Articles.title',
      'Articles.body', 'Articles.published', 'Articles.created',
      'Articles.slug',
    ];

    $query = $query
              // select() でロードするフィールドを限定している
              ->select($columns)
              // 重複行をまとめる
              ->distinct($columns);

    if (empty($options['tags'])) {
      // タグが指定されていない場合は、タグのない記事を検索
      $query->leftJoinWith('Tags')
          ->where(['Tags.title IS' => null]);
    } else {
      // 提供されたタグが1つ以上ある記事を検索
      $query->innerJoinWith('Tags')
          ->where(['Tags.title IN' => $options['tags']]);
    }

    return $query->group(['Articles.id']);
  }
}