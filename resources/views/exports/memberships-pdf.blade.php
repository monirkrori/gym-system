<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تقرير العضويات</title>
    <style>
        body {
            font-family: 'cairo', sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: right;
        }
        th {
            background-color: #f4f4f4;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .status-active {
            color: green;
        }
        .status-expired {
            color: red;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>تقرير العضويات</h1>
    <p>تاريخ التقرير: {{ now()->format('Y-m-d') }}</p>
</div>

<table>
    <thead>
    <tr>
        <th>#</th>
        <th>اسم المستخدم</th>
        <th>البريد الإلكتروني</th>
        <th>نوع الباقة</th>
        <th>الحالة</th>
        <th>تاريخ الإنشاء</th>
    </tr>
    </thead>
    <tbody>
    @foreach($memberships as $membership)
        <tr>
            <td>{{ $membership->id }}</td>
            <td>{{ $membership->user?->name ?? 'غير متوفر' }}</td>
            <td>{{ $membership->user?->email ?? 'غير متوفر' }}</td>
            <td>{{ $membership->package?->name ?? 'غير متوفر' }}</td>
            <td class="status-{{ $membership->status }}">
                {{ $membership->status === 'active' ? 'نشط' : 'منتهي' }}
            </td>
            <td>{{ $membership->created_at->format('Y-m-d') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
