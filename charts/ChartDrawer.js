class ChartDrawer
{
    constructor(yAxisPoints, xAxisPoints, title, canvasID, physicalQuantityName)
    {
        this.yAxisPoints = yAxisPoints;
        this.xAxisPoints = xAxisPoints;
        this.title = title;
        this.canvasID = canvasID;
        this.physicalQuantityName = physicalQuantityName;
    }

    drawChart()
    {
        console.log("Started drawing a chart!");

        var ctx = document.getElementById(this.canvasID).getContext('2d');
        var chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'line',
            backgroundColor: 'rgb(255, 99, 132)',

            // The data for our dataset
            data: {
                labels: this.xAxisPoints,
                datasets: [{
                    label: physicalQuantityName,
                    data: this.yAxisPoints,
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
                    text: this.title,
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
}

class ChartDrawerBuilder
{
    constructor()
    {
        this.yAxisPoints = [];
        this.xAxisPoints = [];
        this.title = '';
        this.canvasID = '';
        this.physicalQuantityName = '';
    }

    setChartPoints(chartPoints)
    {
        this.yAxisPoints = getYAxisPoints(chartPoints);
        this.xAxisPoints = getXAxisPoints(chartPoints);
        return this;
    }

    getYAxisPoints(chartPoints)
    {
        let yAxisPoints = [];

        for(point in chartPoints)
        {
            yAxisPoints.push(point.measured_value);
        }

        return yAxisPoints;
    }

    getXAxisPoints(chartPoints)
    {
        let xAxisPoints = [];
        for(point in chartPoints)
        {
            xAxisPoints.push(point.reading_time);
        }
    }

    setChartTitle(title)
    {
        this.title = title;
        return this;
    }

    setCanvasID(canvasID)
    {
        this.canvasID = canvasID;
        return this;
    }

    setPhysicalQuantityName(physicalQuantityName)
    {
        this.physicalQuantityName = physicalQuantityName;
        return this;
    }

    build()
    {
        return new ChartDrawer(this.yAxisPoints, this.xAxisPoints, this.title, this.canvasID);
    }
}
