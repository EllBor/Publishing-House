<?php

namespace app\models;

use yii\db\ActiveRecord;

class Genres extends ActiveRecord
{
    public static function tableName()
    {
        return 'genres';
    }

    public function getBooks()
    {
        return $this->hasMany(Books::class, ['id' => 'book_id'])
            ->viaTable('books_genres', ['genre_id' => 'id']);
    }

    public function beforeDelete()
    {
        BooksGenres::deleteAll(['genre_id' => $this->id]);
        return parent::beforeDelete();
    }

    public function rules()
    {
        return [
            ['nameOfGenres', 'required'],
            ['nameOfGenres', 'string', 'min' => 3],
            [['id'], 'safe'],
        ];
    }
}
