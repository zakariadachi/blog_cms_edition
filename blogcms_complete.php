<?php

// ==================== CLASSE USER ====================
class User {
    protected int $id;
    protected string $username;
    protected string $email;
    protected string $password;
    protected string $role;
    protected DateTime $createdAt;
    protected ?DateTime $lastLogin;

    public function __construct(int $id, string $username, string $email, string $password, string $role) {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->password = password_hash($password, PASSWORD_DEFAULT);
        $this->role = $role;
        $this->createdAt = new DateTime();
        $this->lastLogin = null;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getRole(): string {
        return $this->role;
    }

    public function setRole(string $role): void {
        $this->role = $role;
    }

    public function verifyPassword(string $password): bool {
        return password_verify($password, $this->password);
    }

    public function updateLastLogin(): void {
        $this->lastLogin = new DateTime();
    }

    public function canCreateArticle(): bool {
        return in_array($this->role, ['author', 'editor', 'admin']);
    }

    public function canModifyArticle(Article $article): bool {
        if ($this->role === 'admin' || $this->role === 'editor') {
            return true;
        }
        if ($this->role === 'author') {
            return $article->getAuthorId() === $this->id;
        }
        return false;
    }

    public function canManageCategories(): bool {
        return in_array($this->role, ['editor', 'admin']);
    }

    public function canManageUsers(): bool {
        return $this->role === 'admin';
    }
}

// ==================== CLASSE AUTHOR ====================
class Author extends User {
    protected string $bio;

    public function __construct(int $id, string $username, string $email, string $password, string $bio = "") {
        parent::__construct($id, $username, $email, $password, 'author');
        $this->bio = $bio;
    }

    public function getBio(): string {
        return $this->bio;
    }

    public function setBio(string $bio): void {
        $this->bio = substr($bio, 0, 500);
    }
}

// ==================== CLASSE EDITOR ====================
class Editor extends User {
    protected string $moderationLevel;

    public function __construct(int $id, string $username, string $email, string $password, string $moderationLevel = 'junior') {
        parent::__construct($id, $username, $email, $password, 'editor');
        $this->moderationLevel = $moderationLevel;
    }

    public function getModerationLevel(): string {
        return $this->moderationLevel;
    }
}

// ==================== CLASSE ADMIN ====================
class Admin extends User {
    protected bool $isSuperAdmin;

    public function __construct(int $id, string $username, string $email, string $password, bool $isSuperAdmin = false) {
        parent::__construct($id, $username, $email, $password, 'admin');
        $this->isSuperAdmin = $isSuperAdmin;
    }

    public function isSuperAdmin(): bool {
        return $this->isSuperAdmin;
    }
}

// ==================== CLASSE ARTICLE ====================
class Article {
    private int $id;
    private string $title;
    private string $content;
    private string $status;
    private int $authorId;
    private array $categoryIds;
    private DateTime $createdAt;
    private ?DateTime $publishedAt;
    private ?DateTime $updatedAt;

    public function __construct(int $id, string $title, string $content, int $authorId) {
        $this->id = $id;
        $this->title = $title;
        $this->content = $content;
        $this->authorId = $authorId;
        $this->status = 'draft';
        $this->categoryIds = [];
        $this->createdAt = new DateTime();
        $this->publishedAt = null;
        $this->updatedAt = null;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): void {
        $this->title = $title;
        $this->updatedAt = new DateTime();
    }

    public function getContent(): string {
        return $this->content;
    }

    public function setContent(string $content): void {
        $this->content = $content;
        $this->updatedAt = new DateTime();
    }

    public function getStatus(): string {
        return $this->status;
    }

    public function getAuthorId(): int {
        return $this->authorId;
    }

    public function getCategoryIds(): array {
        return $this->categoryIds;
    }

    public function addCategory(int $categoryId): bool {
        if (!in_array($categoryId, $this->categoryIds)) {
            $this->categoryIds[] = $categoryId;
            return true;
        }
        return false;
    }

    public function removeCategory(int $categoryId): bool {
        $key = array_search($categoryId, $this->categoryIds);
        if ($key !== false) {
            unset($this->categoryIds[$key]);
            $this->categoryIds = array_values($this->categoryIds);
            return true;
        }
        return false;
    }

    public function publish(): bool {
        if (empty($this->categoryIds)) {
            return false;
        }
        $this->status = 'published';
        $this->publishedAt = new DateTime();
        return true;
    }

