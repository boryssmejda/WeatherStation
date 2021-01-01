function drawChart()
{
    var ctx = document.getElementById('myChart').getContext('2d');
    var chart = new Chart(ctx, {
        // The type of chart we want to create
        type: 'line',
        backgroundColor: 'rgb(255, 99, 132)',

        // The data for our dataset
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
            datasets: [{
                label: 'Temperature',
                data: [0, 10, 5, 2, 20, 30, 45],
                "fill":false,
                backgroundColor: 'rgb(255, 99, 132)',
                "borderColor":"rgb(100, 100, 255)"
            }]
        },

        // Configuration options go here
        options: {
            title:
            {
                display: true,
                text: "Temperature in Tuszyn",
                fontSize: 15
            },
            legend:
            {
                display: false
            },
            maintainAspectRatio: false,
        }
    });
}