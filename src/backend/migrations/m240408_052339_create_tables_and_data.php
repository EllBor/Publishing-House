<?php

use yii\db\Migration;

/**
 * Class m240408_052339_create_tables_and_data
 */
class m240408_052339_create_tables_and_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('Authors', [
            'id' => $this->primaryKey(),
            'firstName' => $this->string(40)->notNull(),
            'lastName' => $this->string(40)->notNull(),
        ]);

        $this->batchInsert('Authors', ['firstName', 'lastName'], [
            ['Иван', 'Иванов'],
            ['Ирина', 'Иринина'],
            ['Артём', 'Артёмов'],
            ['Алёна', 'Алёнина'],
        ]);

        $this->createTable('Books', [
            'id' => $this->primaryKey(),
            'ISBN' => $this->string(20)->notNull(),
            'nameBook' => $this->string(40)->notNull(),
            'numberOfPages' => $this->integer()->notNull(),
            'dateOfPublication' => $this->date()->notNull(),
        ]);

        $this->batchInsert('Books', ['ISBN', 'nameBook', 'numberOfPages', 'dateOfPublication'], [
            ['123456789', 'Книга 1', 300, '2022-01-01'],
            ['987654321', 'Книга 2', 250, '2022-11-01'],
            ['587954021', 'Книга 3', 350, '2023-02-01'],
            ['555555555', 'Книга 4', 400, '2024-01-12'],
        ]);

        $this->createTable('Genres', [
            'id' => $this->primaryKey(),
            'nameOfGenres' => $this->string(20)->notNull(),
        ]);

        $this->batchInsert('Genres', ['nameOfGenres'], [
            ['Фантастика'],
            ['Детектив'],
            ['Роман'],
        ]);

        $this->createTable('Books_Genres', [
            'book_id' => $this->integer(),
            'genre_id' => $this->integer(),
            'PRIMARY KEY (book_id, genre_id)',
        ]);

        $this->addForeignKey('fk_books_genres_book_id', 'Books_Genres', 'book_id', 'Books', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_books_genres_genre_id', 'Books_Genres', 'genre_id', 'Genres', 'id', 'CASCADE', 'CASCADE');

        $this->batchInsert('Books_Genres', ['book_id', 'genre_id'], [
            [1, 2],
            [2, 2],
            [3, 3],
            [4, 1],
            [4, 2],
            [4, 3],
            [1, 1],
        ]);

        $this->createTable('Books_Authors', [
            'book_id' => $this->integer(),
            'author_id' => $this->integer(),
            'PRIMARY KEY (book_id, author_id)',
        ]);

        $this->addForeignKey('fk_books_authors_book_id', 'Books_Authors', 'book_id', 'Books', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_books_authors_author_id', 'Books_Authors', 'author_id', 'Authors', 'id', 'CASCADE', 'CASCADE');

        $this->batchInsert('Books_Authors', ['book_id', 'author_id'], [
            [1, 2],
            [2, 2],
            [3, 3],
            [4, 1],
            [3, 1],
            [4, 2],
            [1, 4],
        ]);

        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'password_hash' => $this->string()->notNull(), 
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
        $this->truncateTable('Books_Authors');
        $this->truncateTable('Books_Genres');
        $this->dropTable('Authors');
        $this->dropTable('Genres');
        $this->dropTable('Books');
        $this->dropTable('Books_Authors');
        $this->dropTable('Books_Genres');
    }
}
