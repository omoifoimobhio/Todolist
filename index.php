<?php
$errors = "";
$update_id = $_GET['updated_id'] ?? 0;
$update_task = $_GET['updated_task'] ?? "";

//connect to database
$db = mysqli_connect('localhost', 'root', '', 'new_todo');
if (!$db) {
    die("connection failed: " . mysqli_connect_error());
}

echo "connected successfully";


if (isset($_POST['add'])) {
    $task = $_POST['task'];
    if (empty($task)) {
        $errors = "You must fill in the task";
    }else {
        mysqli_query($db, "INSERT INTO tasks (name) VALUES ('$task')");
        header('location: index.php');
    }
}
//Update task
if (isset($_POST['update'])) {
    $id = $update_id;
    $task = $_POST['task'];
    $errors = $task;
    $sql = "UPDATE tasks SET name=$task WHERE tasks.id=$id";
    // $errors = $sql;
    mysqli_query($db, $sql);
    if (mysqli_query($db, $sql)) {
        echo "Record updated successfully";
      } else {
        echo "Error updating record: " . mysqli_error($db);
      }
    header('location: index.php');
}

    //delete task
if (isset($_GET['del_task'])) {
        $id = $_GET['del_task'];
        mysqli_query($db, "DELETE FROM tasks WHERE id=$id");
        header('location: index.php');
}


$tasks = mysqli_query($db, "SELECT * FROM tasks");

?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
</head>
<body>
    <div class="heading">
        <h2>Todo List</h2>
    </div>
    <?php if ($update_id != 0) {?>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <?php if (isset($errors)) { ?>
            <p><?php echo $errors; ?></p>
        <?php } ?>
        <input type="text" name="task" class="task_input" value=<?php echo $update_task; ?>>
        <button type="submit" class="task_btn" name="update">Update Task</button>
    </form>
    <?php } else { ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <?php if (isset($errors)) { ?>
                <p><?php echo $errors; ?></p>
            <?php } ?>
            <input type="text" name="task" class="task_input" >
            <button type="submit" class="task_btn" name="add">Add Task</button>
        </form>
    <?php } ?>
    <table>
        <thead>
            <tr>
                <td>N</td>
                <th>Task</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php  while ($row = mysqli_fetch_array($tasks,MYSQLI_ASSOC)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td class="task"><?php echo $row['name']; ?></td>
                    <td class="delete">
                        <a href="index.php?updated_id=<?php echo $row['id']?>&updated_task=<?php echo $row['name']; ?>">Edit</a>
                        <a href="index.php?del_task=<?php echo $row['id']; ?>">x</a>
                    </td>
                </tr>
            <?php } ?>
            
            
        </tbody>
    </table>
</body>

</html>