    public function unpublish(): bool {
        $this->status = 'draft';
        return true;
    }

    public function archive(): bool {
        $this->status = 'archived';
        return true;
    }

    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }

    public function getPublishedAt(): ?DateTime {
        return $this->publishedAt;
    }
}

// ==================== CLASSE CATEGORY ====================
class Category {
    private int $id;
    private string $name;
    private string $description;
    private ?int $parentId;
    private DateTime $createdAt;

    public function __construct(int $id, string $name, string $description = "", ?int $parentId = null) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->parentId = $parentId;
        $this->createdAt = new DateTime();
    }

    public function getId(): int {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): void {
        $this->name = $name;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function setDescription(string $description): void {
        $this->description = $description;
    }

    public function getParentId(): ?int {
        return $this->parentId;
    }
}

// ==================== CLASSE BLOGCMS (Gestionnaire Principal) ====================
class BlogCMS {
    private array $users = [];
    private array $articles = [];
    private array $categories = [];
    private ?User $currentUser = null;
    private int $nextUserId = 1;
    private int $nextArticleId = 1;
    private int $nextCategoryId = 1;

    public function __construct() {
        $this->initializeDefaultData();
    }

    private function initializeDefaultData(): void {
        // Créer un admin par défaut
        $admin = new Admin(
            $this->nextUserId++,
            'admin',
            'admin@blogcms.com',
            'admin123',
            true
        );
        $this->users[] = $admin;

        // Créer quelques catégories par défaut
        $tech = new Category($this->nextCategoryId++, 'Technologie', 'Articles tech');
        $this->categories[] = $tech;

        $programming = new Category($this->nextCategoryId++, 'Programmation', 'Code et dev', $tech->getId());
        $this->categories[] = $programming;

        $lifestyle = new Category($this->nextCategoryId++, 'Lifestyle', 'Vie quotidienne');
        $this->categories[] = $lifestyle;
    }

    // ========== GESTION DE L'AUTHENTIFICATION ==========
    public function login(string $username, string $password): bool {
        foreach ($this->users as $user) {
            if ($user->getUsername() === $username && $user->verifyPassword($password)) {
                $this->currentUser = $user;
                $user->updateLastLogin();
                return true;
            }
        }
        return false;
    }

    public function logout(): void {
        $this->currentUser = null;
    }

    public function getCurrentUser(): ?User {
        return $this->currentUser;
    }

    // ========== GESTION DES UTILISATEURS ==========
    public function createUser(string $username, string $email, string $password, string $role, string $extra = ""): ?User {
        if (!$this->currentUser || !$this->currentUser->canManageUsers()) {
            echo "❌ Permission refusée.\n";
            return null;
        }

        // Vérifier l'unicité
        foreach ($this->users as $user) {
            if ($user->getUsername() === $username || $user->getEmail() === $email) {
                echo "❌ Username ou email déjà utilisé.\n";
                return null;
            }
        }

        $user = null;
        switch ($role) {
            case 'author':
                $user = new Author($this->nextUserId++, $username, $email, $password, $extra);
                break;
            case 'editor':
                $user = new Editor($this->nextUserId++, $username, $email, $password, $extra ?: 'junior');
                break;
            case 'admin':
                $user = new Admin($this->nextUserId++, $username, $email, $password, false);
                break;
            default:
                $user = new User($this->nextUserId++, $username, $email, $password, 'visitor');
        }

        $this->users[] = $user;
        echo "✅ Utilisateur créé avec succès (ID: {$user->getId()}).\n";
        return $user;
    }

    public function listUsers(): void {
        if (!$this->currentUser || !$this->currentUser->canManageUsers()) {
            echo "❌ Permission refusée.\n";
            return;
        }

        echo "\n" . str_repeat("=", 80) . "\n";
        echo "LISTE DES UTILISATEURS\n";
        echo str_repeat("=", 80) . "\n";
        printf("%-5s %-20s %-30s %-10s\n", "ID", "Username", "Email", "Rôle");
        echo str_repeat("-", 80) . "\n";

        foreach ($this->users as $user) {
            printf("%-5d %-20s %-30s %-10s\n", 
                $user->getId(), 
                $user->getUsername(), 
                $user->getEmail(), 
                $user->getRole()
            );
        }
        echo str_repeat("=", 80) . "\n\n";
    }

