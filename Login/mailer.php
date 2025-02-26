<?php
// Connect to the database
require_once 'db.php';

$email = $_POST['email'] ?? null;
$no_WA = $_POST['no_WA'] ?? null;
$method = $_POST['method'];
$otp = rand(100000, 999999);  // Generate OTP

// Validate and send OTP based on selected method
if ($method == 'email' && $email) {
    // Send OTP to Email using a mailer function
    sendEmail($email, $otp);
} elseif ($method == 'whatsapp' && $no_WA) {
    // Send OTP to WhatsApp using Fomtte API
    sendWhatsApp($no_WA, $otp);
} else {
    // Handle missing email/WA
    echo "Missing details.";
}

// Save OTP to database for later validation
$query = "UPDATE pengguna SET otp = ? WHERE email = ? OR no_WA = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('sss', $otp, $email, $no_WA);
$stmt->execute();

// Function to send OTP via Email
function sendEmail($email, $otp) {
    $subject = "OTP for Password Reset";
    $message = "Your OTP code is: $otp";
    $headers = "From: kost.polibatam@gmail.com";
    if (mail($email, $subject, $message, $headers)) {
        echo "OTP sent to your email.";
    } else {
        echo "Failed to send OTP via email.";
    }
}

// Function to send OTP via WhatsApp using Fomtte API
function sendWhatsApp($no_WA, $otp) {
    $fomtte_api_url = "https://api.fomtte.com/send";
    $api_key = "your_fomtte_api_key";  // Replace with your actual Fomtte API key
    
    $data = [
        'phone' => $no_WA,
        'message' => "Your OTP code is: $otp",
        'apikey' => $api_key,
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fomtte_api_url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    // Check if the request was successful
    if ($response) {
        echo "OTP sent to WhatsApp.";
    } else {
        echo "Failed to send OTP via WhatsApp.";
    }
}
?>
