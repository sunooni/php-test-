<?php
// Подключение к базе данных
$host = 'localhost';
$db = 'todo_list';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Создание таблицы, если её нет
$conn->query("CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    completed BOOLEAN DEFAULT FALSE
)");

// Функция добавления задачи
function addTask($title) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO tasks (title) VALUES (?)");
    $stmt->bind_param("s", $title);
    $stmt->execute();
}

// Функция получения задач
function getTasks() {
    global $conn;
    return $conn->query("SELECT * FROM tasks");
}

// Функция удаления задачи
function deleteTask($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Функция обновления статуса задачи
function updateTaskStatus($id, $completed) {
    global $conn;
    $stmt = $conn->prepare("UPDATE tasks SET completed = ? WHERE id = ?");
    $stmt->bind_param("ii", $completed, $id);
    $stmt->execute();
}

// Пример использования:
addTask("Первая задача");
$tasks = getTasks();

while ($row = $tasks->fetch_assoc()) {
    echo $row['title'] . ($row['completed'] ? " (выполнена)" : "") . "<br>";
}
?>