    public function deleteUser(int $userId): bool {
        if (!$this->currentUser || !$this->currentUser->canManageUsers()) {
            echo "❌ Permission refusée.\n";
            return false;
        }

        if ($this->currentUser->getId() === $userId) {
            echo "❌ Vous ne pouvez pas vous supprimer vous-même.\n";
            return false;
        }

        foreach ($this->users as $key => $user) {
            if ($user->getId() === $userId) {
                // Archiver les articles de cet utilisateur
                foreach ($this->articles as $article) {
                    if ($article->getAuthorId() === $userId) {
                        $article->archive();
                    }
                }
                unset($this->users[$key]);
                $this->users = array_values($this->users);
                echo "✅ Utilisateur supprimé et ses articles archivés.\n";
                return true;
            }
        }

        echo "❌ Utilisateur non trouvé.\n";
        return false;
    }

    // ========== GESTION DES ARTICLES ==========
    public function createArticle(string $title, string $content, array $categoryIds): ?Article {
        if (!$this->currentUser || !$this->currentUser->canCreateArticle()) {
            echo "❌ Permission refusée.\n";
            return null;
        }

        if (empty($categoryIds)) {
            echo "❌ Au moins une catégorie est requise.\n";
            return null;
        }

        $article = new Article(
            $this->nextArticleId++,
            $title,
            $content,
            $this->currentUser->getId()
        );

        foreach ($categoryIds as $catId) {
            $article->addCategory($catId);
        }

        $this->articles[] = $article;
        echo "✅ Article créé avec succès (ID: {$article->getId()}).\n";
        return $article;
    }

    public function listArticles(?string $filter = null, $filterValue = null): void {
        echo "\n" . str_repeat("=", 100) . "\n";
        echo "LISTE DES ARTICLES\n";
        echo str_repeat("=", 100) . "\n";
        printf("%-5s %-40s %-15s %-15s %-10s\n", "ID", "Titre", "Auteur", "Statut", "Catégories");
        echo str_repeat("-", 100) . "\n";

        $filteredArticles = $this->articles;

        // Appliquer les filtres
        if ($filter === 'author' && $filterValue !== null) {
            $filteredArticles = array_filter($filteredArticles, function($article) use ($filterValue) {
                return $article->getAuthorId() === $filterValue;
            });
        } elseif ($filter === 'status' && $filterValue !== null) {
            $filteredArticles = array_filter($filteredArticles, function($article) use ($filterValue) {
                return $article->getStatus() === $filterValue;
            });
        }

        foreach ($filteredArticles as $article) {
            $author = $this->getUserById($article->getAuthorId());
            $authorName = $author ? $author->getUsername() : 'Unknown';
            $catCount = count($article->getCategoryIds());
            
            printf("%-5d %-40s %-15s %-15s %-10d\n", 
                $article->getId(), 
                substr($article->getTitle(), 0, 40),
                substr($authorName, 0, 15),
                $article->getStatus(),
                $catCount
            );
        }
        echo str_repeat("=", 100) . "\n\n";
    }

    public function viewArticle(int $articleId): void {
        $article = $this->getArticleById($articleId);
        if (!$article) {
            echo "❌ Article non trouvé.\n";
            return;
        }

        $author = $this->getUserById($article->getAuthorId());
        
        echo "\n" . str_repeat("=", 80) . "\n";
        echo "ARTICLE #" . $article->getId() . "\n";
        echo str_repeat("=", 80) . "\n";
        echo "Titre: " . $article->getTitle() . "\n";
        echo "Auteur: " . ($author ? $author->getUsername() : 'Unknown') . "\n";
        echo "Statut: " . $article->getStatus() . "\n";
        echo "Créé le: " . $article->getCreatedAt()->format('Y-m-d H:i:s') . "\n";
        if ($article->getPublishedAt()) {
            echo "Publié le: " . $article->getPublishedAt()->format('Y-m-d H:i:s') . "\n";
        }
        echo "\nCatégories:\n";
        foreach ($article->getCategoryIds() as $catId) {
            $cat = $this->getCategoryById($catId);
            if ($cat) {
                echo "  - " . $cat->getName() . "\n";
            }
        }
        echo "\nContenu:\n";
        echo str_repeat("-", 80) . "\n";
        echo $article->getContent() . "\n";
        echo str_repeat("=", 80) . "\n\n";
    }

