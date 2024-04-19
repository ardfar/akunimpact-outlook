<!-- resources/views/dashboard.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Tambahkan link untuk menggunakan Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto py-6">
        <h1 class="text-2xl font-semibold mb-4">Omzet Chart</h1>
        <div class="mb-4">
            <label for="range" class="mr-2">Select Range:</label>
            <select id="range" name="range" class="px-2 py-1 rounded border">
                <option value="monthly" {{ request()->input('range') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="weekly" {{ request()->input('range') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                <option value="daily" {{ request()->input('range') == 'daily' ? 'selected' : '' }}>Daily</option>
            </select>
            <button onclick="updateChart()" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Submit</button>
        </div>
        <canvas id="omzetChart" width="650" height="250"></canvas>
        
        <!-- resources/views/dashboard.blade.php -->

<h1 class="text-2xl font-semibold mt-8 mb-4">Statistics</h1>
<form action="{{ route('dashboard.index') }}" method="GET" class="mb-4">
    <label for="month" class="mr-2">Select Month:</label>
    <select id="month" name="month" class="px-2 py-1 rounded border">
        @foreach ($monthOptions as $value => $label)
            <option value="{{ $value }}" {{ $value == $selectedMonth ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Submit</button>
</form>

        
        @if ($statistics)
            <div class="bg-gray-100 rounded-lg p-4 mb-4">
                <p class="text-lg font-semibold">Profit:</p>
                <p class="text-xl text-blue-700">{{ 'Rp ' . number_format($statistics->profit, 0, ',', '.') }}</p>
            </div>
            <div class="bg-gray-100 rounded-lg p-4 mb-4">
                <p class="text-lg font-semibold">Omzet:</p>
                <p class="text-xl text-blue-700">{{ 'Rp ' . number_format($statistics->omzet, 0, ',', '.') }}</p>
            </div>
            <div class="bg-gray-100 rounded-lg p-4 mb-4">
                <p class="text-lg font-semibold">Average Profit Received per Trx:</p>
                <p class="text-xl text-blue-700">{{ 'Rp ' . number_format($statistics->average_profit_per_trx, 0, ',', '.') }}</p>
            </div>
        @endif
    </div>

    <script>
    function updateChart() {
        var range = document.getElementById('range').value;
        var month = document.getElementById('month').value;
        var url = "{{ route('dashboard.index') }}?range=" + range;
        if (range != 'daily' && month) {
            url += "&month=" + month;
        }
        window.location.href = url;
    }

    var ctx = document.getElementById('omzetChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Omzet',
                data: {!! json_encode($omzet) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value, index, values) {
                            return 'Rp ' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        }
                    }
                }]
            }
        }
    });
</script>
</body>
</html>
