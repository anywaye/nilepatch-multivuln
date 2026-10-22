<?php
// Helper functions - VULNERABLE: XSS, file inclusion, insecure deserialization

function searchUsers($term) {
    $db = getDB();
    // VULNERABLE: SQL injection
    $query = "SELECT * FROM users WHERE username LIKE '%$term%' OR email LIKE '%$term%'";
    $result = $db->query($query);
    $users = [];
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    return $users;
}

function displayUser($user) {
    // VULNERABLE: XSS - no output escaping
    echo "<div class='user'>";
    echo "<h3>" . $user['username'] . "</h3>";
    echo "<p>" . $user['email'] . "</p>";
    echo "<p>" . $user['bio'] . "</p>";
    echo "</div>";
}

function loadPage($page) {
    // VULNERABLE: Local file inclusion
    $file = "pages/" . $page . ".php";
    if (file_exists($file)) {
        include($file);
    } else {
        echo "Page not found";
    }
}

function deserializeData($data) {
    // VULNERABLE: Insecure deserialization
    return unserialize($data);
}

function savePreference($key, $value) {
    // VULNERABLE: No CSRF protection
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO preferences (user_id, pref_key, pref_value) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE pref_value = ?");
    $stmt->bind_param("isss", $_SESSION['user_id'], $key, $value, $value);
    $stmt->execute();
}
