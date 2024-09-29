<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Start the session to store messages
session_start();

// Database credentials
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = "";     // Default XAMPP password is empty
$dbname = "agriculturedb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize message variable
$message = "";

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Determine which form was submitted based on the 'form_type' hidden input
    $form_type = $_POST['form_type'];

    switch ($form_type) {
        case 'add_farmer':
            // Retrieve and sanitize form data
            $name = $conn->real_escape_string($_POST['name']);
            $contact = $conn->real_escape_string($_POST['contact']);
            $email = $conn->real_escape_string($_POST['email']);
            $address = $conn->real_escape_string($_POST['address']);

            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO farmers (name, contact, email, address) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $contact, $email, $address);

            // Execute and check
            if ($stmt->execute()) {
                $message = "New farmer added successfully!";
            } else {
                $message = "Error adding farmer: " . $stmt->error;
            }

            $stmt->close();
            break;

        case 'add_field':
            // Retrieve and sanitize form data
            $farmer_id = intval($_POST['farmer_id']);
            $field_name = $conn->real_escape_string($_POST['field_name']);
            $location = $conn->real_escape_string($_POST['location']);
            $size_in_acres = floatval($_POST['size_in_acres']);
            $soil_type = $conn->real_escape_string($_POST['soil_type']);

            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO fields (farmer_id, field_name, location, size_in_acres, soil_type) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issds", $farmer_id, $field_name, $location, $size_in_acres, $soil_type);

            // Execute and check
            if ($stmt->execute()) {
                $message = "New field added successfully!";
            } else {
                $message = "Error adding field: " . $stmt->error;
            }

            $stmt->close();
            break;

        case 'add_instrument':
            // Retrieve and sanitize form data
            $field_id = intval($_POST['field_id']);
            $instrument_type = $conn->real_escape_string($_POST['instrument_type']);
            $serial_number = $conn->real_escape_string($_POST['serial_number']);
            $installation_date = $_POST['installation_date']; // Assuming date is properly formatted
            $status = $conn->real_escape_string($_POST['status']);

            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO agricultureinstruments (field_id, instrument_type, serial_number, installation_date, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("issss", $field_id, $instrument_type, $serial_number, $installation_date, $status);

            // Execute and check
            if ($stmt->execute()) {
                $message = "New agriculture instrument added successfully!";
            } else {
                $message = "Error adding instrument: " . $stmt->error;
            }

            $stmt->close();
            break;

        case 'add_crop':
            // Retrieve and sanitize form data
            $field_id = intval($_POST['field_id']);
            $crop_name = $conn->real_escape_string($_POST['crop_name']);
            $growth_duration = intval($_POST['growth_duration']);
            $water_needs = $conn->real_escape_string($_POST['water_needs']);
            $sunlight_needs = $conn->real_escape_string($_POST['sunlight_needs']);

            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO crops (field_id, crop_name, growth_duration, water_needs, sunlight_needs) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("isiss", $field_id, $crop_name, $growth_duration, $water_needs, $sunlight_needs);

            // Execute and check
            if ($stmt->execute()) {
                $message = "New crop added successfully!";
            } else {
                $message = "Error adding crop: " . $stmt->error;
            }

            $stmt->close();
            break;

        case 'add_sensordata':
            // Retrieve and sanitize form data
            $instrument_id = intval($_POST['instrument_id']);
            $data_type = $conn->real_escape_string($_POST['data_type']);
            $value = floatval($_POST['value']);
            $timestamp = $_POST['timestamp']; // Format: YYYY-MM-DDTHH:MM

            // Convert timestamp to MySQL DATETIME format
            $timestamp = date("Y-m-d H:i:s", strtotime($timestamp));

            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO sensordata (instrument_id, timestamp, data_type, value) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("issd", $instrument_id, $timestamp, $data_type, $value);

            // Execute and check
            if ($stmt->execute()) {
                $message = "Sensor data added successfully!";
            } else {
                $message = "Error adding sensor data: " . $stmt->error;
            }

            $stmt->close();
            break;

        case 'add_weatherdata':
            // Retrieve and sanitize form data
            $field_id = intval($_POST['field_id']);
            $date = $_POST['date']; // Format: YYYY-MM-DD
            $temperature = floatval($_POST['temperature']);
            $rainfall = floatval($_POST['rainfall']);
            $humidity = floatval($_POST['humidity']);

            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO weatherdata (field_id, date, temperature, rainfall, humidity) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("isddd", $field_id, $date, $temperature, $rainfall, $humidity);

            // Execute and check
            if ($stmt->execute()) {
                $message = "Weather data added successfully!";
            } else {
                $message = "Error adding weather data: " . $stmt->error;
            }

            $stmt->close();
            break;

        default:
            $message = "Unknown form submission.";
    }

    // Store the message in session to display after redirect
    $_SESSION['message'] = $message;

    // Redirect to the same page to prevent form resubmission
    header("Location: index.php");
    exit();
}

