<?php
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: ../signin.php');
    exit;
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$databaseName = "water_polo";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $databaseName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to update merchandise status
function updateMerchStatus($conn, $merch_id, $new_status) {
    $stmt = $conn->prepare("UPDATE merchandise SET active = ? WHERE merch_id = ?");
    $stmt->bind_param("ii", $new_status, $merch_id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle'])) {
    $merch_id = $_POST['merch_id'];
    $current_status = $_POST['current_status'];
    $new_status = ($current_status == 1) ? 0 : 1;

    if (updateMerchStatus($conn, $merch_id, $new_status)) {
        $message = "Status updated successfully.";
    } else {
        $message = "Error updating status.";
    }
}

// Function to fetch merchandise by status
function fetchMerchByStatus($conn, $status) {
    $stmt = $conn->prepare("SELECT * FROM merchandise WHERE active = ? ORDER BY title ASC");
    $stmt->bind_param("i", $status);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $data;
}

// Fetch active and disabled merchandise
$activeMerch = fetchMerchByStatus($conn, 1);
$disabledMerch = fetchMerchByStatus($conn, 0);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Merchandise</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link id="main_css" href="../../styles/style41.css" rel="stylesheet">
    <?php include 'admin-bar.php'; ?>
    <style>
        .content-container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #CC0000;
            color: white;
        }
        .toggle-btn {
            background-color: #CC0000;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }
        .re-enable-btn {
            background-color: #28a745; 
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body style="background-color: #EEEEEE;">
    <div class="content-container">
        <h2 style="text-align: center;">Manage Merchandise</h2>
        <?php if (isset($message)): ?>
            <div style="text-align: center; margin: 20px;"><?php echo $message; ?></div>
        <?php endif; ?>

        <h3 style="text-align: center;">Active Merchandise</h3>
        <?php if (count($activeMerch) > 0): ?>
            <table>
                <tr>
                    <th>Merchandise ID</th>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($activeMerch as $merch): ?>
                    <tr>
                        <td><?php echo $merch['merch_id']; ?></td>
                        <td><?php echo $merch['title']; ?></td>
                        <td>$<?php echo number_format($merch['price'], 2); ?></td>
                        <td><?php echo $merch['description']; ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="merch_id" value="<?php echo $merch['merch_id']; ?>">
                                <input type="hidden" name="current_status" value="1">
                                <button type="submit" name="toggle" class="toggle-btn">Disable</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p style="text-align: center;">No active merchandise found.</p>
        <?php endif; ?>

        <h3 style="text-align: center; margin-top: 40px;">Disabled Merchandise</h3>
        <?php if (count($disabledMerch) > 0): ?>
            <table>
                <tr>
                    <th>Merchandise ID</th>
                    <th>Title</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
                <?php foreach ($disabledMerch as $merch): ?>
                    <tr>
                        <td><?php echo $merch['merch_id']; ?></td>
                        <td><?php echo $merch['title']; ?></td>
                        <td>$<?php echo number_format($merch['price'], 2); ?></td>
                        <td><?php echo $merch['description']; ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="merch_id" value="<?php echo $merch['merch_id']; ?>">
                                <input type="hidden" name="current_status" value="0">
                                <button type="submit" name="toggle" class="re-enable-btn">Enable</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p style="text-align: center;">No disabled merchandise found.</p>
        <?php endif; ?>
    </div>
</body>
</html>
