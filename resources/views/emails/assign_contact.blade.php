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
    You have been assigned a contact, go and check them here <a href="{{ url('admin/contacts/' . $contact->id) }}"> {{ url('admin/contacts/' . $contact->id) }} </a>
</p>
</body>
</html>
