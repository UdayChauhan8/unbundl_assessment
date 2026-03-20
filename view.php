<!DOCTYPE html>
<html>

<head>
    <title>Unbundl Assessment</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header style="text-align: left; margin-bottom: 20px;">
        <div
            style="display: inline-flex; align-items: center; gap: 8px; background: white; padding: 6px 12px; border-radius: 6px;">
            <img src="https://unbundl.com/cdn/shop/files/Logo_83b3f08f-7fa6-460e-b0f6-3a7bdd540472.webp?v=1756893840&width=40"
                alt="Unbundl Symbol" style="height: 30px;">
            <img src="https://unbundl.com/cdn/shop/files/unbundl_logo_blue.png" alt="Unbundl Text"
                style="height: 22px;">
        </div>
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