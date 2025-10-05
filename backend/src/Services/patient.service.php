<?php
// Service layer - Handles business logic and validation

require_once __DIR__ . '/../Models/patient.model.php';

function validatePatientData($data) {
    $errors = [];
    $required_fields = ['first_name', 'last_name', 'email', 'phone', 'date_of_birth', 'address'];
    
    // Check required fields
    foreach ($required_fields as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            $errors[] = "$field is required";
        }
    }
    
    // Validate email if provided
    if (isset($data['email']) && !empty($data['email'])) {
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }
    }
    
    // Validate date if provided
    if (isset($data['date_of_birth']) && !empty($data['date_of_birth'])) {
        if (!strtotime($data['date_of_birth'])) {
            $errors[] = "Invalid date of birth format";
        }
    }
    
    return $errors;
}

function getAllPatients($conn) {
    return findAllPatients($conn);
}

function getPatient($conn, $id) {
    if (!is_numeric($id)) {
        throw new Exception("Invalid patient ID");
    }
    
    $patient = findPatientById($conn, $id);
    if (!$patient) {
        throw new Exception("Patient not found", 404);
    }
    
    return $patient;
}

function createPatient($conn, $data) {
    $errors = validatePatientData($data);
    if (!empty($errors)) {
        throw new Exception($errors[0]);
    }
    
    // Check if email already exists
    $existingPatient = findPatientByEmail($conn, $data['email']);
    if ($existingPatient) {
        throw new Exception("Email already registered");
    }
    
    $patientId = insertPatient($conn, $data);
    if (!$patientId) {
        throw new Exception("Failed to create patient");
    }
    
    return $patientId;
}

function updatePatient($conn, $id, $data) {
    if (!is_numeric($id)) {
        throw new Exception("Invalid patient ID");
    }
    
    $errors = validatePatientData($data);
    if (!empty($errors)) {
        throw new Exception($errors[0]);
    }
    
    // Check if patient exists
    $existingPatient = findPatientById($conn, $id);
    if (!$existingPatient) {
        throw new Exception("Patient not found", 404);
    }
    
    // Check if email is taken by another patient
    if ($data['email'] !== $existingPatient['email']) {
        $emailPatient = findPatientByEmail($conn, $data['email']);
        if ($emailPatient) {
            throw new Exception("Email already registered by another patient");
        }
    }
    
    $success = updatePatientById($conn, $id, $data);
    if (!$success) {
        throw new Exception("Failed to update patient");
    }
    
    return true;
}

function deletePatient($conn, $id) {
    if (!is_numeric($id)) {
        throw new Exception("Invalid patient ID");
    }
    
    // Check if patient exists
    $existingPatient = findPatientById($conn, $id);
    if (!$existingPatient) {
        throw new Exception("Patient not found", 404);
    }
    
    $success = deletePatientById($conn, $id);
    if (!$success) {
        throw new Exception("Failed to delete patient");
    }
    
    return true;
}

// Helper function for email checks
function findPatientByEmail($conn, $email) {
    $email = mysqli_real_escape_string($conn, $email);
    $query = "SELECT * FROM patients WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    
    $patient = null;
    if ($result) {
        $patient = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
    }
    
    return $patient;
}