    public function updateArticle(int $articleId, ?string $title = null, ?string $content = null): bool {
        $article = $this->getArticleById($articleId);
        if (!$article) {
            echo "❌ Article non trouvé.\n";
            return false;
        }

        if (!$this->currentUser || !$this->currentUser->canModifyArticle($article)) {
            echo "❌ Permission refusée.\n";
            return false;
        }

        if ($title !== null) {
            $article->setTitle($title);
        }
        if ($content !== null) {
            $article->setContent($content);
        }

        echo "✅ Article mis à jour.\n";
        return true;
    }

    public function deleteArticle(int $articleId): bool {
        $article = $this->getArticleById($articleId);
        if (!$article) {
            echo "❌ Article non trouvé.\n";
            return false;
        }

        if (!$this->currentUser || !$this->currentUser->canModifyArticle($article)) {
            echo "❌ Permission refusée.\n";
            return false;
        }

        foreach ($this->articles as $key => $art) {
            if ($art->getId() === $articleId) {
                unset($this->articles[$key]);
                $this->articles = array_values($this->articles);
                echo "✅ Article supprimé.\n";
                return true;
            }
        }

        return false;
    }

    public function publishArticle(int $articleId): bool {
        $article = $this->getArticleById($articleId);
        if (!$article) {
            echo "❌ Article non trouvé.\n";
            return false;
        }

        if (!$this->currentUser || !$this->currentUser->canModifyArticle($article)) {
            echo "❌ Permission refusée.\n";
            return false;
        }

        if ($article->publish()) {
            echo "✅ Article publié avec succès.\n";
            return true;
        } else {
            echo "❌ L'article doit avoir au moins une catégorie pour être publié.\n";
            return false;
        }
    }

    // ========== GESTION DES CATÉGORIES ==========
    public function createCategory(string $name, string $description = "", ?int $parentId = null): ?Category {
        if (!$this->currentUser || !$this->currentUser->canManageCategories()) {
            echo "❌ Permission refusée.\n";
            return null;
        }

        // Vérifier l'unicité du nom au même niveau
        foreach ($this->categories as $cat) {
            if ($cat->getName() === $name && $cat->getParentId() === $parentId) {
                echo "❌ Une catégorie avec ce nom existe déjà à ce niveau.\n";
                return null;
            }
        }

        $category = new Category($this->nextCategoryId++, $name, $description, $parentId);
        $this->categories[] = $category;
        echo "✅ Catégorie créée avec succès (ID: {$category->getId()}).\n";
        return $category;
    }

    public function listCategories(): void {
        echo "\n" . str_repeat("=", 80) . "\n";
        echo "ARBORESCENCE DES CATÉGORIES\n";
        echo str_repeat("=", 80) . "\n";
        
        $this->displayCategoryTree(null, 0);
        
        echo str_repeat("=", 80) . "\n\n";
    }

    private function displayCategoryTree(?int $parentId, int $level): void {
        foreach ($this->categories as $category) {
            if ($category->getParentId() === $parentId) {
                $indent = str_repeat("  ", $level);
                $articleCount = $this->countArticlesInCategory($category->getId());
                echo $indent . "├─ [" . $category->getId() . "] " . $category->getName() . " ($articleCount articles)\n";
                $this->displayCategoryTree($category->getId(), $level + 1);
            }
        }
    }

    private function countArticlesInCategory(int $categoryId): int {
        $count = 0;
        foreach ($this->articles as $article) {
            if (in_array($categoryId, $article->getCategoryIds())) {
                $count++;
            }
        }
        return $count;
    }

    public function deleteCategory(int $categoryId): bool {
        if (!$this->currentUser || !$this->currentUser->canManageCategories()) {
            echo "❌ Permission refusée.\n";
            return false;
        }

        // Vérifier s'il y a des articles
        $articleCount = $this->countArticlesInCategory($categoryId);
        if ($articleCount > 0) {
            echo "❌ Impossible de supprimer: $articleCount article(s) utilisent cette catégorie.\n";
            return false;
        }

        foreach ($this->categories as $key => $cat) {
            if ($cat->getId() === $categoryId) {
                unset($this->categories[$key]);
                $this->categories = array_values($this->categories);
                echo "✅ Catégorie supprimée.\n";
                return true;
            }
        }

        echo "❌ Catégorie non trouvée.\n";
        return false;
    }

    // ========== MÉTHODES UTILITAIRES ==========
    private function getUserById(int $id): ?User {
        foreach ($this->users as $user) {
            if ($user->getId() === $id) {
                return $user;
            }
        }
        return null;
    }

