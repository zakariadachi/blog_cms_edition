<?php 
// class User {
//     protected int $id;
//     protected string $username;
//     protected string $email;
//     protected string $role;
//     protected string $password;

//     public function __construct(string $username,string $email,string $role)
//     {
//         $this->username = $username;
//         $this->email = $email;
//         $this->role = $role;
//     }

//     public function afficherInfo()
//     {
//         return "user:  $this->username email: $this->email";
//     }
//     public function estAuteur()
//     {
//         if($this->role==="author")
//         {
//             return "OUI";
//         }
//         else return "NON";
//     }

// }
// $user1 = new User("john", "john@mail.com","admin");
// // echo $user1->afficherInfo();

// $userAuteur = new User("lea", "lea@blog.com", "auteur");
// $userVisiteur = new User("visiteur", "v@blog.com", "visiteur");
// // echo $userAuteur->estAuteur()."\n";
// // echo $userVisiteur->estAuteur();

// class Article {
//     protected string $title;
//     protected string $content;
//     protected string $statu;

//     public function __construct(string $title,string $content) 
//     {
//         $this->title = $title;
//         $this->content = $content;
//         $this->statu = "brouillon";
//     }
//     public function afficher()
//     {
//         return "title : $this->title content : $this->content statu : $this->statu \n";  
//     }

//     public function toggleStatus()
//     {
//         $this->statu = "public";
//         return;
//     }

// }
// $article = new Article("the world", "the world is happy");
// echo $article->afficher();
// echo $article->toggleStatus();
// echo $article->afficher();


// class User {

//     protected string $user_name;
//     protected string $email;
//     protected int $age;

//     public function __construct($user_name,$email,$age)
//     {
//         $this->user_name=$user_name;
//         $this->email=$email;
//         $this->age=$age;
//     }

//     public function getUsername()
//     {
//         return $this->user_name;
//     }

//     public function estMajeur()
//     {
//         if($this->age>=18)
//             return true;
//         else
//             return false;
//     }

//     public function afficherInfos() {
//         if($this->estMajeur())
//             return "nom: $this->user_name \n email: $this->email \n majour: Oui";
//         else
//             return "nom: $this->user_name \n email: $this->email \n majour: Non";
//     }
// }

// $User1 = new User("john","john@gmail.com",19);
// $User = new User("john","john@gmail.com",16);
// echo $User->afficherInfos();

// class User {

//     protected $username;
//     protected $email;
//     protected $role;

//     public function __construct($username, $email,$role)
//     {
//         $this->username=$username;
//         $this->email=$email;
//         $this->role=$role;
//     }

//     public function getUsername()
//     {
//         return $this->username;
//     }

//     public function getEmail()
//     {
//         return $this->email;
//     }

//      public function getRole()
//     {
//         return $this->role;
//     }

    

//     public function afficher()
//     {
//         return "name: $this->username | email: $this->email | role: $this->role \n";
//     }

// public function setEmail($email)
// {
//     if (strpos($email, '@') !== false) {
//         $this->email = $email;
//     }
// }

// public function setRole($role)
// {
//     if ($role == "admin" || $role == "author" || $role == "visitor") {
//         $this->role = $role;
//     }
// }


// }

// $user1 = new User("ali", "ali@mail.com","admin");
// $user2 = new User("ziko", "ziko@mail.com","author");
// $user3 = new User("aymen", "aymen@mail.com","visitor");
// $user1->setEmail("wrongmail.com");
// $user1->setEmail("wrong@mail.com");
// $user1->setRole("author");
// $user2->setRole("ziko");
// $user2->setEmail("wrong@mail.com");
// $user3->setRole("admin");

// echo $user1->afficher();
// echo $user2->afficher();
// echo $user3->afficher();


/***********************
 *  Classe User
 ***********************/
// class User
// {
//     private string $user_name;
//     private string $email;

//     public function __construct(string $user_name, string $email)
//     {
//         $this->user_name = $user_name;
//         $this->email = $email;
//     }

//     public function getUserName(): string
//     {
//         return $this->user_name;
//     }

//     public function getEmail(): string
//     {
//         return $this->email;
//     }

//     public function afficher(): string
//     {
//         return "user: {$this->user_name} | email: {$this->email}\n";
//     }
// }


// /***********************
//  *  Classe UserManager
//  ***********************/
// class UserManager
// {
//     protected array $users = [];

//     public function ajouterUser(): void
//     {
//         echo "Entrer username: ";
//         $username = trim(fgets(STDIN));

//         echo "Entrer email: ";
//         $email = trim(fgets(STDIN));

//         $user = new User($username, $email);
//         $this->users[] = $user;

//         echo "Utilisateur ajouté avec succès.\n";
//     }

//     public function afficherUsers(): void
//     {
//         if (empty($this->users)) {
//             echo "Aucun utilisateur.\n";
//             return;
//         }

//         foreach ($this->users as $user) {
//             echo $user->afficher();
//         }
//     }
// }


