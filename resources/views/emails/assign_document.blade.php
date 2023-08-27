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
    You have been assigned a document, go and check them here <a href="{{ url('admin/documents/' . $document->id) }}"> {{ url('admin/documents/' . $document->id) }} </a>
</p>
</body>
</html>