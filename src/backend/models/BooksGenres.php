<?php

namespace app\models;

use yii\db\ActiveRecord;

class BooksGenres extends ActiveRecord
{
    public static function tableName()
    {
        return 'books_genres';
    }

    public function getBook()
    {
        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }

    public function getGenre()
    {
        return $this->hasOne(Genres::class, ['id' => 'genre_id']);
    }
}
