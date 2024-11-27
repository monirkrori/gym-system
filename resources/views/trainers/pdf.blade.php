<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تصدير المدربين</title>
</head>
<body>
<h1>قائمة المدربين</h1>
<table border="1" cellspacing="0" cellpadding="5">
    <thead>
    <tr>
        <th>#</th>
        <th>الاسم</th>
        <th>البريد الإلكتروني</th>
        <th>تاريخ الإضافة</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($trainers as $trainer)
        <tr>
            <td>{{ $trainer->id }}</td>
            <td>{{ $trainer->name }}</td>
            <td>{{ $trainer->email }}</td>
            <td>{{ $trainer->created_at->format('Y-m-d') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
