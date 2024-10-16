<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

$servername = "localhost";
$username = "root";  
$password = "";      
$databaseName = "waterpoloclub"; 

$conn = new mysqli($servername, $username, $password, $databaseName);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $position = $_POST['position'];

        $stmt = $conn->prepare("INSERT INTO roster (first_name, last_name, position) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $firstname, $lastname, $position);
        $stmt->execute();
        $stmt->close();
        echo "Member added.";
    }

    if (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $position = $_POST['position'];

        $stmt = $conn->prepare("UPDATE roster SET first_name = ?, last_name = ?, position = ? WHERE id = ?");
        $stmt->bind_param("sssi", $firstname, $lastname, $position, $id);
        $stmt->execute();
        $stmt->close();
        echo "Member updated.";
    }

    if (isset($_POST['delete'])) {
        $id = $_POST['id'];
        $stmt = $conn->prepare("DELETE FROM roster WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        echo "Member deleted.";
    }
}

$sql = "SELECT * FROM roster";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Edit Roster</title>
    <link id="main_css" href="../styles/style41.css" rel="stylesheet">
    <style>
        .center-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh; 
        }

        .content-box {
            width: 80%; /
            max-width: 600px;
            padding: 20px;
        }
    </style>
</head>
<body>
<?php include 'navbar.html'; ?>

<div class="center-container">
    <div class="content-box">
        <h1>Edit Roster</h1>
        <h2>Add New Member</h2>
        <form method="POST" action="edit_roster.php">
            <label for="firstname">First Name:</label><br>
            <input type="text" id="firstname" name="firstname" required><br><br>

            <label for="lastname">Last Name:</label><br>
            <input type="text" id="lastname" name="lastname" required><br><br>

            <label for="position">Position:</label><br>
            <input type="text" id="position" name="position" required><br><br>

            <button type="submit" name="add">Add Member</button>
        </form>
        <h2>Current Roster</h2>
        <table>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Position</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                <td><?php echo htmlspecialchars($row['position']); ?></td>
                <td>
                    <form method="POST" action="edit_roster.php">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="text" name="firstname" value="<?php echo htmlspecialchars($row['first_name']); ?>" required>
                        <input type="text" name="lastname" value="<?php echo htmlspecialchars($row['last_name']); ?>" required>
                        <input type="text" name="position" value="<?php echo htmlspecialchars($row['position']); ?>" required>
                        <button type="submit" name="edit">Edit</button>
                    </form>
                </td>
                <td>
                    <form method="POST" action="edit_roster.php">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>

<?php $conn->close(); ?>
<script src="../js/external-js.js"></script>
</body>
</html>
