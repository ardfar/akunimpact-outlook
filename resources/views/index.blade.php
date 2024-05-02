<!-- resources/views/dashboard.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AkunImpact Outlook</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <!-- Tambahkan link untuk menggunakan Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    {{-- GENERAL CARD : START  --}}
    <header class="w-full h-fit mt-10 px-6">
        {{-- Header Title  --}}
        <div class="w-full h-fit flex justify-between mb-6">
            <h2 class="text-2xl font-semibold">AkunImpact Outlook</h2>
            <div class="flex items-center justify-evenly gap-x-2 px-4 py-2 text-center bg-red-500 text-white rounded-lg">
                <i data-feather="file-text" class="w-4 h-4 text-white"></i>
                Export to PDF
            </div>
        </div>

        {{-- Card Container  --}}
        <div class="w-full h-fit grid grid-cols-12 gap-x-4">
            {{-- Daily Transaction  --}}
            <div class="col-span-2 bg-gray-100 rounded-lg p-4 mb-4">
                <div class="flex justify-between w-full h-fit mb-4">
                    <i data-feather="activity" class="w-10 h-10 text-black"></i>
                    @if ($genStat["daily"]["diff"] > 0)
                        <div class="flex items-center justify-between text-white bg-green-500 h-8 px-2 gap-x-1 rounded-lg">
                            <i data-feather="arrow-up" class="w-4 h-4 text-white inline"></i>
                            <span class="text-sm inline">
                                {{ number_format($genStat["daily"]["diff"], 2, ".", ",") }}%
                            </span>
                        </div>
                        
                    @elseif ($genStat["daily"]["diff"] < 0)
                        <div class="flex items-center justify-between text-white bg-red-500 h-8 px-2 gap-x-1 rounded-lg">
                            <i data-feather="arrow-up" class="w-4 h-4 text-white inline"></i>
                            <span class="text-sm inline">
                                {{ number_format($genStat["daily"]["diff"], 2, ".", ",") }}%
                            </span>
                        </div>

                    @else
                        <div class="flex items-center justify-between text-white bg-black h-8 px-2 gap-x-1 rounded-lg">
                            <i data-feather="minus" class="w-4 h-4 text-white inline"></i>
                            <span class="text-sm inline">
                                No Data
                            </span>
                        </div>
                    @endif
                </div>
                <p class="text-4xl text-blue-700">
                    {{ $genStat["daily"]["count"] }}
                </p>
                <p class="text-lg font-semibold mt-2">Daily Transaction</p>
            </div>

            {{-- Weekly Transaction  --}}
            <div class="col-span-2 bg-gray-100 rounded-lg p-4 mb-4">
                <div class="flex justify-between w-full h-fit mb-4">
                    <i data-feather="bar-chart" class="w-10 h-10 text-black"></i>
                    @if ($genStat["weekly"]["diff"] > 0)
                        <div class="flex items-center justify-between text-white bg-green-500 h-8 px-2 gap-x-1 rounded-lg">
                            <i data-feather="arrow-up" class="w-4 h-4 text-white inline"></i>
                            <span class="text-sm inline">
                                {{ number_format($genStat["weekly"]["diff"], 2, ".", ",") }}%
                            </span>
                        </div>
                        
                    @elseif ($genStat["weekly"]["diff"] < 0)
                        <div class="flex items-center justify-between text-white bg-red-500 h-8 px-2 gap-x-1 rounded-lg">
                            <i data-feather="arrow-down" class="w-4 h-4 text-white inline"></i>
                            <span class="text-sm inline">
                                {{ number_format($genStat["weekly"]["diff"], 2, ".", ",") }}%
                            </span>
                        </div>
                        
                    @else
                        <div class="flex items-center justify-between text-white bg-black h-8 px-2 gap-x-1 rounded-lg">
                            <i data-feather="minus" class="w-4 h-4 text-white inline"></i>
                            <span class="text-sm inline">
                                No Data
                            </span>
                        </div>
                    @endif
                </div>
                <p class="text-4xl text-blue-700">{{ $genStat["weekly"]["count"] }}</p>
                <p class="text-lg font-semibold mt-2">Weekly Transaction</p>
            </div>

            {{-- Total Transaction  --}}
            <div class="col-span-2 bg-gray-100 rounded-lg p-4 mb-4">
                <div class="flex justify-between w-full h-fit mb-4">
                    <i data-feather="bar-chart-2" class="w-10 h-10 text-black"></i>
                    @if ($genStat["monthly"]["diff"] > 0)
                        <div class="flex items-center justify-between text-white bg-green-500 h-8 px-2 gap-x-1 rounded-lg">
                            <i data-feather="arrow-up" class="w-4 h-4 text-white inline"></i>
                            <span class="text-sm inline">
                                {{ number_format($genStat["monthly"]["diff"], 2, ".", ",") }}%
                            </span>
                        </div>
                        
                    @elseif ($genStat["monthly"]["diff"] < 0)
                        <div class="flex items-center justify-between text-white bg-red-500 h-8 px-2 gap-x-1 rounded-lg">
                            <i data-feather="arrow-down" class="w-4 h-4 text-white inline"></i>
                            <span class="text-sm inline">
                                {{ number_format($genStat["monthly"]["diff"], 2, ".", ",") }}%
                            </span>
                        </div>
                        
                    @else
                        <div class="flex items-center justify-between text-white bg-black h-8 px-2 gap-x-1 rounded-lg">
                            <i data-feather="minus" class="w-4 h-4 text-white inline"></i>
                            <span class="text-sm inline">
                                No Data
                            </span>
                        </div>
                    @endif
                </div>
                <p class="text-4xl text-blue-700">{{ $genStat["monthly"]["count"] }}</p>
                <p class="text-lg font-semibold mt-2">Monthly Transaction</p>
            </div>

            {{-- Most handling  --}}
            <div class="col-span-2 bg-gray-100 rounded-lg p-4 mb-4">
                <div class="flex justify-start w-full h-fit mb-4">
                    <i data-feather="user" class="w-10 h-10 text-black"></i>
                </div>
                <p class="text-4xl text-blue-700">{{ $genStat["rank"]["handler"] }}</p>
                <p class="text-lg font-semibold mt-2">Most Transaction Handler</p>
            </div>

            {{-- Preferred Payment method  --}}
            <div class="col-span-2 bg-gray-100 rounded-lg p-4 mb-4">
                <div class="flex justify-start w-full h-fit mb-4">
                    <i data-feather="credit-card" class="w-10 h-10 text-black"></i>
                </div>
                <p class="text-4xl text-blue-700">{{ $genStat["rank"]["payment"] }}</p>
                <p class="text-lg font-semibold mt-2">Preferred Payment Method</p>
            </div>

            {{-- Best Category  --}}
            <div class="col-span-2 bg-gray-100 rounded-lg p-4 mb-4">
                <div class="flex justify-start w-full h-fit mb-4">
                    <i data-feather="tag" class="w-10 h-10 text-black"></i>
                </div>
                <p class="text-4xl text-blue-700 capitalize">{{ $genStat["best_cat"] }}</p>
                <p class="text-lg font-semibold mt-2">Best Category</p>
            </div>
        </div>
        
    </header>
    {{-- GENERAL CARD : END  --}}

    {{-- Finance Statistic: START  --}}
    <section class="w-full h-1/2 grid grid-cols-12 gap-x-4 px-6 mt-6">
        <div class="relative col-span-8 w-full h-full">
            <div class="flex justify-between items-center">
                <h2 class="text-2xl font-semibold mb-4">Omzet Chart</h2>

                <div class="mr-6">
                    <label for="range" class="mr-2">Graph Range</label>
                    <select id="range" name="range" class="px-2 py-1 rounded border">
                        <option value="monthly">Monthly</option>
                        <option value="weekly">Weekly</option>
                        <option value="daily">Daily</option>
                    </select>
                </div>
            </div>
            <div class="relative w-full h-5/6" id="canvas-cont">
                <canvas id="omzetChart" style="width: 100%; height: 100%" data-drawn="false"></canvas>
            </div>
        </div>

        <div class="relative col-span-4 w-full h-full">
            <div class="flex items-center justify-between w-full h-fit">
                <h2 class="text-2xl font-semibold mb-4">Finance Statistics</h2>

                <div class="mb-4">
                    <label for="period" class="mr-2">Period</label>
                    <select id="period" name="period" class="px-2 py-1 rounded border">
                        @foreach ($monthOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        
            <div class="bg-gray-100 rounded-lg p-4 mb-4">
                <p class="text-lg font-semibold">Omzet:</p>
                <p class="text-xl text-blue-700" id="stat-omzet">Rp. - </p>
            </div>
            <div class="bg-gray-100 rounded-lg p-4 mb-4">
                <p class="text-lg font-semibold">Profit:</p>
                <p class="text-xl text-blue-700" id="stat-profit">Rp. - </p>
            </div>
            <div class="bg-gray-100 rounded-lg p-4 mb-4">
                <p class="text-lg font-semibold">Average Profit Received per Transaction:</p>
                <p class="text-xl text-blue-700" id="stat-avg-profit">Rp. - </p>
            </div>

        </div>
    </section>
    {{-- Finance Statistic: END  --}}

    {{-- Finance Prediction: START  --}}
    <section class="w-full h-1/2 grid grid-cols-12 gap-x-4 px-6 mt-8">
        <div class="relative col-span-8 w-full h-full">
            <div class="flex justify-start items-center">
                <h2 class="text-2xl font-semibold mb-4">Impact Secure Chart</h2>
            </div>
            <div class="relative w-full h-5/6" id="is-canvas-cont">
                <canvas id="ISChart" style="width: 100%; height: 100%" data-drawn="false"></canvas>
            </div>
        </div>

        <div class="relative col-span-4 w-full h-full">
            <div class="w-full h-fit mb-4">
                <h2 class="text-2xl font-semibold mb-4">Finance Prediction</h2>

                <p class="capitalize">
                    <span class="font-bold uppercase">
                        disclaimer! 
                    </span>
                    these predictions were predicted based on their respective moving average which indicates the uptrend or downtrend.
                </p>
            </div>
        
            <div class="bg-gray-100 rounded-lg p-4 mb-4">
                <p class="text-lg font-semibold">Omzet</p>
                <p class="text-xl text-blue-700 capitalize" id="pred-omzet">Down</p>
            </div>
            <div class="bg-gray-100 rounded-lg p-4 mb-4">
                <p class="text-lg font-semibold">Impact Secure Sales</p>
                <p class="text-xl text-blue-700 capitalize" id="pred-is">High</p>
            </div>

        </div>
    </section>
    {{-- Finance Prediction: END  --}}

    {{-- Another Stat : START  --}}
    <section class="w-full h-1/2 grid grid-cols-12 gap-x-4 px-6 mt-8">
        <div class="relative col-span-9 w-full h-full">
            <div class="flex justify-start items-center">
                <h2 class="text-2xl font-semibold mb-4">Payment Methods Chart</h2>
            </div>
            <div class="relative w-full h-5/6 flex items-center" id="payment-canvas-cont">
                <canvas id="paymentChart" style="width: 100%; height: 100%" data-drawn="false"></canvas>
            </div>
        </div>
        <div class="relative col-span-3 w-full h-full">
            <div class="flex justify-center items-center">
                <h2 class="text-2xl font-semibold mb-4 text-center">Payment Methods Chart</h2>
            </div>
            <div class="relative w-full h-5/6 flex items-center justify-center" id="handler-canvas-cont">
                <canvas id="handlerChart" style="width: 100%; height: 100%" data-drawn="false"></canvas>
            </div>
        </div>
    </section>
    {{-- Another Stat : END  --}}
    
    {{-- Data Display : START  --}}
    <section class="w-full h-fit px-6 my-10">
        {{-- Header Title  --}}
        <div class="w-full h-fit flex justify-between mb-6">
            <h2 class="text-2xl font-semibold">Recent Transactions</h2>
            <a href="{{ route("add-record") }}" class="flex items-center justify-evenly gap-x-2 px-4 py-2 text-center bg-green-500 text-white rounded-lg">
                <i data-feather="plus-square" class="w-4 h-4 text-white"></i>
                Add Record
            </a>
        </div>

        {{-- Card Container  --}}
        <div class="w-full h-fit grid grid-cols-12 gap-y-2">
            {{-- Daily Transaction  --}}
            <div class="grid grid-cols-12 col-span-12 bg-gray-100 rounded-lg p-4 mb-4 text-center">
                <p class="text-xl text-blue-700">
                    Date
                </p>
                <p class="text-xl text-blue-700 col-span-2">
                    Code
                </p>
                <p class="text-xl text-blue-700">
                    Category
                </p>
                <p class="text-xl text-blue-700 col-span-2">
                    Selling Price
                </p>
                <p class="text-xl text-blue-700 col-span-2">
                    Profits
                </p>
                <p class="text-xl text-blue-700">
                    Method
                </p>
                <p class="text-xl text-blue-700 col-span-2">
                    Impact Secure
                </p>
                <p class="text-xl text-blue-700">
                    Handler
                </p>
            </div>

            @foreach ($recent_trx as $item)
            <div class="grid grid-cols-12 col-span-12 bg-gray-100 rounded-lg p-4 mb-4 text-center">
                <p class="text-lg black">
                    {{ \Carbon\Carbon::parse($item->trx_time)->toDateString() }}
                </p>
                <p class="text-lg black col-span-2">
                    {{ $item->code }}
                </p>
                <p class="text-lg black">
                    {{ $item->category }}
                </p>
                <p class="text-lg black col-span-2">
                    {{ number_format($item->deal_price, 0, ',', '.') }}
                </p>
                <p class="text-lg black col-span-2">
                    {{ number_format(($item->deal_price - $item->net_price), 0, ',', '.') }}
                </p>
                <p class="text-lg black">
                    {{ $item->buyer_payments }}
                </p>
                <p class="text-lg black col-span-2">
                    {{ $item->impact_secure }}
                </p>
                <p class="text-lg black">
                    {{ $item->handler }}
                </p>
            </div>
            @endforeach
        </div>
        
    </header>
    {{-- Data Display : END  --}}


    <script>
        $(document).ready(function(){
            update_omzet()
            update_impact_secure()
            update_stat()
            update_payment_chart()
            update_handler_chart()
            feather.replace();
        })

        $("#range").change(function() {
            update_omzet()
            update_impact_secure()
        });

        function reset_and_get_canvas(id, container){
            var canvas = $("#" + id);

            if (canvas.attr("data-drawn") == "true")
            {
                canvas.remove();

                $('<canvas/>',{
                    id: id,
                    style: 'width: 100%; height: 100%',
                    "data-drawn": "false",
                }).appendTo('#' + container);

                canvas = $("#" + id)
            }

            return canvas
        }
    </script>

    <script>
        function update_omzet() {
            var range = document.getElementById('range').value;
            $.ajax({
                url: "{{ route('get-omzet') }}/" + range,
                success: function (response){
                    console.log(response)

                    update_omzet_chart(response)
                    $("#pred-omzet").html(response["prediction"])

                }
            })
        }


        function update_omzet_chart(data){
            var canvas = reset_and_get_canvas("omzetChart", "canvas-cont")

            canvas.attr("data-drawn", "true")

            var ctx = canvas[0].getContext('2d');

            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data["labels"],
                    datasets: [{
                        label: 'Omzet',
                        data: data["omzet"],
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1,
                        tension: 0.5
                    },{
                        label: 'Moving Average',
                        data: data["moving_average"],
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        tension: 0.5
                    }
                ]
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
        }
    </script>

    <script>
        function update_impact_secure() {
            var range = document.getElementById('range').value;
            $.ajax({
                url: "{{ route('get-impact-secure') }}/" + range,
                success: function (response){
                    console.log(response)

                    update_impact_secure_chart(response)
                    $("#pred-is").html(response["prediction"])
                }
            })
        }


        function update_impact_secure_chart(data){
            var canvas = reset_and_get_canvas("ISChart", "is-canvas-cont")

            canvas.attr("data-drawn", "true")

            var ctx = canvas[0].getContext('2d');

            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data["labels"],
                    datasets: [{
                        label: 'Sales',
                        data: data["impact_secure"],
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        tension: 0.5

                    }, {
                        label: 'Moving Average',
                        data: data["moving_average"],
                        backgroundColor: 'rgba(255, 159, 64, 0.2)',
                        borderColor: 'rgba(255, 159, 64, 1)',
                        borderWidth: 1,
                        tension: 0.5
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
        }
    </script>

    <script>

        function update_payment_chart(data){
            var canvas = reset_and_get_canvas("paymentChart", "payment-canvas-cont")
            paymentRanks = @json($payment_method);

            canvas.attr("data-drawn", "true")

            var ctx = canvas[0].getContext('2d');

            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: paymentRanks.map(rank => rank.payment_method),  // Extract payment methods as labels
                    datasets: [{
                        label: 'Payment Method Counts',
                        data: paymentRanks.map(rank => rank.count),  // Extract counts as data values
                        backgroundColor: [
                            // Define background colors for bars (optional)
                            'rgba(0, 0, 139, 0.2)',  // Dark blue
                            'rgba(96, 165, 229, 0.2)',  // Light blue
                            'rgba(77, 77, 77, 0.2)',
                            // Add more colors if needed
                        ],
                        borderColor: [
                            // Define border colors for bars (optional)
                            'rgba(0, 0, 139, 1)',   // Dark blue
                            'rgba(96, 165, 229, 1)',  // Light blue
                            'rgba(77, 77, 77, 1)',  
                            // Add more colors if needed
                        ],
                        borderWidth: 1,
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        }

        function update_handler_chart(data){
            var canvas = reset_and_get_canvas("handlerChart", "handler-canvas-cont")
            data = @json($handler)  // Extract counts as data values


            canvas.attr("data-drawn", "true")

            var ctx = canvas[0].getContext('2d');

            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: data.map(item => item.handler_value),
                    datasets: [{
                        label: 'Handler Distribution',
                        data: data.map(item => item.count),
                        backgroundColor: [
                            'rgba(0, 0, 139, 0.2)',  // Dark blue
                            'rgba(96, 165, 229, 0.2)',  // Light blue
                            'rgba(77, 77, 77, 0.2)',
                            "rgba(253,158,0.2)",
                            "rgba(255,242,0, 0.2)"
                        ],
                        borderColor: [
                            'rgba(0, 0, 139, 1)',  // Dark blue
                            'rgba(96, 165, 229, 1)',  // Light blue
                            'rgba(77, 77, 77, 1)',
                            "rgba(253,158,1)",
                            "rgba(255,242,0, 1)"

                        ],
                        borderWidth: 0.5,
                    }]
                }
                });
        }
    </script>

    <script>
        $("#period").change(function() {
            update_stat()
        });


        function update_stat() {
            var period = document.getElementById('period').value;

            const formatter = new Intl.NumberFormat("id-ID", {
                style: 'currency',
                currency: 'IDR',
            });

            $.ajax({
                url: "{{ route('get-finance-statistic') }}/" + period,
                success: function (response){
                    console.log(response)

                    $("#stat-profit").text(formatter.format(response["profit"]))
                    $("#stat-omzet").text(formatter.format(response["omzet"]))
                    $("#stat-avg-profit").text(formatter.format(response["avg_profit"]))
                }
            })
        }
    </script>
</body>
</html>
