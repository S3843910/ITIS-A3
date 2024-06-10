<?php
if (!isset($_GET['search'])) {
    exit('Invalid request');
}

$search = $_GET['search'];

$db = new SQLite3('../database/database.db');

$books = array();
$stmt = $db->prepare("SELECT * FROM Books WHERE name LIKE :search OR genre LIKE :search");
$stmt->bindValue(':search', "%$search%", SQLITE3_TEXT);
$result = $stmt->execute();

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $books[] = array(
        'name' => $row['name'],
        'genre' => $row['genre'],
        'filename' => $row['filename']
    );
}

header('Content-Type: application/json');
echo json_encode($books);

?>
