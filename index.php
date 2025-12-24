<?php
class user 
{
    protected int $id;
    protected string $user_name;
    protected string $email;
    protected string $password;
    protected string $role;
    protected datetime $create_at;
    protected ?datetime $last_login=null;


    public function __construct($id,$user_name,$email,$password,$role,$create_at,$last_login)
    {
        $this->id=$id;
        $this->user_name=$user_name;
        $this->email=$email;
        $this->password=$password;
        $this->role=$role;
        $this->create_at=$create_at;
        $this->last_login=$last_login;
    }
}

class article 
{
    private int $id;
    private string $title;
    private string $contenu;
    private DateTime $crate_at;
    private ?DateTime $updated_at = null;


    public function __construct($id,$user_name,$email,$password,$role,$create_at,$last_login)
    {
        $this->id=$id;
        $this->user_name=$user_name;
        $this->email=$email;
        $this->password=$password;
        $this->role=$role;
        $this->create_at=$create_at;
        $this->last_login=$last_login;
    }
}