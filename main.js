document.getElementById("get-measured-data-button").addEventListener('click', getAjax);

function getAjax()
{
    console.log('Starting with AJAX');

    const now = new Date();
    const ten_minutes_ago = new Date();

    ten_minutes_ago.setMinutes(now.getMinutes() - 10);
    document.write(now);
    document.write(ten_minutes_ago);

    const requested_quantity = {
        "quantity": "Temperature",
        "time-begin": ten_minutes_ago,
        "time-end": now
    };

    request= new XMLHttpRequest();
    request.onreadystatechange = function()
    {
        if (this.readyState == 4 && this.status == 200)
        {
            const responseResult = JSON.parse(this.responseText);
            console.log(responseResult);
        }
    };

    request.open("POST", "data_visualiser.php", true);
    request.setRequestHeader("Content-type", "application/json");

    const todoJSON = JSON.stringify(requested_quantity);
    console.log(todoJSON);
    request.send(todoJSON);
}