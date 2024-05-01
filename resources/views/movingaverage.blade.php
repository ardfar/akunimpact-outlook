<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Moving Average</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto py-6">
        <h1 class="text-2xl font-semibold mb-4">Profit Prediction</h1>
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
        <p class="text-lg font-semibold">Predicted Profit Trend:</p>
        <p class="text-xl {{ $profitTrend == 'increase' ? 'text-green-500' : 'text-red-500' }}">
        {{ ucfirst($profitTrend) }}
        </p>

        <h1 class="text-2xl font-semibold mb-4">Impact Secure Sales Prediction</h1>
        <canvas id="impactSecureChart" width="650" height="250"></canvas>
        <p class="text-lg font-semibold">Predicted Impact Secure Sales Trend:</p>
        <p class="text-xl {{ $impactTrend == 'increase' ? 'text-green-500' : 'text-red-500' }}">
        {{ ucfirst($impactTrend) }}
        </p>

        <h1 class="text-2xl font-semibold mt-8 mb-4">Statistics</h1>
        <form action="{{ route('moving_average.index') }}" method="GET" class="mb-4">
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
            var url = "{{ route('moving_average.index') }}?range=" + range;
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
                }, {
                    label: 'Profit Prediction',
                    data: {!! json_encode($profitPrediction) !!},
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
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

        var impactSecureCtx = document.getElementById('impactSecureChart').getContext('2d');
        var impactSecureChart = new Chart(impactSecureCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Impact Secure Sales',
                    data: {!! json_encode($impactSecureCounts) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }, {
                    label: 'Impact Secure Prediction',
                    data: {!! json_encode($impactSecurePrediction) !!},
                    backgroundColor: 'rgba(255, 159, 64, 0.2)',
                    borderColor: 'rgba(255, 159, 64, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1 // Set step size to 1 for integer values
                        }
                    }]
                }
            }
        });
    </script>
</body>
</html>
