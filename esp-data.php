<!DOCTYPE html>
<html><body>
<?php

require('db_connection.php');
$conn = new DB_connection();

$sql = "SELECT id, sensor_id, measured_value, measurement_unit_id, measured_quantity_id, sensor_location_id, reading_time FROM Measurements ORDER BY id DESC";

echo '<table cellspacing="5" cellpadding="5">
      <tr>
        <td>ID</td>
        <td>Sensor ID</td>
        <td>Value</td>
        <td>Measurement Unit</td>
        <td>Measured Quantity</td>
        <td>Sensor Location</td>
        <td>Timestamp</td>
      </tr>';

if ($result = $conn->get()->query($sql))
{
    while ($row = $result->fetch_assoc())
    {
        $row_id = $row["id"];
        $row_sensor = $row["sensor_id"];
        $measured_value = $row["measured_value"];
        $measurement_unit_id = $row["measurement_unit_id"];
        $measured_quantity_id = $row["measured_quantity_id"];
        $sensor_location_id = $row["sensor_location_id"];
        $row_reading_time = $row["reading_time"];

        echo '<tr>
                <td>' . $row_id . '</td>
                <td>' . $row_sensor . '</td>
                <td>' . $measured_value . '</td>
                <td>' . $measurement_unit_id . '</td>
                <td>' . $measured_quantity_id . '</td>
                <td>' . $sensor_location_id . '</td>
                <td>' . $row_reading_time . '</td>
              </tr>';
    }
    $result->free();
}
?>
</table>
</body>
</html>