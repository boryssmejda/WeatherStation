function drawChart()
{
    console.log("In draw chart");
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

function verifyInputParameters()
{
    clearErrorInformation();

    let city = getCity();

    let physicalQuantities = getPhysicalQuantities();
    if(physicalQuantities.length == 0)
    {
        printErrorInformation("No measured quantity was chosen!");
        return;
    }

    let datetime_beginning = getDateTime("weatherConditionsBeginning");
    console.log("Beginning:" + datetime_beginning);
    if(datetime_beginning == "")
    {
        printErrorInformation("No datetime beginning was chosen!");
        return;
    }

    let datetime_end = getDateTime("weatherConditionsEnd");
    console.log("End:" + datetime_end);
    if(datetime_end == "")
    {
        printErrorInformation("No datetime end was chosen!");
        return;
    }

    if(datetime_end <= datetime_beginning)
    {
        printErrorInformation("End datetime cannot be earlier than the beginning!");
        return;
    }

    let userInformation = new InformationGivenByTheUser(city, physicalQuantities, datetime_beginning, datetime_end);
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

function makeElementVisible(element_id)
{
    document.getElementById("error_information").style.display = 'block';
    document.getElementById("error_information").style.visibility = 'visible';
}
