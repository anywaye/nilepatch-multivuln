<?php
// Helper functions
require_once __DIR__ . '/../config/db.php';

function searchUsers($term) {
    $db = getDB();
    // Use prepared statement to prevent SQL injection
    $like = "%" . $term . "%";
    $stmt = $db->prepare("SELECT * FROM users WHERE username LIKE ? OR email LIKE ?");
    if (!$stmt) {
        return [];
    }
    $stmt->bind_param("ss", $like, $like);
    $stmt->execute();
    $result = $stmt->get_result();
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    return $users;
}

function displayUser($user) {
    // Escape output to prevent XSS
    $username = htmlspecialchars($user['username'] ?? '', ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($user['email'] ?? '', ENT_QUOTES, 'UTF-8');
    $bio = htmlspecialchars($user['bio'] ?? '', ENT_QUOTES, 'UTF-8');
    echo "<div class='user'>";
    echo "<h3>" . $username . "</h3>";
    echo "<p>" . $email . "</p>";
    echo "<p>" . $bio . "</p>";
    echo "</div>";
}

function loadPage($page) {
    // Validate page name and restrict to pages directory to prevent LFI
    $page = basename((string)$page);
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $page)) {
        echo "Invalid page";
        return;
    }
    $baseDir = realpath(__DIR__ . '/../pages');
    if ($baseDir === false) {
        echo "Page not found";
        return;
    }
    $file = $baseDir . '/' . $page . '.php';
    $realFile = realpath($file);
    if ($realFile !== false && strpos($realFile, $baseDir . DIRECTORY_SEPARATOR) === 0 && is_file($realFile)) {
        include $realFile;
    } else {
        echo "Page not found";
    }
}

function deserializeData($data) {
    // Use JSON instead of insecure PHP serialization
    $decoded = json_decode($data, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return null;
    }
    return $decoded;
}

function savePreference($key, $value, $csrfToken = null) {
    // Require CSRF protection
    if ($csrfToken === null) {
        $csrfToken = $_POST['csrf_token'] ?? $_REQUEST['csrf_token'] ?? '';
    }
    if (!verifyCsrfToken($csrfToken)) {
        throw new Exception('Invalid CSRF token');
    }
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO preferences (user_id, pref_key, pref_value) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE pref_value = ?");
    if (!$stmt) {
        return;
    }
    $stmt->bind_param("isss", $_SESSION['user_id'], $key, $value, $value);
    $stmt->execute();
}
