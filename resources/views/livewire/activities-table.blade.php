<div class="bg-white rounded-lg shadow-lg p-6">
    <table class="w-full table-auto">
        <thead>
        <tr>
            <th class="py-2 px-4 text-left">النشاط</th>
            <th class="py-2 px-4 text-left">التاريخ</th>
        </tr>
        </thead>
        <tbody>
        @foreach($activities as $activity)
            <tr>
                <td class="py-2 px-4">{{ $activity['title'] }}</td>
                <td class="py-2 px-4">{{ $activity['date'] }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
