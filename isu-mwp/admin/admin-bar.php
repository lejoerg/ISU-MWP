<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        /* Navbar styling */
        .admin-bar {
            background-color: #CC0000;
            color: white;
            font-size: 18px;
            font-weight: bold;
            line-height: 2;
            padding: 10px 0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Back button styling */
        .admin-bar .back-button {
            position: absolute;
            left: 15px;
            background-color: white;
            color: #CC0000;
            font-size: 18px;
            border: none;
            border-radius: 15px;
            padding: 5px 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            font-weight: bold;
        }

        .admin-bar .back-button:hover {
            background-color: #ffffffaa;
        }

        /* Arrow icon for back button */
        .admin-bar .back-button::before {
            content: '←';
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="admin-bar">
        <!-- Back button -->
        <button class="back-button" onclick="window.location.href='../admin-portal.php';">
            Back to Portal
        </button>
        <!-- Title centered on the same line -->
        Admin Portal
    </div>
</body>
</html>
