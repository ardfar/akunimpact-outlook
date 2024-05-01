<!-- resources/views/dashboard.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            <h2 class="text-2xl font-semibold">General Statistics</h2>
            <p>
                period 
                <span id="period-start"></span>
                -
                <span id="period-end"></span>
            </p>
        </div>

        {{-- Card Container  --}}
        <div class="w-full h-fit grid grid-cols-12 gap-x-4">
            {{-- Daily Transaction  --}}
            <div class="col-span-2 bg-gray-100 rounded-lg p-4 mb-4">
                <div class="flex justify-between w-full h-fit mb-4">
                    <i data-feather="activity" class="w-10 h-10 text-black"></i>
                    @if ($genStat["daily"]["count"] > 0)
                        <div class="flex items-center justify-between text-white bg-green-500 h-8 px-2 gap-x-1 rounded-lg">
                            <i data-feather="arrow-up" class="w-4 h-4 text-white inline"></i>
                            <span class="text-sm inline">
                                {{ $genStat["daily"]["count"] }}%
                            </span>
                        </div>
                        
                    @elseif ($genStat["daily"]["count"] < 0)
                        <div class="flex items-center justify-between text-white bg-red-500 h-8 px-2 gap-x-1 rounded-lg">
                            <i data-feather="arrow-up" class="w-4 h-4 text-white inline"></i>
                            <span class="text-sm inline">
                                {{ $genStat["daily"]["count"] }}%
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
                    @if ($genStat["weekly"]["count"] > 0)
                        <div class="flex items-center justify-between text-white bg-green-500 h-8 px-2 gap-x-1 rounded-lg">
                            <i data-feather="arrow-up" class="w-4 h-4 text-white inline"></i>
                            <span class="text-sm inline">
                                {{ $genStat["weekly"]["count"] }}%
                            </span>
                        </div>
                        
                    @elseif ($genStat["weekly"]["count"] < 0)
                        <div class="flex items-center justify-between text-white bg-red-500 h-8 px-2 gap-x-1 rounded-lg">
                            <i data-feather="arrow-up" class="w-4 h-4 text-white inline"></i>
                            <span class="text-sm inline">
                                {{ $genStat["weekly"]["count"] }}%
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
                    @if ($genStat["monthly"]["count"] > 0)
                        <div class="flex items-center justify-between text-white bg-green-500 h-8 px-2 gap-x-1 rounded-lg">
                            <i data-feather="arrow-up" class="w-4 h-4 text-white inline"></i>
                            <span class="text-sm inline">
                                {{ $genStat["monthly"]["count"] }}%
                            </span>
                        </div>
                        
                    @elseif ($genStat["monthly"]["count"] < 0)
                        <div class="flex items-center justify-between text-white bg-red-500 h-8 px-2 gap-x-1 rounded-lg">
                            <i data-feather="arrow-up" class="w-4 h-4 text-white inline"></i>
                            <span class="text-sm inline">
                                {{ $genStat["monthly"]["count"] }}%
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
                <p class="text-4xl text-blue-700">Micva</p>
                <p class="text-lg font-semibold mt-2">Most Handling</p>
            </div>

            {{-- Preferred Payment method  --}}
            <div class="col-span-2 bg-gray-100 rounded-lg p-4 mb-4">
                <div class="flex justify-start w-full h-fit mb-4">
                    <i data-feather="credit-card" class="w-10 h-10 text-black"></i>
                </div>
                <p class="text-4xl text-blue-700">BCA</p>
                <p class="text-lg font-semibold mt-2">Preferred Payment Method</p>
            </div>

            {{-- Best Category  --}}
            <div class="col-span-2 bg-gray-100 rounded-lg p-4 mb-4">
                <div class="flex justify-start w-full h-fit mb-4">
                    <i data-feather="tag" class="w-10 h-10 text-black"></i>
                </div>
                <p class="text-4xl text-blue-700">Account Sales</p>
                <p class="text-lg font-semibold mt-2">Best Category</p>
            </div>
        </div>
        
    </header>
    {{-- GENERAL CARD : END  --}}

    {{-- Finance Statistic: START  --}}
    <section class="w-full h-1/2 grid grid-cols-12 gap-x-4 px-6">
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


    <script>
        $(document).ready(function(){
            update_omzet()
            update_stat()
            feather.replace();
        })
    </script>

    <script>

        $("#range").change(function() {
            update_omzet()
        });


        function update_omzet() {
            var range = document.getElementById('range').value;
            $.ajax({
                url: "{{ route('get-omzet') }}/" + range,
                success: function (response){
                    console.log(response)

                    update_omzet_chart(response)
                }
            })
        }

        function reset_and_get_canvas(id){
            var canvas = $("#" + id);

            if (canvas.attr("data-drawn") == "true")
            {
                canvas.remove();

                $('<canvas/>',{
                    id: 'omzetChart',
                    style: 'width: 100%; height: 100%',
                    "data-drawn": "false",
                }).appendTo('#canvas-cont');

                canvas = $("#" + id)
            }

            return canvas
        }


        function update_omzet_chart(data){
            var canvas = reset_and_get_canvas("omzetChart")

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
