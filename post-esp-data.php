<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("vendor/autoload.php");

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Create the logger
$logger = new Logger('my_logger');
// Now add some handlers
$logger->pushHandler(new StreamHandler(__DIR__.'/my_app.log', Logger::DEBUG));

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
        $logger->error("Could get JSON content");
    }
    else
    {
        $logger->info("Got JSON correctly!");
    }

    $weather_station_measurements = json_decode($json_result, true);

    if($weather_station_measurements == NULL)
    {
        $logger->error("Could not parse JSON!");
    }

    $api_key = test_input($weather_station_measurements['apiKeyValue']);

    if($api_key == $api_key_value)
    {
        $logger->info("Api Key correct!");

        require('db_connection.php');
        $conn = new DB_connection();

        $timestamp = get_timestamp($weather_station_measurements['weatherConditions']['timestamp']);

        $logger->info("Timestamp:", ['timestamp' => $weather_station_measurements['weatherConditions']['timestamp']]);
        $logger->info("Timestamp from JSON: ", ['timestamp' => $measurement_timestamp]);

        foreach($weather_station_measurements['weatherConditions']['values'] as $sensor_measurement)
        {
            $measured_value = get_measured_value_for_tuszyn($sensor_measurement['value']);
            $measurement_unit_id = get_measurement_unit_id($conn->get(), $sensor_measurement['unit']);
            $sensor_id = get_sensor_id($conn->get(), $sensor_measurement['sensor']);
            $measured_quantity_id = get_measured_quantity_id($conn->get(), $sensor_measurement['quantity']);
            $sensor_location_id = get_sensor_location_id($conn->get(), $weather_station_measurements['location']);

            $sql = "INSERT INTO Measurements (measured_value, measurement_unit_id, reading_time, sensor_id, measured_quantity_id, sensor_location_id)
                VALUES ($measured_value, $measurement_unit_id, '$timestamp', $sensor_id, $measured_quantity_id, $sensor_location_id)";

            execute_query($conn, $sql);

            /////////////////////////// Andrespol ////////////////////////////////

            $measured_value = get_measured_value_for_andrespol($sensor_measurement['value']);
            $sensor_location_id = get_sensor_location_id($conn->get(), 'Andrespol');

            $sql = "INSERT INTO Measurements (measured_value, measurement_unit_id, reading_time, sensor_id, measured_quantity_id, sensor_location_id)
                VALUES ($measured_value, $measurement_unit_id, '$timestamp', $sensor_id, $measured_quantity_id, $sensor_location_id)";

            execute_query($conn, $sql);

            // Bukowiec
            $measured_value = get_measured_value_for_bukowiec($sensor_measurement['value']);
            $sensor_location_id = get_sensor_location_id($conn->get(), 'Bukowiec');

            $sql = "INSERT INTO Measurements (measured_value, measurement_unit_id, reading_time, sensor_id, measured_quantity_id, sensor_location_id)
                VALUES ($measured_value, $measurement_unit_id, '$timestamp', $sensor_id, $measured_quantity_id, $sensor_location_id)";

            execute_query($conn, $sql);
        }
    }
    else
    {
        $logger->error("Wrong API Key provided.");
    }

}
else
{
    $logger->error("Not POST method was used!");
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

function get_timestamp($measurement_timestamp)
{
    $dateToBeModified = DateTime::createFromFormat("d/m/Y H:i:s", $measurement_timestamp);
    $timestamp = $dateToBeModified->format('Y-m-d H:i:s');

    return $timestamp;
}

function execute_query($db_conn, $sql_query)
{
    global $logger;

    $logger->info("SQL QUERY:", ['sql' => $sql_query]);

    if($db_conn->get()->query($sql_query) == true)
    {
        $logger->info("Successfull insertion!");
    }
    else
    {
        $logger->error("Insertion failed!");
        $logger->error("Error message: ", ['error' => $mysqli->error]);
    }
}

function get_measured_value_for_tuszyn($val)
{
    $measured_value = str_replace(',', '.', $val);
    $measured_value = floatval($measured_value);
    $measured_value = sprintf("%.2f", $measured_value);
    $measured_value = str_replace(',', '.', $measured_value);

    return $measured_value;
}

function get_measured_value_for_andrespol($val)
{
    $measured_value = str_replace(',', '.', $val);
    $measured_value += (1 + rand(0, 100) / 1000.0);
    $measured_value = floatval($measured_value);
    $measured_value = sprintf("%.2f", $measured_value);
    $measured_value = str_replace(',', '.', $measured_value);

    return $measured_value;
}

function get_measured_value_for_bukowiec($val)
{
    $measured_value = str_replace(',', '.', $val);
    $measured_value += (2 - rand(10, 100) / 1000.0);
    $measured_value = floatval($measured_value);
    $measured_value = sprintf("%.2f", $measured_value);
    $measured_value = str_replace(',', '.', $measured_value);

    return $measured_value;
}

?>