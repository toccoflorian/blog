<?php

global $pdo;

class Article
{
    private PDOStatement  $statementreadOne;
    private PDOStatement  $statementreadAll;
    private PDOStatement  $statementreadAllByAuthor;
    private PDOStatement  $statementcreateOne;
    private PDOStatement  $statementupdateOne;
    private PDOStatement  $statementdeleteOne;


    # constructeur
    function __construct(private PDO $pdo)
    {
        $this->statementreadOne = $pdo->prepare('SELECT articles.*, user.firstname, user.lastname FROM articles LEFT JOIN user ON articles.author=user.id WHERE articles.id=:id');
        $this->statementreadAll = $pdo->prepare('SELECT articles.*, user.firstname, user.lastname FROM articles LEFT JOIN user ON articles.author=user.id ');
        $this->statementreadAllByAuthor = $pdo->prepare('SELECT * FROM articles WHERE author=:idauthor');
        $this->statementcreateOne = $pdo->prepare(
            'INSERT INTO articles (
                title,
                image,
                content,
                category,
                author
                ) VALUES(
                    :title,
                    :image,
                    :content, 
                    :category,
                    :author
            )'
        );
        $this->statementupdateOne = $pdo->prepare("UPDATE articles
                                                SET
                                                title = :title,
                                                image = :image,
                                                content = :content,
                                                category = :category,
                                                author = :author
                                                WHERE
                                                id = :id;");
        $this->statementdeleteOne = $pdo->prepare('DELETE FROM articles WHERE id=:id');
    }
    # read one
    function fetchOne(int $id)
    {
        $this->statementreadOne->bindValue(':id', $id);
        $this->statementreadOne->execute();
        return $this->statementreadOne->fetch();
    }
    #read all
    function fetchAll()
    {
        $this->statementreadAll->execute();
        return $this->statementreadAll->fetchAll();
    }
    #read all by author
    function fetchAllByAuthor($author)
    {
        $this->statementreadAllByAuthor->bindValue(':idauthor', $author);
        $this->statementreadAllByAuthor->execute();
        return $this->statementreadAllByAuthor->fetchAll();
    }
    # create one
    function createOne($article)
    {
        $this->statementcreateOne->bindValue(':title', $article['title']);
        $this->statementcreateOne->bindValue(':image', $article['image']);
        $this->statementcreateOne->bindValue(':content', $article['content']);
        $this->statementcreateOne->bindValue(':category', $article['category']);
        $this->statementcreateOne->bindValue(':author', $article['author']);
        $this->statementcreateOne->execute();
        return $this->fetchOne($this->pdo->lastInsertId());
    }
    # update one
    function updateOne($article)
    {
        $this->statementupdateOne->bindValue(':title', $article['title']);
        $this->statementupdateOne->bindValue(':image', $article['image']);
        $this->statementupdateOne->bindValue(':content', $article['content']);
        $this->statementupdateOne->bindValue(':category', $article['category']);
        $this->statementupdateOne->bindValue(':author', $article['author']);
        $this->statementupdateOne->bindValue(':id', $article['id']);
        $this->statementupdateOne->execute();
    }
    function deleteOne($id)
    {
        $this->statementdeleteOne->bindValue(':id', $id);
        $this->statementdeleteOne->execute();
    }
}

return new Article($pdo);
