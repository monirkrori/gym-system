@extends('layouts.dashboard')

@section('content')
    <h1>العضويات المحذوفة</h1>

    @if($deletedMemberships->isEmpty())
        <p>لا توجد عضويات محذوفة.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>اسم العضو</th>
                    <th>الخطة</th>
                    <th>العضوية</th>
                    <th>تاريخ الحذف</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deletedMemberships as $membership)
                    <tr>
                        <td>{{ $membership->user->name }}</td>
                        <td>{{optional($membership->membership_plan)->name ?? 'غير محدد' }}</td>
                        <td>{{ optional($membership->membership_package)->name ?? 'غير محدد'}}</td>
                        <td>{{ $membership->deleted_at->format('Y-m-d H:i:s') }}</td>
                        <td>
                            <!-- Restore Membership Button -->
                            <form action="{{ route('admin.memberships.restore', $membership->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success">استرجاع</button>
                            </form>

                            <!-- Force Delete Button -->
                            <form action="{{ route('admin.memberships.force-delete', $membership->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">حذف نهائي</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