// Retrieve and clear the message from session
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agriculture Data Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
        }
        h2 {
            color: #333;
        }
        .container {
            display: flex;
            flex-wrap: wrap;
        }
        .form-container, .data-container {
            background-color: #fff;
            padding: 20px;
            margin: 10px;
            border-radius: 5px;
            flex: 1 1 45%;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        form {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"], input[type="email"], input[type="number"], input[type="date"], input[type="datetime-local"], select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            margin-top: 15px;
            padding: 10px 15px;
            background-color: #4CAF50;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 3px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 3px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        @media (max-width: 800px) {
            .form-container, .data-container {
                flex: 1 1 100%;
            }
        }
    </style>
</head>
<body>

    <h1>Agriculture Data Management System</h1>

    <!-- Display Message -->
    <?php if (!empty($message)): ?>
        <div class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <div class="container">

        <!-- Form Containers -->
        <div class="form-container">
            <h2>Add New Farmer</h2>
            <form action="index.php" method="POST">
                <input type="hidden" name="form_type" value="add_farmer">
                <label for="name">Farmer Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="contact">Contact:</label>
                <input type="text" id="contact" name="contact" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>

                <input type="submit" value="Add Farmer">
            </form>
        </div>

        <div class="form-container">
            <h2>Add New Field</h2>
            <form action="index.php" method="POST">
                <input type="hidden" name="form_type" value="add_field">
                <label for="farmer_id">Farmer:</label>
                <select id="farmer_id" name="farmer_id" required>
                    <option value="">-- Select Farmer --</option>
                    <?php
                    // Fetch farmers from the database
                    $result = $conn->query("SELECT farmer_id, name FROM farmers");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['farmer_id'] . "'>" . htmlspecialchars($row['name']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>No farmers available</option>";
                    }
                    ?>
                </select>

                <label for="field_name">Field Name:</label>
                <input type="text" id="field_name" name="field_name" required>

                <label for="location">Location:</label>
                <input type="text" id="location" name="location" required>

                <label for="size_in_acres">Size (acres):</label>
                <input type="number" step="0.1" id="size_in_acres" name="size_in_acres" required>

                <label for="soil_type">Soil Type:</label>
                <input type="text" id="soil_type" name="soil_type" required>

                <input type="submit" value="Add Field">
            </form>
        </div>

        <div class="form-container">
            <h2>Add New Agriculture Instrument</h2>
            <form action="index.php" method="POST">
                <input type="hidden" name="form_type" value="add_instrument">
                <label for="field_id">Field:</label>
                <select id="field_id" name="field_id" required>
                    <option value="">-- Select Field --</option>
                    <?php
                    // Fetch fields from the database
                    $result = $conn->query("SELECT field_id, field_name FROM fields");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['field_id'] . "'>" . htmlspecialchars($row['field_name']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>No fields available</option>";
                    }
                    ?>
                </select>

                <label for="instrument_type">Instrument Type:</label>
                <input type="text" id="instrument_type" name="instrument_type" required>

                <label for="serial_number">Serial Number:</label>
                <input type="text" id="serial_number" name="serial_number" required>

                <label for="installation_date">Installation Date:</label>
                <input type="date" id="installation_date" name="installation_date" required>

                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="">-- Select Status --</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>

                <input type="submit" value="Add Instrument">
            </form>
        </div>

        <div class="form-container">
            <h2>Add New Crop</h2>
            <form action="index.php" method="POST">
                <input type="hidden" name="form_type" value="add_crop">
                <label for="field_id_crop">Field:</label>
                <select id="field_id_crop" name="field_id" required>
                    <option value="">-- Select Field --</option>
                    <?php
                    // Fetch fields from the database
                    $result = $conn->query("SELECT field_id, field_name FROM fields");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['field_id'] . "'>" . htmlspecialchars($row['field_name']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>No fields available</option>";
                    }
                    ?>
                </select>

                <label for="crop_name">Crop Name:</label>
                <input type="text" id="crop_name" name="crop_name" required>

                <label for="growth_duration">Growth Duration (days):</label>
                <input type="number" id="growth_duration" name="growth_duration" required>

                <label for="water_needs">Water Needs:</label>
                <select id="water_needs" name="water_needs" required>
                    <option value="">-- Select Water Needs --</option>
                    <option value="High">High</option>
                    <option value="Medium">Medium</option>
                    <option value="Low">Low</option>
                </select>

                <label for="sunlight_needs">Sunlight Needs:</label>
                <select id="sunlight_needs" name="sunlight_needs" required>
                    <option value="">-- Select Sunlight Needs --</option>
                    <option value="Full Sun">Full Sun</option>
                    <option value="Partial Sun">Partial Sun</option>
                </select>

                <input type="submit" value="Add Crop">
            </form>
        </div>

        <div class="form-container">
            <h2>Add Sensor Data</h2>
            <form action="index.php" method="POST">
                <input type="hidden" name="form_type" value="add_sensordata">
                <label for="instrument_id">Instrument:</label>
                <select id="instrument_id" name="instrument_id" required>
                    <option value="">-- Select Instrument --</option>
                    <?php
                    // Fetch instruments from the database
                    $result = $conn->query("SELECT instrument_id, serial_number FROM agricultureinstruments");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['instrument_id'] . "'>" . htmlspecialchars($row['serial_number']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>No instruments available</option>";
                    }
                    ?>
                </select>

                <label for="data_type">Data Type:</label>
                <select id="data_type" name="data_type" required>
                    <option value="">-- Select Data Type --</option>
                    <option value="Temperature">Temperature</option>
                    <option value="Humidity">Humidity</option>
                    <option value="Soil Moisture">Soil Moisture</option>
                    <!-- Add more data types as needed -->
                </select>

                <label for="value">Value:</label>
                <input type="number" step="0.1" id="value" name="value" required>

                <label for="timestamp">Timestamp:</label>
                <input type="datetime-local" id="timestamp" name="timestamp" required>

                <input type="submit" value="Add Sensor Data">
            </form>
        </div>

        <div class="form-container">
            <h2>Add Weather Data</h2>
            <form action="index.php" method="POST">
                <input type="hidden" name="form_type" value="add_weatherdata">
                <label for="field_id_weather">Field:</label>
                <select id="field_id_weather" name="field_id" required>
                    <option value="">-- Select Field --</option>
                    <?php
                    // Fetch fields from the database
                    $result = $conn->query("SELECT field_id, field_name FROM fields");
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row['field_id'] . "'>" . htmlspecialchars($row['field_name']) . "</option>";
                        }
                    } else {
                        echo "<option value=''>No fields available</option>";
                    }
                    ?>
                </select>

                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required>

                <label for="temperature_weather">Temperature (°C):</label>
                <input type="number" step="0.1" id="temperature_weather" name="temperature" required>

                <label for="rainfall">Rainfall (mm):</label>
                <input type="number" step="0.1" id="rainfall" name="rainfall" required>

                <label for="humidity_weather">Humidity (%):</label>
                <input type="number" step="0.1" id="humidity_weather" name="humidity" required>

                <input type="submit" value="Add Weather Data">
            </form>
        </div>

        <!-- Data Display Containers -->
        <div class="data-container">
            <h2>View Farmers</h2>
            <?php
            $sql = "SELECT * FROM farmers";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Farmer ID</th><th>Name</th><th>Contact</th><th>Email</th><th>Address</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['farmer_id']) . "</td>
                            <td>" . htmlspecialchars($row['name']) . "</td>
                            <td>" . htmlspecialchars($row['contact']) . "</td>
                            <td>" . htmlspecialchars($row['email']) . "</td>
                            <td>" . htmlspecialchars($row['address']) . "</td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No farmers found.</p>";
            }
            ?>
        </div>

        <div class="data-container">
            <h2>View Fields</h2>
            <?php
            $sql = "SELECT fields.*, farmers.name AS farmer_name FROM fields JOIN farmers ON fields.farmer_id = farmers.farmer_id";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Field ID</th><th>Farmer Name</th><th>Field Name</th><th>Location</th><th>Size (acres)</th><th>Soil Type</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['field_id']) . "</td>
                            <td>" . htmlspecialchars($row['farmer_name']) . "</td>
                            <td>" . htmlspecialchars($row['field_name']) . "</td>
                            <td>" . htmlspecialchars($row['location']) . "</td>
                            <td>" . htmlspecialchars($row['size_in_acres']) . "</td>
                            <td>" . htmlspecialchars($row['soil_type']) . "</td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No fields found.</p>";
            }
            ?>
        </div>

        <div class="data-container">
            <h2>View Agriculture Instruments</h2>
            <?php
            $sql = "SELECT agricultureinstruments.*, fields.field_name FROM agricultureinstruments JOIN fields ON agricultureinstruments.field_id = fields.field_id";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Instrument ID</th><th>Field Name</th><th>Type</th><th>Serial Number</th><th>Installation Date</th><th>Status</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['instrument_id']) . "</td>
                            <td>" . htmlspecialchars($row['field_name']) . "</td>
                            <td>" . htmlspecialchars($row['instrument_type']) . "</td>
                            <td>" . htmlspecialchars($row['serial_number']) . "</td>
                            <td>" . htmlspecialchars($row['installation_date']) . "</td>
                            <td>" . htmlspecialchars($row['status']) . "</td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No agriculture instruments found.</p>";
            }
            ?>
        </div>

        <div class="data-container">
            <h2>View Crops</h2>
            <?php
            $sql = "SELECT crops.*, fields.field_name FROM crops JOIN fields ON crops.field_id = fields.field_id";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Crop ID</th><th>Field Name</th><th>Crop Name</th><th>Growth Duration (days)</th><th>Water Needs</th><th>Sunlight Needs</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['crop_id']) . "</td>
                            <td>" . htmlspecialchars($row['field_name']) . "</td>
                            <td>" . htmlspecialchars($row['crop_name']) . "</td>
                            <td>" . htmlspecialchars($row['growth_duration']) . "</td>
                            <td>" . htmlspecialchars($row['water_needs']) . "</td>
                            <td>" . htmlspecialchars($row['sunlight_needs']) . "</td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No crops found.</p>";
            }
            ?>
        </div>

        <div class="data-container">
    <h2>View Sensor Data</h2>
    <?php
    $sql = "SELECT sensordata.*, agricultureinstruments.serial_number 
            FROM sensordata 
            JOIN agricultureinstruments ON sensordata.instrument_id = agricultureinstruments.instrument_id 
            ORDER BY sensordata.timestamp DESC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<tr>
                <th>Data ID</th>
                <th>Instrument Serial Number</th>
                <th>Timestamp</th>
                <th>Data Type</th>
                <th>Value</th>
              </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['data_id']) . "</td>
                    <td>" . htmlspecialchars($row['serial_number']) . "</td>
                    <td>" . htmlspecialchars($row['timestamp']) . "</td>
                    <td>" . htmlspecialchars($row['data_type']) . "</td>
                    <td>" . htmlspecialchars($row['value']) . "</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No sensor data found.</p>";
    }
    ?>
</div>
