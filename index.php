<?php
class Collection {
protected $users = [];
protected $articles = [];
protected $categories = [];
protected $current_user = null;

public function __construct()
{

}

public function readArticle()
{
      if (empty($this->articles)) {
        echo "\nAucun article disponible pour le moment.\n";
        return;
    }

    echo "\n===== LISTE DES ARTICLES =====\n";

    foreach ($this->articles as $article) {
        echo "Titre     : {$article['title']}\n";
        echo "Auteur    : {$article['author']}\n";
        echo "Contenu   : {$article['content']}\n";
        echo "-----------------------------\n";
    }
}


// MÉTHODE À IMPLÉMENTER :
public function login($username, $password) {
    foreach ($this->users as $user) {
        if ($user->getUsername() === $username && $user->verifyPassword($password)) {
            $this->current_user = $user;
            return true;
        }else
            return false;
    }
        
    }
    public function logout() {
       return $this->current_user = null;
    }
    public function getCurrentUser() {
        return $this->current_user;
    }
    public function isLoggedIn() {
        if($this->current_user)
            return true;
        else
            return false;
    }

}

class User {
    private int $id;
    private string $username;
    private string $email;
    private string $password;
    private DateTime $createdAt;
    private ?DateTime $lastLogin;

    public function __construct(int $id,string $username,string $email,string $password)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = new DateTime();
        $this->lastLogin = null;
    }

    public function getUser_name()
    {
        $this->user_name=$username;
    }

    public function getEmail()
    {
        $this->email = $email;
    }

    public function readArticle() 
    {
        return "title: $this->getTitle";
    }
    
    public function writeComment() 
    {
        
    }
}

class Author extends User {
    protected string $bio;
    protected array $articles = [];

    public function __construct(int $id,string $username,string $email,string $password,string $bio)
    {
        parent::__construct($id, $username, $email, $password, 'author');
        $this->bio = $bio;
    }

    public function createArticle(article $article) 
    {
        $articles[]=$article;
    }

    public function deleteOwnArticle(int $articleId)
    {
    foreach ($this->articles as $index => $article) {
        if ($article->getId() === $articleId) {
            unset($this->articles[$index]);
            return true;
        }
    }
        return false;
    }
    


    public function updateOwnArticle() 
    {

    }

}

class Article {
    private int $id;
    private string $title;
    private string $content;
    protected array $comment;
    private string $statu;
    private DateTime $created_at;
    private DateTime $updated_at;

    public function __construct(int $id,string $title,string $content) 
    {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->created_at = new DateTime();
        $this->updated_at = new DateTime();
    }


    public function getTitle(){
        return $this->title=$title;
    }

    public function getStatus()
    {
        return $this->statu;
    }

    public function addCategory(): bool 
    { 

    }

    public function removeCategory(): bool 
    {

    }
    public function publish(): bool 
    {

    }
    public function unpublish(): bool 
    {
        
    }
    public function archiver(): bool 
    {
        
    }
}

class Category {
    private int $id;
    private string $name;

    public function __construct(int $id,string $name,?int $parentCategoryId = null) 
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function addSubCategory(Category $category): bool {

    }

    public function update(): bool 
    {
        if($this->getStatus()=="draft")
            $this->getStatus()=="published";
        else
            $this->getStatus()=="draft";
    }

    public function delete(): bool 
    {

    }

}

class Comment {
    private int $id;
    private string $content;
    private int $user_id;
    private int $article_id;

    public function __construct(int $id,string $content,int $user_id,int $article_id) 
    {
        $this->id = $id;
        $this->content = $content;
        $this->user_id = $user_id;
        $this->article_id = $article_id;
    }

    public function update(): bool 
    { 

    }

    public function delete(): bool 
    { 

    }
}

class Modirator {
    public function __construct() 
    {

    }

    public function createAssignArticle() 
    {

    }

    public function deleteArticle() 
    {

    }

    public function updateArticle() 
    {

    }

    public function publishArticle() 
    {

    }

    public function archiveArticle() 
    {

    }

    public function createCategory() 

    {

    }

    public function deleteCategory() 
    {

    }

    public function updateCategory() 
    {

    }


    public function updateComment() 
    {

    }
    public function deleteComment() 
    {

    }
}

class Editor extends Modirator {
    protected string $moderationLevel;

    public function __construct(int $id,string $username,string $email,string $password,string $moderationLevel) {
        parent::__construct();
        $this->moderationLevel = $moderationLevel;
    }
}

class Admin extends Modirator {
    protected bool $isSuperAdmin;

    public function __construct(int $id,string $username,string $email,string $password,bool $isSuperAdmin = false) {
        parent::__construct();
        $this->isSuperAdmin = $isSuperAdmin;
    }

    public function createUser() 
    {

    }

    public function deleteUser() 
    {

    }

    public function assignRole() 
    {

    }

}
