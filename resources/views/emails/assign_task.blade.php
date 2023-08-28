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
    You have been assigned a task, go and check them here <a href="{{ url('admin/tasks/' . $task->id) }}"> {{ url('admin/tasks/' . $task->id) }} </a>
</p>
</body>
</html>