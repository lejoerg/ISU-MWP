<?php
// Load PHPMailer files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';
require '../PHPMailer/Exception.php';

// Start session for CSRF token
session_start();

// Generate CSRF token if it doesn't exist
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$statusMessage = ''; // Initialize status message
$formSubmitted = false; // Flag to track successful form submission

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // CSRF Token Validation
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $statusMessage = 'Invalid CSRF token.';
    } else {
        // Sanitize and validate form data
        $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
        $phone = htmlspecialchars($_POST['phone'] ?? '', ENT_QUOTES, 'UTF-8');
        $preferredContactMethod = htmlspecialchars($_POST['preferredContactMethod'] ?? '', ENT_QUOTES, 'UTF-8');
        $subject = htmlspecialchars($_POST['subject'], ENT_QUOTES, 'UTF-8');
        $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');

        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $statusMessage = 'Invalid email format.';
        } elseif (strlen($message) > 1000) {
            $statusMessage = 'Message is too long.';
        } else {
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'lukejoerg12@gmail.com';
                $mail->Password = 'owky yetg naei yeno';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                // Recipients
                $mail->setFrom($email, $name);
                $mail->addAddress('lukejoerg12@gmail.com', 'ISU MWP'); // Add a recipient

                // Email content
                $mail->isHTML(true);
                $mail->Subject = $subject;

                // Construct email body
                $mail->Body = "<h3>Contact Request from $name</h3>
                               <p><strong>Email:</strong> $email</p>
                               <p><strong>Phone:</strong> $phone</p>
                               <p><strong>Preferred Contact Method:</strong> $preferredContactMethod</p>
                               <p><strong>Subject:</strong> $subject</p>
                               <p><strong>Message:</strong><br>$message</p>";

                // Send email
                $mail->send();
                $statusMessage = 'Message has been sent successfully!';
                $formSubmitted = true; // Mark form as successfully submitted
            } catch (Exception $e) {
                $statusMessage = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Illinois State University - Men's Water Polo RSO</title>
    <link id="main_css" href="../styles/style41.css" rel="stylesheet">
    <link href="../styles/easy-responsive-tabs.css" rel="stylesheet">
    <link href="../styles/additional-styles.css" rel="stylesheet">
    <?php include 'navbar.html'; ?>
</head>
<style>
.contact-container {
    max-width: 800px;
    margin: 80px auto 0 auto;
    background: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.25);
}

h1 {
    text-align: center;
    color: #333;
}

label {
    display: block;
    margin: 10px 0 5px;
}

input, textarea, select {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    width: 100%;
    padding: 10px;
    background-color: #CC0000;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    margin-right: 4%;
}

button:hover {
    background-color: #800000;
}

.reset-btn {
    background-color: #555;
}

.reset-btn:hover {
    background-color: #333;
}

.required {
    color: red;
}

.hidden {
    display: none;
}
</style>
<body>
    <div class="contact-container">
        <h1>Contact Us</h1>
        <!-- Display status message if available -->
        <?php if (!empty($statusMessage)): ?>
            <p><?= $statusMessage; ?></p>
        <?php endif; ?>
        
        <form id="contactForm" method="POST" action="">
            <label for="name"><b>Name </b><span class="required">*</span></label>
            <input type="text" id="name" name="name" required>

            <label for="email"><b>Email </b><span class="required">*</span></label>
            <input type="email" id="email" name="email" required>

            <label for="phone"><b>Phone Number </b><i>(optional)</i></label>
            <input type="tel" id="phone" name="phone" oninput="togglePreferredContact()">

            <div id="preferredContact" class="hidden">
                <label for="preferredContactMethod"><b>Preferred Contact Method </b></label>
                <select id="preferredContactMethod" name="preferredContactMethod">
                    <option value="Email">Email</option>
                    <option value="Phone">Phone</option>
                </select>
            </div>

            <label for="subject"><b>Subject </b><span class="required">*</span></label>
            <input type="text" id="subject" name="subject" required>

            <label for="message"><b>Message </b><span class="required">*</span></label>
            <textarea id="message" name="message" required style="height: 150px;"></textarea>

            <!-- CSRF token for security -->
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <!-- Submit and Reset buttons -->
            <button type="reset" class="reset-btn">Reset</button>
            <button type="submit">Submit</button>
        </form>
    </div>

    <script>
    function togglePreferredContact() {
        const phoneInput = document.getElementById('phone');
        const preferredContactDiv = document.getElementById('preferredContact');

        if (phoneInput.value) {
            preferredContactDiv.classList.remove('hidden');
        } else {
            preferredContactDiv.classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const currentPath = window.location.pathname.split("/").pop();
        const navLinks = document.querySelectorAll('.o-nav-main__link');

        navLinks.forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.classList.add('active');
            }
        });
    });

    <?php if ($formSubmitted): ?>
    // Clear form fields after successful submission
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('contactForm').reset();
    });
    <?php endif; ?>
    </script>
    
    <?php include 'footer.html'; ?>
</body>
</html>
