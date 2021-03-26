<html>
<body>
<strong>Customer name:</strong> {{ ucfirst($name) ?? '' }}<br>
<strong>Customer email:</strong> {{ $email ?? '' }} <br>
<strong>Customer contact number:</strong> {{ $contact_no ?? '' }} <br>
<hr>
<strong>Message:</strong>
<br>
{!! $msg !!}
</body>
</html>
