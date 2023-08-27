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
    You have been assigned to a user role "{{ $user->roles[0]->name }}"
</p>
</body>
</html>