<!DOCTYPE html>
<html>
<head>
    <title>Mail from {{ config('app.name') }}</title>
</head>
<body>
    <h4>Hello! Admin</h4>
    <p>You have received a new contact form submission. Here are the details:</p>
    <h5>Email: {{ $details['email'] }}</h5>
    <p>Phone Number: {{ $details['phone'] }}</p>
    <p>Message: {{ $details['message'] }}</p>
</body>
</html>
