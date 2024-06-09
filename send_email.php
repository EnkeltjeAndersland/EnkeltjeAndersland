<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $naam = $_POST['naam'];
    $plaats = $_POST['plaats'];
    $observatie_tekst = $_POST['observatie_tekst'];
    $files = $_FILES['files'];

    $to = 'enkeltjeandersland@gmail.com';
    $subject = 'New Contact Form Submission';
    $message = "Naam: $naam\nPlaats: $plaats\nObservatie Tekst: $observatie_tekst";
    $headers = "From: enkeltjeandersland@gmail.com";

    $boundary = md5(time());

    $headers .= "\r\nMIME-Version: 1.0\r\nContent-Type: multipart/mixed; boundary=\"{$boundary}\"";

    $body = "--{$boundary}\r\n";
    $body .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
    $body .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $body .= $message . "\r\n\r\n";

    for ($i = 0; $i < count($files['name']); $i++) {
        $file_name = $files['name'][$i];
        $file_size = $files['size'][$i];
        $file_tmp = $files['tmp_name'][$i];
        $file_type = $files['type'][$i];
        $file_content = chunk_split(base64_encode(file_get_contents($file_tmp)));

        $body .= "--{$boundary}\r\n";
        $body .= "Content-Type: {$file_type}; name=\"{$file_name}\"\r\n";
        $body .= "Content-Disposition: attachment; filename=\"{$file_name}\"\r\n";
        $body .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $body .= $file_content . "\r\n\r\n";
    }

    $body .= "--{$boundary}--";

    if (mail($to, $subject, $body, $headers)) {
        echo 'Form submitted successfully.';
    } else {
        echo 'Failed to send email.';
    }
} else {
    echo 'Invalid request method.';
}
?>