// /***********************
//  *  Classe Menu
//  ***********************/
// class Menu
// {
//     public function afficherMenu(): void
//     {
//         echo "\n===== MENU =====\n";
//         echo "1. Ajouter utilisateur\n";
//         echo "2. Afficher utilisateurs\n";
//         echo "3. Quitter\n";
//         echo "Choix: ";
//     }

//     public function lireChoix(): int
//     {
//         return (int) trim(fgets(STDIN));
//     }

//     public function executerChoix(int $choix, UserManager $manager): void
//     {
//         switch ($choix) {
//             case 1:
//                 $manager->ajouterUser();
//                 break;
//             case 2:
//                 $manager->afficherUsers();
//                 break;
//             case 3:
//                 $this->quitter();
//                 break;
//             default:
//                 echo "Choix invalide.\n";
//         }
//     }

//     public function quitter(): void
//     {
//         echo "Au revoir 👋\n";
//         exit;
//     }
// }


// /***********************
//  *  Programme principal
//  ***********************/
// $menu = new Menu();
// $manager = new UserManager();

// while (true) {
//     $menu->afficherMenu();
//     $choix = $menu->lireChoix();
//     $menu->executerChoix($choix, $manager);
// }


class User {
    private string $username;
    private string $password;

    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    public function getUserName()
    {
        return $this->username;
    }

    public function verifyPassword($password)
    {
        return $password === $this->password;
    }
}



class Article {
    private int $id;
    private string $title;

    public function __construct(int $id,string $title) 
    {
        $this->id = $id;
        $this->title = $title;
    }

}
class Collection {
private $users = [];
private $articles = [];
private $categories = [];
private $current_user = null;

public function __construct()
{
     $this->users = [
        new User('alice', 'pass123'),
        new User("bob", "bobpass"),
        new User("charlie", "charlie123")
    ];
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




// TEST FINAL
$collection = new Collection();
// Test 1: Connexion réussie
$result = $collection->login('alice', 'pass123');
echo $result ? "Connexion alice OK" : "Échec connexion alice";
// Test 2: Connexion échouée (mauvais mot de passe)
$result = $collection->login('alice', 'wrongpass');
echo !$result ? "Rejet mauvais mot de passe OK" : "Problème vérification";
// Test 3: Vérification état connexion
if ($collection->isLoggedIn()) {
$user = $collection->getCurrentUser();
echo "Utilisateur connecté: " . $user->getUserName();
}
// Test 4: Déconnexion
$collection->logout();
echo !$collection->isLoggedIn() ? "Déconnexion OK" : "Problème déconnexion";





echo "=== BLOGCMS CONSOLE AVEC AUTHENTIFICATION ===\n";

$db = new Collection();
$running = true;

while ($running) {
    // AFFICHAGE DE L'EN-TÊTE AVEC ÉTAT DE CONNEXION
    if ($db->isLoggedIn()) {
        $user = $db->getCurrentUser();
        echo "\n--- Connecté en tant que: {$user->username} ({$user->role}) ---\n";
    } else {
        echo "\n--- MENU VISITEUR (non connecté) ---\n";
    }

    // MENU DYNAMIQUE - CHANGE SELON L'ÉTAT DE CONNEXION
    if (!$db->isLoggedIn()) {
        // Menu visiteur (non connecté)
        echo "1. Voir tous les articles\n";
        echo "2. Se connecter\n";
        echo "0. Quitter\n";
    } else {
        // Menu utilisateur connecté
        echo "1. Voir tous les articles\n";
        echo "2. Créer un nouvel article\n";
        echo "3. Voir mes informations\n";
        echo "4. Se déconnecter\n";
        echo "0. Quitter\n";
    }

    $choice = readline("Votre choix : ");

    // TRAITEMENT DES CHOIX
    if (!$db->isLoggedIn()) {
        // Traitement menu visiteur
        switch ($choice) {
            case '1': // Voir tous les articles
                $db->showAllArticles();
                break;
            case '2': // Se connecter
                $username = readline("Username : ");
                $password = readline("Password : ");
                if ($db->login($username, $password)) {
                    echo "Connexion réussie !\n";
                } else {
                    echo "Échec de connexion\n";
                }
                break;
            case '0': // Quitter
                $running = false;
                echo "Au revoir !\n";
                break;
            default:
                echo "Choix invalide. Veuillez réessayer.\n";
        }
    } else {
        // Traitement menu utilisateur connecté
        switch ($choice) {
            case '1': // Voir tous les articles
                $db->showAllArticles();
                break;
            case '2': // Créer un nouvel article
                $title = readline("Titre de l'article : ");
                $content = readline("Contenu : ");
                $db->createArticle($title, $content, $user->username);
                echo "Article créé avec succès !\n";
                break;
            case '3': // Voir mes informations
                echo "Username : {$user->username}\n";
                echo "Email : {$user->email}\n";
                echo "Rôle : {$user->role}\n";
                break;
            case '4': // Se déconnecter
                $db->logout();
                echo "Vous êtes déconnecté.\n";
                break;
            case '0': // Quitter
                $running = false;
                echo "Au revoir !\n";
                break;
            default:
                echo "Choix invalide. Veuillez réessayer.\n";
        }
    }
}






























