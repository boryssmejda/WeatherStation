function plotWeatherConditionsCharts()
{
    clearErrorInformation();
    deleteDivForPlots();

    const parametersChosenByTheUser = new InputParameters();

    if(verifyInputParameters(parametersChosenByTheUser) != true)
    {
        return;
    }

    getWeatherConditions(parametersChosenByTheUser);
}

function getWeatherConditions(parametersChosenByTheUser)
{
    sendChosenParametersToServer(parametersChosenByTheUser);
}

function drawChart(yAxisPoints, xAxisPoints, title, canvasID)
{
    console.log("In draw chart");

    var ctx = document.getElementById(canvasID).getContext('2d');
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

function verifyInputParameters(userInput)
{
    if(userInput.physicalQuantities.length == 0)
    {
        printErrorInformation("No measured quantity was chosen!");
        return false;
    }

    if(userInput.beginning == "")
    {
        printErrorInformation("No datetime beginning was chosen!");
        return false;
    }

    if(userInput.end == "")
    {
        printErrorInformation("No datetime end was chosen!");
        return false;
    }

    if(userInput.end <= userInput.beginning)
    {
        printErrorInformation("End datetime cannot be earlier than the beginning!");
        return false;
    }

    console.log("Physical quantities: " + physicalQuantities);
    let userInformation = new InformationGivenByTheUser(city, physicalQuantities, datetime_beginning, datetime_end);

    sendChosenParametersToServer(userInformation);
}

function getCity()
{
    return document.querySelector('input[name="city"]:checked').value;
}

function getPhysicalQuantities()
{
    let physical_quantities = [];
    const chosenPhysicalQuantities = document.querySelectorAll('input[name="physical_quantity"]:checked');

    chosenPhysicalQuantities.forEach(element => {
        physical_quantities.push(element.value);
    });

    return physical_quantities;
}

function clearErrorInformation()
{
    document.getElementById("error_information").style.display = 'none';
    document.getElementById("error_information").innerHTML = "";

    console.log("Cleared error information!\n");
}

function getDateTime(current_datetime)
{
    return document.getElementById(current_datetime).value;
}

function printErrorInformation(information)
{
    makeElementVisible();
    document.getElementById("error_information").innerHTML += "<strong>Error!</strong> ";
    document.getElementById("error_information").innerHTML += information;
}

function makeElementVisible()
{
    document.getElementById("error_information").style.display = 'block';
    document.getElementById("error_information").style.visibility = 'visible';
}

function sendChosenParametersToServer(chosenParameters)
{
    const jsonRequest = createJSONFromChosenParameters(chosenParameters);

    let xhttpRequest = new XMLHttpRequest();

    xhttpRequest.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200)
        {
            console.log("Got the message!");
            let plotPoints = JSON.parse(this.responseText);
            console.log(plotPoints);
            plotCharts(plotPoints, chosenParameters);
        }
    };

    xhttpRequest.open("POST", "receiver.php", true);
    xhttpRequest.setRequestHeader("Content-type", "application/json");
    xhttpRequest.send(jsonRequest);
}

function createJSONFromChosenParameters(chosenParameters)
{
    console.log(chosenParameters.physicalQuantities);
    const toSend = {
        city: chosenParameters.city,
        physicalQuantities: chosenParameters.physicalQuantities,
        beginning: chosenParameters.beginning,
        end: chosenParameters.end
    };

    return JSON.stringify(toSend);
}

function plotCharts(plotPoints, userInputParameters)
{
    console.log("Plot charts!");

    console.log(plotPoints.length);

    createDivForPlots();
    createCanvasesForPlots(userInputParameters.physicalQuantities);

    for(i = 0; i < userInputParameters.physicalQuantities.length; ++i)
    {
        const chartTitle = createChartTitle(userInputParameters.city, userInputParameters.physicalQuantities[i]);

        const chart = new ChartDrawerBuilder()
            .setChartPoints(plotPoints[i])
            .setChartTitle(chartTitle)
            .setCanvasID(userInputParameters.physicalQuantities[i] + '_canvas')
            .setPhysicalQuantityName(userInputParameters.physicalQuantities[i])
            .build()

        chart.drawChart();
    }
}

function createDivForPlots()
{
    console.log("In create div plots");

    const newDiv = document.createElement("div");
    newDiv.id = "div_for_plots";

    let currentDiv = document.getElementById("main_page_content");
    currentDiv.appendChild(newDiv);
}

function createCanvasesForPlots(physicalQuantities)
{
    for(i = 0; i < physicalQuantities.length; ++i)
    {
        addCanvas(physicalQuantities[i]);
    }
}

function addCanvas(canvasName)
{
    let canv = document.createElement('canvas');
    canv.id = canvasName + '_canvas';
    canv.style.width = "100%";
    canv.style.display = "block";
    canv.style.border = "5px solid red";
    canv.style.marginBottom = "5%";

    document.getElementById('div_for_plots').appendChild(canv);
}

function deleteDivForPlots()
{
    let div_for_plots = document.getElementById("div_for_plots");

    if(div_for_plots == null)
    {
        return;
    }

    div_for_plots.remove();
}

function createChartTitle(city, displayedQuantity)
{
    return displayedQuantity + ' in ' + city;;
}
