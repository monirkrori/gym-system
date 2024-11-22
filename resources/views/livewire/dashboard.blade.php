<div>
    <div class="container mx-auto px-4 py-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Card 1: عدد المستخدمين -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">عدد المستخدمين</h2>
                    <div class="text-3xl font-bold text-blue-500">1000</div>
                </div>
                <p class="text-gray-500 mt-4">إجمالي عدد المستخدمين في النظام</p>
            </div>

            <!-- Card 2: مخطط بياني -->
            <div class="bg-white shadow-lg rounded-lg p-6 col-span-2">
                <h2 class="text-xl font-semibold text-gray-800">مخطط النشاط</h2>
                <canvas id="activityChart"></canvas>
            </div>

            <!-- Card 3: عدد الطلبات -->
            <div class="bg-white shadow-lg rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">عدد الطلبات</h2>
                    <div class="text-3xl font-bold text-green-500">450</div>
                </div>
                <p class="text-gray-500 mt-4">إجمالي عدد الطلبات في النظام</p>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // إعداد بيانات المخطط
            var ctx = document.getElementById('activityChart').getContext('2d');
            var activityChart = new Chart(ctx, {
                type: 'line', // نوع المخطط
                data: {
                    labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو'],
                    datasets: [{
                        label: 'النشاط الشهري',
                        data: @json($data), // البيانات القادمة من Livewire
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endpush

</div>
