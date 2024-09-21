<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Your reCAPTCHA secret key
    $secret = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';
    $response = $_POST['g-recaptcha-response'];
    $remoteip = $_SERVER['REMOTE_ADDR'];

    // Verify reCAPTCHA
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = [
        'secret' => $secret,
        'response' => $response,
        'remoteip' => $remoteip
    ];

    $options = [
        'http' => [
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        ]
    ];

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $resultJson = json_decode($result);

    if ($resultJson->success != true) {
        echo 'reCAPTCHA verification failed. Please try again.';
    } else {
        // reCAPTCHA verification successful
        $clinic = $_POST['clinic'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $message = $_POST['message'];

        $to = 'your-email@example.com'; // Replace with your email address
        $subject = 'New Appointment Request';
        $body = "Clinic: $clinic\nFirst Name: $first_name\nLast Name: $last_name\nEmail: $email\nPhone: $phone\nDate: $date\nTime: $time\nMessage: $message";
        $headers = "From: $email";

        if (mail($to, $subject, $body, $headers)) {
            echo 'Your appointment request has been sent successfully.';
        } else {
            echo 'There was an error sending your request. Please try again.';
        }
    }
}
?>