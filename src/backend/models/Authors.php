<?php

namespace app\models;

use yii\db\ActiveRecord;

class Authors extends ActiveRecord
{
    public static function tableName()
    {
        return 'authors';
    }

    public function getBooks()
    {
        return $this->hasMany(Books::class, ['id' => 'book_id'])
            ->viaTable('books_authors', ['author_id' => 'id']);
    }

    public function beforeDelete()
    {
        BooksAuthors::deleteAll(['author_id' => $this->id]);
        return parent::beforeDelete();
    }

    public function rules()
    {
        return [
            [['firstName', 'lastName'], 'required'],
            [['firstName', 'lastName'], 'string', 'min' => 3],
            [['id'], 'safe'],
        ];
    }
}
