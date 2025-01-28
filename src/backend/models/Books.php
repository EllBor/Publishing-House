<?php

namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use yii\helpers\ArrayHelper;

class Books extends ActiveRecord
{
  public $authors;
  public $genres;
  public $authorNames;
  public $genreNames;

  public static function tableName()
  {
    return 'books';
  }

  public function getGenres()
  {
    return $this->hasMany(Genres::class, ['id' => 'genre_id'])
      ->viaTable('books_genres', ['book_id' => 'id']);
  }

  public function getAuthors()
  {
    return $this->hasMany(Authors::class, ['id' => 'author_id'])
      ->viaTable('books_authors', ['book_id' => 'id']);
  }

  public function afterSave($insert, $changedAttributes)
  {
    parent::afterSave($insert, $changedAttributes);

    if ($insert) {
      $this->afterCreate();
    } else {

      $this->afterUpdate();
    }
  }

  public function afterCreate()
  {
    $selectedAuthors = explode(',', Yii::$app->getRequest()->getBodyParam('authors'));
    $selectedGenres = explode(',', Yii::$app->getRequest()->getBodyParam('genres'));

    if (!empty($selectedAuthors)) {
      foreach ($selectedAuthors as $authorId) {
        $booksAuthors = new BooksAuthors(['book_id' => $this->id, 'author_id' => $authorId]);
        $booksAuthors->save();
      }
    }

    if (!empty($selectedGenres)) {
      foreach ($selectedGenres as $genreId) {
        $booksGenres = new BooksGenres(['book_id' => $this->id, 'genre_id' => $genreId]);
        $booksGenres->save();
      }
    }
  }

  public function afterUpdate()
  {
    $selectedAuthors = explode(',', Yii::$app->getRequest()->getBodyParam('authors'));
    $selectedGenres = explode(',', Yii::$app->getRequest()->getBodyParam('genres'));

    $this->unlinkAll('authors', true);
    $this->unlinkAll('genres', true);

    if (!empty($selectedAuthors)) {
      foreach ($selectedAuthors as $authorId) {
        $author = Authors::findOne($authorId);
        if ($author) {
          $this->link('authors', $author);
        }
      }
    }

    if (!empty($selectedGenres)) {
      foreach ($selectedGenres as $genreId) {
        $genre = Genres::findOne($genreId);
        if ($genre) {
          $this->link('genres', $genre);
        }
      }
    }
  }

  public function beforeDelete()
  {
    BooksAuthors::deleteAll(['book_id' => $this->id]);
    BooksGenres::deleteAll(['book_id' => $this->id]);
    return parent::beforeDelete();
  }

  public function fields()
  {
    return ArrayHelper::merge(parent::fields(), [
      'authorNames',
      'genreNames',
    ]);
  }

  public function rules()
  {
    return [
      [['ISBN', 'nameBook', 'numberOfPages', 'dateOfPublication'], 'required'],
      ['nameBook', 'string', 'min' => 4],
      ['numberOfPages', 'integer'],
      ['dateOfPublication', 'date', 'format' => 'php:Y-m-d'],
      [['authors', 'genres', 'id'], 'safe'],
    ];
  }
}