    private function getArticleById(int $id): ?Article {
        foreach ($this->articles as $article) {
            if ($article->getId() === $id) {
                return $article;
            }
        }
        return null;
    }

    private function getCategoryById(int $id): ?Category {
        foreach ($this->categories as $category) {
            if ($category->getId() === $id) {
                return $category;
            }
        }
        return null;
    }

    public function getCategories(): array {
        return $this->categories;
    }
}

// ==================== INTERFACE CONSOLE ====================
class ConsoleInterface {
    private BlogCMS $cms;
    private bool $running = true;

    public function __construct() {
        $this->cms = new BlogCMS();
    }

    public function run(): void {
        $this->showWelcome();
        
        while ($this->running) {
            if (!$this->cms->getCurrentUser()) {
                $this->showLoginMenu();
            } else {
                $this->showMainMenu();
            }
        }

        echo "\n👋 Au revoir!\n";
    }

    private function showWelcome(): void {
        echo "\n";
        echo "╔═══════════════════════════════════════════════════════╗\n";
        echo "║                                                       ║\n";
        echo "║           BLOGCMS CONSOLE EDITION v1.0                ║\n";
        echo "║                                                       ║\n";
        echo "╚═══════════════════════════════════════════════════════╝\n";
        echo "\n";
    }

    private function showLoginMenu(): void {
        echo "\n--- AUTHENTIFICATION ---\n";
        echo "Compte par défaut: admin / admin123\n\n";
        
        $username = $this->prompt("Username: ");
        $password = $this->prompt("Password: ");

        if ($this->cms->login($username, $password)) {
            $user = $this->cms->getCurrentUser();
            echo "\n✅ Connexion réussie! Bienvenue " . $user->getUsername() . " (" . $user->getRole() . ")\n";
            $this->pause();
        } else {
            echo "\n❌ Identifiants incorrects.\n";
            $this->pause();
        }
    }

    private function showMainMenu(): void {
        $user = $this->cms->getCurrentUser();
        
        echo "\n" . str_repeat("=", 60) . "\n";
        echo "BLOGCMS CONSOLE - Connecté: " . $user->getUsername() . " (" . $user->getRole() . ")\n";
        echo str_repeat("=", 60) . "\n";
        
        echo "1. Gestion des articles\n";
        echo "2. Gestion des catégories\n";
        
        if ($user->canManageUsers()) {
            echo "3. Gestion des utilisateurs\n";
        }
        
        echo "0. Déconnexion\n";
        echo str_repeat("=", 60) . "\n";

        $choice = $this->prompt("Votre choix: ");

        switch ($choice) {
            case '1':
                $this->articleMenu();
                break;
            case '2':
                $this->categoryMenu();
                break;
            case '3':
                if ($user->canManageUsers()) {
                    $this->userMenu();
                }
                break;
            case '0':
                $this->cms->logout();
                echo "\n✅ Déconnexion réussie.\n";
                $this->pause();
                break;
        }
    }

    private function articleMenu(): void {
        echo "\n--- GESTION DES ARTICLES ---\n";
        echo "1. Créer un article\n";
        echo "2. Lister mes articles\n";
        echo "3. Lister tous les articles\n";
        echo "4. Voir un article\n";
        echo "5. Modifier un article\n";
        echo "6. Publier un article\n";
        echo "7. Supprimer un article\n";
        echo "0. Retour\n";

        $choice = $this->prompt("Votre choix: ");

        switch ($choice) {
            case '1':
                $this->createArticle();
                break;
            case '2':
                $user = $this->cms->getCurrentUser();
                $this->cms->listArticles('author', $user->getId());
                $this->pause();
                break;
            case '3':
                $this->cms->listArticles();
                $this->pause();
                break;
            case '4':
                $id = (int)$this->prompt("ID de l'article: ");
                $this->cms->viewArticle($id);
                $this->pause();
                break;
            case '5':
                $this->updateArticle();
                break;
            case '6':
                $id = (int)$this->prompt("ID de l'article à publier: ");
                $this->cms->publishArticle($id);
                $this->pause();
                break;
            case '7':
                $id = (int)$this->prompt("ID de l'article à supprimer: ");
                echo "⚠️  Êtes-vous sûr? (oui/non): ";
                $confirm = trim(fgets(STDIN));
                if ($confirm === 'oui') {
                    $this->cms->deleteArticle($id);
                }
                $this->pause();
                break;
        }
    }

