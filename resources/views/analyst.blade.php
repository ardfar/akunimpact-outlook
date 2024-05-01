<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>AkunImpact Analyst</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.8.0/dist/chart.min.js"></script>
</head>
<body>
  <canvas id="paymentMethodChart" width="200" height="50"></canvas>
  <canvas id="handlerChart" width="50" height="50"></canvas>

  <script>
  var ctx = document.getElementById('paymentMethodChart').getContext('2d');
  var paymentRanks = @json($paymentRanks);  // Convert PHP data to JS object

  // Chart configuration (bar chart)
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
              borderWidth: 1
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

  var ctxHandler = document.getElementById('handlerChart').getContext('2d');
    var handlerLabels = @json($handlerStats->pluck('handler_value'));  // Extract handler values as labels
    var handlerData = @json($handlerStats->pluck('count'));  // Extract counts as data values

    var myChartHandler = new Chart(ctxHandler, {
      type: 'pie',
      data: {
        labels: handlerLabels,
        datasets: [{
          label: 'Handler Distribution',
          data: handlerData,
          backgroundColor: [
            'rgba(0, 0, 139, 0.2)',  // Dark blue
                'rgba(96, 165, 229, 0.2)',  // Light blue
                'rgba(77, 77, 77, 0.2)',
          ],
          borderColor: [
            'rgba(0, 0, 139, 1)',  // Dark blue
                'rgba(96, 165, 229, 1)',  // Light blue
                'rgba(77, 77, 77, 1)',
          ],
          borderWidth: 0.5
        }]
      }
    });
  </script>
</body>
</html>