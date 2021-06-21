function renderChart(labels, data) {
  //  Mixed chart
  var ctx = document.getElementById('monthly-orders-chart');
  // ctx.height = 60;
  let orderChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [
        {
          label: 'Price Change Line',
          type: 'line',
          borderColor: '#8192a3',
          // borderColor: '#b59049',
          borderWidth: 3,
          backgroundColor: 'rgba(127,99,244,0.1)',
          data: data,
          pointBackgroundColor: '#8192a3',
          pointHoverBackgroundColor: '#8192a3',
          pointRadius: 0,
          pointHoverRadius: 3,
          fill: true,
        },
        {
          label: 'Price Change Bar',
          type: 'bar',
          backgroundColor: '#b59049',
          backgroundColorHover: '#14637F',
          data: data,
        },
      ],
    },
    options: {
      barRadius: 4,
      maintainAspectRatio: false,
      title: {
        display: false,
        text: 'Population growth (millions): Europe & Africa',
      },
      legend: {
        position: 'bottom',
        labels: {
          usePointStyle: true,
          fontFamily: 'Segoe UI',
          padding: 25,
        },
      },
      scales: {
        yAxes: [
          {
            display: true,
            ticks: {
              beginAtZero: false,
            },
            gridLines: { color: '#f2f2f2' },
          },
        ],
        xAxes: [
          {
            // Change here
            display: true,
            barPercentage: 0.2,
            ticks: {
              beginAtZero: false,
            },
            gridLines: { color: '#fff' },
          },
        ],
      },
    },
  });
}
//doughut chart
var ctx = document.getElementById('most-selling-items');
// ctx.height = 175;
new Chart(ctx, {
  type: 'doughnut',
  data: {
    datasets: [
      {
        data: [10, 20, 30, 50],
        backgroundColor: ['#CC4F5B', '#14637F', '#8E8E8E', '#AD884F'],
        hoverBackgroundColor: [
          'rgba(204, 79, 91, 0.9)',
          'rgba(20, 99, 127, 0.9)',
          'rgba(142, 142, 142, 0.9)',
          'rgba(173, 136, 79, 0.9)',
        ],
      },
    ],
    labels: [
      'Bacardí Superior',
      'Metaxa 5 Stars',
      'Bushmills Black Bush',
      'Aberlour 18-Year',
    ],
  },
  options: {
    responsive: true,
    cutoutPercentage: 60,
    maintainAspectRatio: false,
    animation: {
      animateRotate: true,
      animateScale: true,
    },
    legend: {
      position: 'right',
      labels: {
        usePointStyle: true,
        fontFamily: 'Segoe UI',
        fontSize: 14,
        fontColor: '#464a53',
      },
    },
  },
});
