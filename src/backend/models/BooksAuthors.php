<?php

namespace app\models;

use yii\db\ActiveRecord;

class BooksAuthors extends ActiveRecord
{
    public static function tableName()
    {
        return 'books_authors';
    }

    public function getBook()
    {
        return $this->hasOne(Books::class, ['id' => 'book_id']);
    }

    public function getAuthor()
    {
        return $this->hasOne(Authors::class, ['id' => 'author_id']);
    }
}
