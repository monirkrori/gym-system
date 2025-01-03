<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تقرير العضويات</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            direction: rtl;
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        table th, table td {
            border: 1px solid #000;
            padding: 0.5rem;
        }
        table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h1>تقرير العضويات</h1>
<table>
    <thead>
    <tr>
        <th>#</th>
        <th>الاسم</th>
        <th>نوع العضوية</th>
        <th>تاريخ الانضمام</th>
        <th>تاريخ الانتهاء</th>
        <th>الحالة</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($memberships as $membership)
        <tr>
            <td>{{ $membership->id }}</td>
            <td>{{ $membership->name }}</td>
            <td>{{ $membership->type }}</td>
            <td>{{ $membership->created_at->format('Y-m-d') }}</td>
            <td>{{ $membership->expires_at?->format('Y-m-d') }}</td>
            <td>{{ $membership->is_active ? 'نشط' : 'منتهي' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
