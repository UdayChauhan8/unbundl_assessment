<!DOCTYPE html>
<html>

<head>
    <title>Unbundl Assessment</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header style="margin-bottom: 40px;">
        <img src="logo.png" alt="Unbundl Logo" style="height: 60px; margin-left: -32px;">
    </header>

    <h2>View Submitted Data </h2>

    <a href="index.php" class="btn btn-add">+ Add New User</a>
    <br><br>

    <?php
    require 'db.php';

    // fetch all users
    $result = $conn->query("SELECT * FROM users ORDER BY id ASC");

    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Action</th>
              </tr>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>
                    <td>' . $row['id'] . '</td>
                    <td>' . htmlspecialchars($row['name']) . '</td>
                    <td>' . htmlspecialchars($row['email']) . '</td>
                    <td>' . htmlspecialchars($row['phone']) . '</td>
                    <td>' . htmlspecialchars($row['address']) . '</td>
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