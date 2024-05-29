<?php
error_reporting(E_ALL & ~E_DEPRECATED);

 include("_includes/config.inc");
 include("_includes/dbconnect.inc");
 include("_includes/functions.inc");
require_once 'vendor/autoload.php'; // Include Composer's autoloader to load Faker library


// Initialize Faker generator
$faker = Faker\Factory::create();

// Function to generate random date of birth
function generateDOB($faker) {
    return $faker->dateTimeBetween('-25 years', '-18 years')->format('Y-m-d');
}

// Function to generate random postcode
function generatePostcode($faker) {
    return $faker->postcode;
}

// Insert 5 student records into the database
for ($i = 0; $i < 5; $i++) {
    $studentid = '2' . str_pad(mt_rand(0, 999999), 7, '0', STR_PAD_LEFT); // Generate random student ID
    $password = password_hash($faker->password, PASSWORD_DEFAULT); // Generate random password
    $dob = generateDOB($faker); // Generate random date of birth
    $firstname = $faker->firstName;
    $lastname = $faker->lastName;
    $house = $faker->buildingNumber . ' ' . $faker->streetName;
    $town = $faker->city;
    $county = $faker->state;
    $country = $faker->country;
    $postcode = generatePostcode($faker); // Generate random postcode

    // Insert student record into the database
    $stmt = $conn->prepare("INSERT INTO student (studentid, password, dob, firstname, lastname, house, town, county, country, postcode) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $studentid, $password, $dob, $firstname, $lastname, $house, $town, $county, $country, $postcode);
    $stmt->execute();
}

echo "5 student records inserted successfully!";
?>
