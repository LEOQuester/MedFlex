<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

// Load dependencies
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/config/database.php';
require __DIR__ . '/src/Controllers/patient.controller.php';

// Create app
$app = AppFactory::create();

// Add error handling
$app->addErrorMiddleware(true, true, true);

// Add body parsing
$app->addBodyParsingMiddleware();

// CORS middleware
$app->add(function (Request $request, $handler) {
    $response = $handler->handle($request);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

// Patient Routes
$app->group('/api', function($app) {
    // GET /api/patients - Get all patients
    $app->get('/patients', 'handleGetAllPatients');
    
    // GET /api/patients/{id} - Get a specific patient
    $app->get('/patients/{id}', 'handleGetPatient');
    
    // POST /api/patients - Create a new patient
    $app->post('/patients', 'handleCreatePatient');
    
    // PUT /api/patients/{id} - Update a patient
    $app->put('/patients/{id}', 'handleUpdatePatient');
    
    // DELETE /api/patients/{id} - Delete a patient
    $app->delete('/patients/{id}', 'handleDeletePatient');
});

// Run app
$app->run();