    private function createArticle(): void {
        echo "\n--- CRÉER UN ARTICLE ---\n";
        $title = $this->prompt("Titre: ");
        echo "Contenu (tapez END sur une ligne seule pour terminer):\n";
        
        $content = "";
        while (true) {
            $line = trim(fgets(STDIN));
            if ($line === "END") break;
            $content .= $line . "\n";
        }

        // Afficher les catégories disponibles
        echo "\nCatégories disponibles:\n";
        foreach ($this->cms->getCategories() as $cat) {
            echo "  [" . $cat->getId() . "] " . $cat->getName() . "\n";
        }

        $catIds = $this->prompt("IDs des catégories (séparés par des virgules): ");
        $categoryIds = array_map('intval', explode(',', $catIds));

        $this->cms->createArticle($title, trim($content), $categoryIds);
        $this->pause();
    }

    private function updateArticle(): void {
        $id = (int)$this->prompt("ID de l'article à modifier: ");
        $title = $this->prompt("Nouveau titre (laisser vide pour ne pas changer): ");
        
        echo "Nouveau contenu (tapez END pour terminer, ou laisser vide pour ne pas changer):\n";
        $content = "";
        $firstLine = trim(fgets(STDIN));
        
        if ($firstLine !== "") {
            $content = $firstLine . "\n";
            while (true) {
                $line = trim(fgets(STDIN));
                if ($line === "END") break;
                $content .= $line . "\n";
            }
        }

        $this->cms->updateArticle(
            $id,
            $title !== "" ? $title : null,
            $content !== "" ? trim($content) : null
        );
        $this->pause();
    }

    private function categoryMenu(): void {
        echo "\n--- GESTION DES CATÉGORIES ---\n";
        echo "1. Créer une catégorie\n";
        echo "2. Lister les catégories\n";
        echo "3. Supprimer une catégorie\n";
        echo "0. Retour\n";

        $choice = $this->prompt("Votre choix: ");

        switch ($choice) {
            case '1':
                $name = $this->prompt("Nom de la catégorie: ");
                $description = $this->prompt("Description: ");
                $parentId = $this->prompt("ID catégorie parente (laisser vide si aucune): ");
                $parentId = $parentId === "" ? null : (int)$parentId;
                $this->cms->createCategory($name, $description, $parentId);
                $this->pause();
                break;
            case '2':
                $this->cms->listCategories();
                $this->pause();
                break;
            case '3':
                $id = (int)$this->prompt("ID de la catégorie à supprimer: ");
                $this->cms->deleteCategory($id);
                $this->pause();
                break;
        }
    }

    private function userMenu(): void {
        echo "\n--- GESTION DES UTILISATEURS ---\n";
        echo "1. Créer un utilisateur\n";
        echo "2. Lister les utilisateurs\n";
        echo "3. Supprimer un utilisateur\n";
        echo "0. Retour\n";

        $choice = $this->prompt("Votre choix: ");

        switch ($choice) {
            case '1':
                $username = $this->prompt("Username: ");
                $email = $this->prompt("Email: ");
                $password = $this->prompt("Password: ");
                echo "Rôles disponibles: visitor, author, editor, admin\n";
                $role = $this->prompt("Rôle: ");
                $extra = "";
                if ($role === 'author') {
                    $extra = $this->prompt("Bio: ");
                } elseif ($role === 'editor') {
                    $extra = $this->prompt("Niveau (junior/senior/chief): ");
                }
                $this->cms->createUser($username, $email, $password, $role, $extra);
                $this->pause();
                break;
            case '2':
                $this->cms->listUsers();
                $this->pause();
                break;
            case '3':
                $id = (int)$this->prompt("ID de l'utilisateur à supprimer: ");
                echo "⚠️  Êtes-vous sûr? (oui/non): ";
                $confirm = trim(fgets(STDIN));
                if ($confirm === 'oui') {
                    $this->cms->deleteUser($id);
                }
                $this->pause();
                break;
        }
    }

    private function prompt(string $message): string {
        echo $message;
        return trim(fgets(STDIN));
    }

    private function pause(): void {
        echo "\nAppuyez sur Entrée pour continuer...";
        fgets(STDIN);
    }
}

// ==================== POINT D'ENTRÉE ====================
$console = new ConsoleInterface();
$console->run();