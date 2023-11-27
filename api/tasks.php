<?php
header('Content-Type: application/json');

// Include database connection
require 'database.php';
$conexion = new Conexion();
$conn = $conexion->obtenerConexion();
$sql = "SELECT * FROM task";

$rawData = file_get_contents("php://input");

// Decode the JSON data
$request = json_decode($rawData, true);


$action = isset($_POST['action']) ? $_POST['action'] : '';
//$action = isset($request['action']) ? $request['action'] : '';

switch ($action) {
    case 'addTask':
        addTask($conn);
        break;
    case 'editTask':
        editTask($conn);
        break;
    case 'deleteTask':
        deleteTask($conn);
        break;
    case 'getTasks':
        echo obtenerTareas($conn);
        break;
    default:
    echo json_encode(['error' => 'Invalid action: ' . $action]);
}


// Function to add a new task
function addTask($conn)
{

    $data = json_decode(file_get_contents("php://input"), true);

    global $conn;

    // Retrieve data from the request
    $data = json_decode($_POST['data'], true);
    $task = $data['task'];
    $assigned_to = $data['assigned_to'];
    $due_date = $data['due_date'];
    $priority = $data['priority'];

    // Perform database operation (replace with your database logic)
    $stmt = $conn->prepare("INSERT INTO task (name, assigned_to, due_date, priority) VALUES (:name, :assigned_to, :due_date, :priority)");
    $stmt->bindParam(':name', $task);
    $stmt->bindParam(':assigned_to', $assigned_to);
    $stmt->bindParam(':due_date', $due_date);
    $stmt->bindParam(':priority', $priority);
    $stmt->execute();

    echo json_encode(['success' => true]);
}

// Function to edit a task
function editTask($conn)
{
    $data = json_decode(file_get_contents("php://input"), true);

    global $conn;

    // Retrieve data from the request
    $data = json_decode($_POST['data'], true);
    $id = $data['id'];
    $task = $data['task'];

    // Perform database operation (replace with your database logic)
    $stmt = $conn->prepare("UPDATE task SET name = :name WHERE id = :id");
    $stmt->bindParam(':name', $task);
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    echo json_encode(['success' => true]);
}

// Function to delete a task
function deleteTask($conn)
{

    $data = json_decode(file_get_contents("php://input"), true);

    global $conn;

    // Retrieve data from the request
    $data = json_decode($_POST['data'], true);
    $id = $data['id'];

    // Perform database operation (replace with your database logic)
    $stmt = $conn->prepare("DELETE FROM task WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    echo json_encode(['success' => true]);
    
}

// Function to get tasks with filters
function getTasks($conn)
{

    $rawData = file_get_contents("php://input");

    $data = json_decode($rawData, true);

    

    global $conn;

    // Retrieve data from the request
    $data = json_decode($_POST['data'], true);
    $priority_filter = $data['priority_filter'];
    $date_filter = $data['date_filter'];

    // Build the SQL query based on filters
    $sql = "SELECT * FROM task WHERE 1";

    if (!empty($priority_filter) && $priority_filter != "All") {
        $sql .= " AND (priority = :priority OR priority IS NULL)";
    }
    if (!empty($date_filter)) {
        $sql .= " AND due_date = :due_date";
    }

    // Prepare and execute the SQL query
    $stmt = $conn->prepare($sql);

    // Bind parameters if they exist
    if (!empty($priority_filter) && $priority_filter != "All") {
        $stmt->bindParam(':priority', $priority_filter);
    }
    if (!empty($date_filter)) {
        $stmt->bindParam(':due_date', $date_filter);
    }

    $stmt->execute();

    // Fetch the filtered tasks
    $todoItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($todoItems);
}

function obtenerTareas($conn){
    // Get the raw POST data
    $rawData = file_get_contents("php://input");

    // Decode the JSON data
    $data = json_decode($rawData, true);

    $sql = "SELECT * FROM task";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $todoItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the JSON instead of echoing it
    return json_encode($todoItems);
}
?>
