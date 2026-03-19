<!DOCTYPE html>
<html>

<head>
    <title>View Submitted Data</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h2>View Submitted Data</h2>

    <!-- Link to go back and add a new user -->
    <a href="index.php" class="btn btn-add">+ Add New User</a>

    <?php
    require 'db.php';

    $result = $conn->query("SELECT * FROM users ORDER BY created_at DESC");

    if ($result->num_rows > 0) {
        echo '<table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Created At</th>
                    <th>Action</th>
                </tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>
                    <td>' . $row['id'] . '</td>
                    <td>' . htmlspecialchars($row['name']) . '</td>
                    <td>' . htmlspecialchars($row['email']) . '</td>
                    <td>' . htmlspecialchars($row['phone']) . '</td>
                    <td>' . htmlspecialchars($row['address']) . '</td>
                    <td>' . $row['created_at'] . '</td>
                    <td class="actions">
                        <a href="edit.php?id=' . $row['id'] . '" class="btn btn-edit">Edit</a>
                    </td>
                  </tr>';
        }

        echo '</table>';
    } else {
        echo '<p class="no-data">No users found. Click "+ Add New User" to get started.</p>';
    }

    $conn->close();
    ?>

</body>

</html>