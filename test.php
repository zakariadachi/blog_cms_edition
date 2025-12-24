<?php
class User {
    protected int $id;
    protected string $username;
    protected string $email;
    protected string $role;
    protected string $password;

    public function __construct(string $username,string $email,string $role)
    {
        $this->username = $username;
        $this->email = $email;
        $this->role = $role;
    }

    public function afficherInfo()
    {
        return "user:  $this->username email: $this->email";
    }
    public function estAuteur()
    {
        if($this->role==="author")
        {
            return "OUI";
        }
        else return "NON";
    }

}
$user1 = new User("john", "john@mail.com","admin");
// echo $user1->afficherInfo();

$userAuteur = new User("lea", "lea@blog.com", "auteur");
$userVisiteur = new User("visiteur", "v@blog.com", "visiteur");
// echo $userAuteur->estAuteur()."\n";
// echo $userVisiteur->estAuteur();

class Article {
    protected string $title;
    protected string $content;
    protected string $statu;

    public function __construct(string $title,string $content) 
    {
        $this->title = $title;
        $this->content = $content;
        $this->statu = "brouillon";
    }
    public function afficher()
    {
        return "title : $this->title content : $this->content statu : $this->statu \n";  
    }

    public function toggleStatus()
    {
        $this->statu = "public";
        return;
    }

}
$article = new Article("the world", "the world is happy");
echo $article->afficher();
echo $article->toggleStatus();
echo $article->afficher();



