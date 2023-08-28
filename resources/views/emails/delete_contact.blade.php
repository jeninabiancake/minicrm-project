<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $subject }}</title>
</head>
<body>
<p>
    Hello {{ $user->name }},
</p>

<p>
    The contact {{ $contact->first_name . " " . $contact->last_name }} have been deleted.
</p>
</body>
</html>