<?php
// Model layer - Handles direct database operations

require_once __DIR__ . '/../../config/database.php';

function findAllPatients($conn) {
    $query = "SELECT * FROM patients";
    $result = mysqli_query($conn, $query);
    
    $patients = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $patients[] = $row;
        }
        mysqli_free_result($result);
    }
    
    return $patients;
}

function findPatientById($conn, $id) {
    $id = mysqli_real_escape_string($conn, $id);
    $query = "SELECT * FROM patients WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    
    $patient = null;
    if ($result) {
        $patient = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
    }
    
    return $patient;
}

function insertPatient($conn, $data) {
    $first_name = mysqli_real_escape_string($conn, $data['first_name']);
    $last_name = mysqli_real_escape_string($conn, $data['last_name']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $phone = mysqli_real_escape_string($conn, $data['phone']);
    $date_of_birth = mysqli_real_escape_string($conn, $data['date_of_birth']);
    $address = mysqli_real_escape_string($conn, $data['address']);
    
    $query = "INSERT INTO patients (first_name, last_name, email, phone, date_of_birth, address) 
              VALUES ('$first_name', '$last_name', '$email', '$phone', '$date_of_birth', '$address')";
    
    if (mysqli_query($conn, $query)) {
        return mysqli_insert_id($conn);
    }
    return false;
}

function updatePatientById($conn, $id, $data) {
    $id = mysqli_real_escape_string($conn, $id);
    $first_name = mysqli_real_escape_string($conn, $data['first_name']);
    $last_name = mysqli_real_escape_string($conn, $data['last_name']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $phone = mysqli_real_escape_string($conn, $data['phone']);
    $date_of_birth = mysqli_real_escape_string($conn, $data['date_of_birth']);
    $address = mysqli_real_escape_string($conn, $data['address']);
    
    $query = "UPDATE patients 
              SET first_name = '$first_name',
                  last_name = '$last_name',
                  email = '$email',
                  phone = '$phone',
                  date_of_birth = '$date_of_birth',
                  address = '$address'
              WHERE id = '$id'";
    
    return mysqli_query($conn, $query);
}

function deletePatientById($conn, $id) {
    $id = mysqli_real_escape_string($conn, $id);
    $query = "DELETE FROM patients WHERE id = '$id'";
    return mysqli_query($conn, $query);
}