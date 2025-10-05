<?php
// Database connection management functions

function closeConnection($conn) {
    if ($conn) {
        mysqli_close($conn);
    }
}

// Register shutdown function to ensure connection is closed
register_shutdown_function(function() {
    global $conn;
    closeConnection($conn);
});