<?php
// Model layer - Handles direct database operations

require_once __DIR__ . '/../../config/database.php';

function findAllPatients($conn) {
    $query = "SELECT Patient_ID, F_name, L_name, DOB, Gender, Address, Email, Username FROM Patient";
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
    $query = "SELECT Patient_ID, F_name, L_name, DOB, Gender, Address, Email, Username FROM Patient WHERE Patient_ID = '$id'";
    $result = mysqli_query($conn, $query);
    
    $patient = null;
    if ($result) {
        $patient = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
    }
    
    return $patient;
}

function insertPatient($conn, $data) {
    $f_name = mysqli_real_escape_string($conn, $data['f_name']);
    $l_name = mysqli_real_escape_string($conn, $data['l_name']);
    $dob = mysqli_real_escape_string($conn, $data['dob']);
    $gender = mysqli_real_escape_string($conn, $data['gender']);
    $address = mysqli_real_escape_string($conn, $data['address']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $username = mysqli_real_escape_string($conn, $data['username']);
    $password = mysqli_real_escape_string($conn, $data['password']);
    
    // Hash the password before storing
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $query = "INSERT INTO Patient (F_name, L_name, DOB, Gender, Address, Email, Username, Password) 
              VALUES ('$f_name', '$l_name', '$dob', '$gender', '$address', '$email', '$username', '$hashed_password')";
    
    if (mysqli_query($conn, $query)) {
        return mysqli_insert_id($conn);
    }
    return false;
}

function updatePatientById($conn, $id, $data) {
    $id = mysqli_real_escape_string($conn, $id);
    $f_name = mysqli_real_escape_string($conn, $data['f_name']);
    $l_name = mysqli_real_escape_string($conn, $data['l_name']);
    $dob = mysqli_real_escape_string($conn, $data['dob']);
    $gender = mysqli_real_escape_string($conn, $data['gender']);
    $address = mysqli_real_escape_string($conn, $data['address']);
    $email = mysqli_real_escape_string($conn, $data['email']);
    $username = mysqli_real_escape_string($conn, $data['username']);
    
    // Only update password if it's provided
    $passwordUpdate = '';
    if (isset($data['password']) && !empty($data['password'])) {
        $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
        $passwordUpdate = ", Password = '$hashed_password'";
    }
    
    $query = "UPDATE Patient 
              SET F_name = '$f_name',
                  L_name = '$l_name',
                  DOB = '$dob',
                  Gender = '$gender',
                  Address = '$address',
                  Email = '$email',
                  Username = '$username'
                  $passwordUpdate
              WHERE Patient_ID = '$id'";
    
    return mysqli_query($conn, $query);
}

function deletePatientById($conn, $id) {
    $id = mysqli_real_escape_string($conn, $id);
    $query = "DELETE FROM patients WHERE id = '$id'";
    return mysqli_query($conn, $query);
}