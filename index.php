<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>To do list</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<body>
<div class="container w-50 mx-auto">
    <h2 class="mt-5 mb-5 text-center">To Do List</h2>
    <form method="post" class="mb-5">
        <label for="task">Enter task:</label>
        <input type="text" name="task" placeholder="text" required>
        <input type="submit" name="submit" value="Add Task">
    </form>
    <?php
    session_start();

    if (!isset($_SESSION["taskList"])) {
        $_SESSION["taskList"] = [];
    }

    if (isset($_POST["submit"])) {
        $task = array(
            "description" => $_POST["task"],
        );
        $_SESSION["taskList"][] = $task;
    }

    echo "<h3>Tasks:</h3>";
    if (count($_SESSION["taskList"]) > 0) {
        echo "<table class='table'>";
        echo "<tr><th>Task</th></tr>";
        foreach ($_SESSION["taskList"] as $key => $task) {
            echo "<tr>";
            echo "<td>" . $task["description"] . "</td>";
            echo "<td>
            <form method='post'>
                <input type='hidden' name='taskId' value='" . $key . "'>
            </form></td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<form method='post'>";
        echo "<input type='submit' name='deleteAll' value='Delete All' class='btn btn-danger mr-2'>";
        echo "<input type='submit' name='readyAll' value='Ready All Tasks' class='btn btn-success'>";
        echo "</form>";
    } else {
        echo "<p>No tasks found.</p>";
    }

    if (!isset($_SESSION["readyTaskList"])) {
        $_SESSION["readyTaskList"] = [];
    }

    if (isset($_POST["readyTask"])) {
        $taskId = $_POST["taskId"];
        $task = $_SESSION["taskList"][$taskId];
        $task["status"] = "Ready";
        $_SESSION["readyTaskList"][] = $task;
        unset($_SESSION["taskList"][$taskId]);
        $_SESSION["taskList"] = array_values($_SESSION["taskList"]);
    }

    if (isset($_SESSION["readyTaskList"]) && count($_SESSION["readyTaskList"]) > 0) {
        echo "<h3 class='mt-5'>Ready/unready tasks:</h3>";
        echo "<table class='table'>";
        echo "<tr><th>Task</th><th>Status</th><th>Action</th></tr>";
        foreach ($_SESSION["readyTaskList"] as $key => $task) {
            echo "<tr>";
            echo "<td>" . $task["description"] . "</td>";
            echo "<td>";
            echo isset($task["status"]) ? $task["status"] : "Unready";
            echo "</td>";
            echo "<td>
                     <form method='post'>
                        <input type='hidden' name='taskId' value='" . $key . "'>
                        <input type='submit' name='toggleStatus' value='" . (isset($task["status"]) && $task["status"] == "Ready" ? "Unready" : "Ready") . "' class='btn btn-secondary'>
                        <input type='submit' name='deleteReady' value='Delete' class='btn btn-danger'>
                    </form>
                 </td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='mt-5'>No ready/unready tasks found.</p>";
    }

    if (isset($_POST["toggleStatus"])) {
        $taskId = $_POST["taskId"];
        if (isset($_SESSION["readyTaskList"][$taskId]["status"])) {
            $_SESSION["readyTaskList"][$taskId]["status"] = ($_SESSION["readyTaskList"][$taskId]["status"] == "Ready") ? "Unready" : "Ready";
        }
    }

    if (isset($_POST["deleteReady"])) {
        $taskId = $_POST["taskId"];
        unset($_SESSION["readyTaskList"][$taskId]);
        $_SESSION["readyTaskList"] = array_values($_SESSION["readyTaskList"]);
    }

    if (isset($_POST["deleteTask"])) {
        $taskId = $_POST["taskId"];
        unset($_SESSION["taskList"][$taskId]);
        $_SESSION["taskList"] = array_values($_SESSION["taskList"]);
    }

    if (isset($_POST["deleteAll"])) {
        $_SESSION["taskList"] = [];
    }

    if (isset($_POST["readyAll"])) {
        $_SESSION["readyTaskList"] = array_merge($_SESSION["readyTaskList"], $_SESSION["taskList"]);
        foreach ($_SESSION["readyTaskList"] as &$task) {
            $task["status"] = "Ready";
        }
        $_SESSION["taskList"] = [];
    }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>