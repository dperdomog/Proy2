<?php
require 'api/database.php';

$todoItems = [];

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task = isset($_POST["task"]) ? $_POST["task"] : "";
    $assigned_to = isset($_POST["assigned_to"]) ? $_POST["assigned_to"] : "";
    $due_date = isset($_POST["due_date"]) ? $_POST["due_date"] : "";
    $priority = isset($_POST["priority"]) ? $_POST["priority"] : "";

    if (isset($_POST["add"])) {
        // Llamada a la API para agregar una nueva tarea
        $response = callApi('addTask', [
            'task' => $task,
            'assigned_to' => $assigned_to,
            'due_date' => $due_date,
            'priority' => $priority
        ]);
    } elseif (isset($_POST["edit"])) {
        $id = $_POST["edit_id"];
        $task = $_POST["edited_task"];
        
        // Llamada a la API para editar una tarea existente
        $response = callApi('editTask', [
            'id' => $id,
            'task' => $task
        ]);
    } elseif (isset($_POST["delete"])) {
        $id = $_POST["delete_id"];
        
        // Llamada a la API para eliminar una tarea
        $response = callApi('deleteTask', [
            'id' => $id
        ]);
    } elseif (isset($_POST["filter"])) {
        $priority_filter = $_POST["priority_filter"];
        $date_filter = $_POST["date_filter"];

        // Llamada a la API para obtener tareas filtradas
        $response = callApi('getTasks', [
            'priority_filter' => $priority_filter,
            'date_filter' => $date_filter
        ]);

        // Decodificar la respuesta JSON
        $todoItems = json_decode($response, true);
    }
}

// Si no es una solicitud POST o si se ejecuta por primera vez, obtener todas las tareas sin filtrar
$response = callApi('getTasks', [
    'priority_filter' => 'All', // Mostrar todas las prioridades
    'date_filter' => '' // Sin filtro de fecha
]);

// Decodificar la respuesta JSON
$todoItems = json_decode($response, true);

// Function to make API calls
function callApi($action, $data)
{
    $apiUrl = 'http://localhost/checklistphp/api/tasks.php';

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'action' => $action,
        'data' => json_encode($data)
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo 'Error en la llamada a la API: ' . curl_error($ch);
    }

    curl_close($ch);

    return $response;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checklist</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://kit.fontawesome.com/a57ee06e6c.js" crossorigin="anonymous"></script>

</head>

<body>
    <div class="mainForm">
        <div class="formItems">
            <h2>Checklist Task  </h2>
            
            <button class="addNewTodo"><i class="fa-solid fa-plus"></i></button>

            <!-- Add Form -->
            <form class="todoForm" method="post">
                <input type="text" name="task" placeholder="Add new item" required>
                <input type="text" name="assigned_to" placeholder="Assigned to">
                <input type="date" name="due_date" placeholder="Due date">
                <select name="priority">
                    <option value="High">Alta</option>
                    <option value="Medium">Medio</option>
                    <option value="Low">Baja</option>
                </select>
                <button type="submit" name="add">Add</button>
            </form>
        </div>
    </div>

    <div class="container">
        <form method="post">
            <label for="priority_filter">Filtro de prioridad:</label>
            <select name="priority_filter">
                <option value="All">All</option>
                <option value="High">Alta</option>
                <option value="Medium">Medio</option>
                <option value="Low">Baja</option>
            </select>

            <label for="date_filter">Filtro de fecha:</label>
            <input type="date" name="date_filter">

            <button type="submit" name="filter">Apply Filter</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Done</th>
                    <th>Task</th>
                    <th>Asignado a</th>
                    <th>Fecha de entrega</th>
                    <th>Prioridad</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($todoItems as $item) : ?>
                    <tr class="<?php echo $item["id"]; ?>">
                        <td style="white-space:nowrap;" class="checky">
                            <input type="checkbox" name="settings" data-button-id="<?php echo $item["id"]; ?>" />
                        </td>
                        <td>
                            <div class="todoText" data-text-id="<?php echo $item["id"]; ?>">
                                <?php echo $item["name"]; ?>
                            </div>
                        </td>
                        <td><?php echo $item["assigned_to"]; ?></td>
                        <td><?php echo $item["due_date"]; ?></td>
                        <td><?php echo $item["priority"]; ?></td>
                        <td>
                            <button type="button" class="commonClass" data-button-id="<?php echo $item["id"]; ?>" novalidate><i class="fa-solid fa-pencil"></i></button>

                            <!-- The Modal -->
                            <div class="myModal" data-modal-id="<?php echo $item["id"]; ?>">
                                <!-- Modal content -->
                                <div class="modal-content">
                                    <span class="close" data-span-id="<?php echo $item["id"]; ?>">&times;</span>
                                    <p>Enter your information:</p>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="edit_id" value="<?php echo $item["id"]; ?>">
                                        <input type="text" id="inputField" name="edited_task" placeholder="Edit task">
                                        <button type="submit" id="submitBtn" name="edit" <?php echo $item["id"]; ?> novalidate>Submit</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                        <td>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="delete_id" value="<?php echo $item["id"]; ?>">
                                <button type="submit" name="delete"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <script src="script.js"></script>
    </div>
</body>

</html>
