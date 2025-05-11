<?php
session_start();

// Initialize tasks array if it doesn't exist
if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

// Handle form submission
if (isset($_POST['add'])) {
    // Verify all required fields are present
    if (!empty($_POST['taskName']) && !empty($_POST['mm']) && !empty($_POST['dd']) && !empty($_POST['yyyy']) && !empty($_POST['status'])) {
        $new_task = [
            'name' => $_POST['taskName'],
            'date' => $_POST['mm'].'/'.$_POST['dd'].'/'.$_POST['yyyy'],
            'status' => $_POST['status'],
            'color' => $_POST['status'] == 'notStart' ? '#ff9999' :
                      ($_POST['status'] == 'inProg' ? '#99ccff' : '#99ff99')
        ];
        
        array_unshift($_SESSION['tasks'], $new_task);
        
        // Redirect to prevent form resubmission
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }
}

// Handle task deletion
if (isset($_GET['delete'])) {
    $task_index = (int)$_GET['delete'];
    if (isset($_SESSION['tasks'][$task_index])) {
        unset($_SESSION['tasks'][$task_index]);
        // Re-index array after deletion
        $_SESSION['tasks'] = array_values($_SESSION['tasks']);
    }
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP TO-DO LIST</title>
    <link rel="stylesheet" href="./assets/css/toodoo.css">
</head>
<body>
    <div id="header"><img src="./assets/img/HEADER.png" alt="To-Do List Header"></div>

    <div class="container">
        <form class="task-form" method="POST" action="">
            <div class="form-group">
                <label for="taskName">Task Name:</label>
                <input type="text" id="taskName" name="taskName" class="form-input" required>
            </div>

            <div class="form-group">
                <label>When:</label>
                <div class="date-inputs">
                    <input type="text" name="mm" placeholder="MM" maxlength="2" class="form-input small" required>
                    <input type="text" name="dd" placeholder="DD" maxlength="2" class="form-input small" required>
                    <input type="text" name="yyyy" placeholder="YYYY" maxlength="4" class="form-input medium" required>
                </div>
            </div>

            <div class="form-group">
                <label for="status">Status:</label>
                <div class="dropdown-container">
                    <select id="status" name="status" class="status-dropdown" required>
                        <option value="" disabled selected>Select status</option>
                        <option value="notStart">Not Started</option>
                        <option value="inProg">In Progress</option>
                        <option value="done">Done</option>
                    </select>
                </div>
            </div>

            <button type="submit" name="add" class="add-btn">ADD</button>
        </form>

        <div class="task-list-container">
            <?php if (!empty($_SESSION['tasks'])): ?>
                <h2>Your Tasks:</h2>
                <div class="task-items">
                    <?php foreach ($_SESSION['tasks'] as $index => $task): ?>
                        <div class="task-item" style="border-left: 5px solid <?php echo $task['color']; ?>">
                            <div class="task-info">
                                <div class="task-name"><?php echo htmlspecialchars($task['name']); ?></div>
                                <div class="task-date"><?php echo htmlspecialchars($task['date']); ?> • 
                                    <?php echo match($task['status']) {
                                        'notStart' => 'Not Started',
                                        'inProg' => 'In Progress',
                                        'done' => 'Done'
                                    }; ?>
                                </div>
                            </div>
                            <div class="task-actions">
                                <a href="?delete=<?php echo $index; ?>" class="delete-btn">Delete</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">No tasks yet. Add one above!</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>