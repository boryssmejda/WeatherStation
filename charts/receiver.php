<?php

$parametersFromTheUser = file_get_contents("php://input");
$object = json_decode($parametersFromTheUser, false);

require("../db_connection.php");

$db_connection = new DB_connection();

$serverResponse = array();

$city_location_id = getCityLocationID($db_connection, $object->city);

foreach($object->physicalQuantities as $quantity)
{
    $quantity_id = getQuantityID($db_connection, $quantity);

    $measuredQuantitiesFromDatabase = getMeasuredQuantities($db_connection, $city_location_id, $quantity_id, $object->beginning, $object->end);
    array_push($serverResponse, $measuredQuantitiesFromDatabase);
}

echo json_encode($serverResponse);
?>

<?php

function  getCityLocationID($db_connection, $city_name)
{
    $sensor_location_id = 0;
    $sql_query = "SELECT sensor_location_id FROM Sensors_locations WHERE sensor_location_name='$city_name'";

    if ($result = mysqli_query($db_connection->get(), $sql_query))
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

function getQuantityID($db_connection, $quantity)
{
    $measured_quantity_id = 0;
    $sql_query = "SELECT measured_quantity_id FROM Measured_quantities WHERE measured_quantity_name='$quantity'";

    if ($result = mysqli_query($db_connection->get(), $sql_query))
    {
        $obj = $result->fetch_object();
        $measured_quantity_id = $obj->measured_quantity_id;
        mysqli_free_result($result);
    }
    else
    {
        die("Could not get quantity ID!");
    }

    return $measured_quantity_id;
}

function getMeasuredQuantities($db_connection, $city_location_id, $quantity_id, $beginning, $end)
{
    $sql_query = "SELECT measured_value, reading_time FROM Measurements 
        WHERE measured_quantity_id = $quantity_id AND sensor_location_id = $city_location_id AND (reading_time BETWEEN ('$beginning') AND ('$end'))";

    $measuredQuantitiesFromDatabase = array();

    if ($result = mysqli_query($db_connection->get(), $sql_query))
    {
        while ($obj = $result->fetch_object())
        {
            array_push($measuredQuantitiesFromDatabase, $obj);
        }
        $result->close();
    }
    else
    {
        die("Could not get quantity ID!");
    }

    return $measuredQuantitiesFromDatabase;
}

?>

