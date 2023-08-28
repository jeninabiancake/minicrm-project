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
    The contact {{ $contact->first_name . " " . $contact->last_name }} have been updated to {{ $contact->getStatus->name }} you can view it here <a href="{{ url('admin/contacts/' . $contact->id) }}"> {{ url('admin/contacts/' . $contact->id) }} </a>
</p>
</body>
</html>