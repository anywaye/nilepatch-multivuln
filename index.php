<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

$search = $_GET['search'] ?? '';
$page = $_GET['page'] ?? 'home';
?>
<!DOCTYPE html>
<html>
<head><title>Complex Test App</title></head>
<body>
    <h1>Complex Test App</h1>
    
    <?php if (isLoggedIn()): ?>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?> (<?php echo htmlspecialchars($_SESSION['role'], ENT_QUOTES, 'UTF-8'); ?>)</p>
        <a href="/logout.php">Logout</a>
    <?php else: ?>
        <a href="/login.php">Login</a>
    <?php endif; ?>
    
    <form method="GET" action="/">
        <input type="text" name="search" value="<?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?>" placeholder="Search users">
        <button type="submit">Search</button>
    </form>
    
    <?php if ($search): ?>
        <h2>Search Results</h2>
        <?php
        $users = searchUsers($search);
        foreach ($users as $user) {
            displayUser($user);
        }
        ?>
    <?php endif; ?>
    
    <div class="content">
        <?php loadPage($page); ?>
    </div>
</body>
</html>
