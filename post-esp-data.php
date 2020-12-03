<?php

$servername = "localhost";
$dbname = "dbc17_stud_005";
$username = "dbc17_stud_005";
$password = "8AS_d2Qsr";

$api_key_value = "tPmAT5Ab3j7F9";

$api_key= $value = $unit = $sensor = $location = $quantity = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $json_result = file_get_contents('php://input');
    if ($json_result == false)
    {
        die("Unable to parse json!");
    }

    $weather_station_measurements = json_decode($json_result, true);
    $api_key = test_input($weather_station_measurements['apiKeyValue']);

    if($api_key == $api_key_value)
    {
        require('db_connection.php');
        $conn = new DB_connection();

        $sensor_location_id = get_sensor_location_id($conn->get(), $weather_station_measurements['location']);
        foreach($weather_station_measurements['data'] as $sensor_measurement)
        {

            $measured_value = str_replace(',', '.', $sensor_measurement['value']);
            $measurement_unit_id = get_measurement_unit_id($conn->get(), $sensor_measurement['unit']);
            $sensor_id = get_sensor_id($conn->get(), $sensor_measurement['sensor']);
            $measured_quantity_id = get_measured_quantity_id($conn->get(), $sensor_measurement['quantity']);

            $sql = "INSERT INTO Measurements (measured_value, measurement_unit_id, sensor_id, measured_quantity_id, sensor_location_id)
                VALUES ($measured_value, $measurement_unit_id, $sensor_id, $measured_quantity_id, $sensor_location_id)";

            $conn->get()->query($sql);
        }
    }
    else
    {
        echo "Wrong API Key provided.";
    }

}
else
{
    echo "No data posted with HTTP POST.";
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function get_sensor_location_id($conn, $sensor_location)
{
    $sensor_location_id = 0;
    $sql_query = "SELECT sensor_location_id FROM Sensors_locations WHERE sensor_location_name='$sensor_location'";

    if ($result = mysqli_query($conn, $sql_query))
    {
        $obj = $result->fetch_object();
        $sensor_location_id = $obj->sensor_location_id;
        mysqli_free_result($result);
    }
    else
    {
        die("Could not get sensor location id!");
    }

    return $sensor_location_id;
}

function get_measurement_unit_id($conn, $measurement_unit)
{
    $measurement_unit_id = 0;
    $sql_query = "SELECT measurement_unit_id FROM Measurement_units WHERE unit_name='$measurement_unit'";

    if ($result = mysqli_query($conn, $sql_query))
    {
        $obj = $result->fetch_object();
        $measurement_unit_id = $obj->measurement_unit_id;
        mysqli_free_result($result);
    }
    else
    {
        die("Could not get sensor location id!");
    }

    return $measurement_unit_id;
}

function get_sensor_id($conn, $sensor_name)
{
    $sensor_id = 0;
    $sql_query = "SELECT sensor_id FROM Sensors WHERE sensor_name='$sensor_name'";

    if ($result = mysqli_query($conn, $sql_query))
    {
        $obj = $result->fetch_object();
        $sensor_id = $obj->sensor_id;
        mysqli_free_result($result);
    }
    else
    {
        die("Could not get sensor location id!");
    }

    return $sensor_id;
}

function get_measured_quantity_id($conn, $measured_quantity)
{
    $measured_quantity_id = 0;
    $sql_query = "SELECT measured_quantity_id FROM Measured_quantities WHERE measured_quantity_name='$measured_quantity'";

    if ($result = mysqli_query($conn, $sql_query))
    {
        $obj = $result->fetch_object();
        $measured_quantity_id = $obj->measured_quantity_id;
        mysqli_free_result($result);
    }
    else
    {
        die("Could not get sensor location id!");
    }

    return $measured_quantity_id;
}
?>