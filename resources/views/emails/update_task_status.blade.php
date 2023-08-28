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
    Task "{{ $task->name }}" have been updated to status "{{ $task->getStatus->name }}" you can view it here <a href="{{ url('admin/tasks/' . $task->id) }}"> {{ url('admin/tasks/' . $task->id) }} </a>
</p>
</body>
</html>