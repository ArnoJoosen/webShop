<?php
class WebShopErrorHandler extends Exception {
    protected $userMessage = "";
    public function getUserMessage() {
        return $this->userMessage;
    }
}

// exeception for database errors
class DatabaseError extends WebShopErrorHandler {
    public function __construct($message, $userMessage = "We're sorry, something went wrong. Please try again later.") {
        parent::__construct($message);
        $this->userMessage = $userMessage;
    }
}

// exception for file upload errors
class UploadException extends WebShopErrorHandler {
    public function __construct($message, $userMessage = "We're sorry, something went wrong. Please try again later.") {
        parent::__construct($message);
        $this->userMessage = $userMessage;
    }
}

// exception for input validation errors of the user
class InputValidationException extends WebShopErrorHandler {
    public function __construct($message, $userMessage = "We're sorry, something went wrong. Please try again later.") {
        parent::__construct($message);
        $this->userMessage = $userMessage;
    }
}

// exception for errors in the session
class SessionException extends WebShopErrorHandler {
    public function __construct($message, $userMessage = "We're sorry, something went wrong. Please try again later.") {
        parent::__construct($message);
        $this->userMessage = $userMessage;
    }
}

// exception for errors when data or page is not found
class NotFoundError extends WebShopErrorHandler {
    public function __construct($message, $userMessage = "We're sorry, something went wrong. Please try again later.") {
        parent::__construct($message);
        $this->userMessage = $userMessage;
    }
}

// exception for errors when the user is not authorized
class UnauthorizedError extends WebShopErrorHandler {
    public function __construct($message, $userMessage = "We're sorry, something went wrong. Please try again later.") {
        parent::__construct($message);
        $this->userMessage = $userMessage;
    }
}

// execption for errors when the user is not authenticated for admin actions
class AdminError extends WebShopErrorHandler {
    public function __construct($message, $userMessage = "Please log in as an admin to access this page.") {
        $this->userMessage = $userMessage;
    }
}

// exception for order processing errors
class OrderProcessingError extends WebShopErrorHandler {
    public function __construct($message, $userMessage = "We're sorry, something went wrong. Please try again later.") {
        parent::__construct($message);
        $this->userMessage = $userMessage;
    }
}

function logError($error_message, $log_file = __DIR__ . '/../logs/error.log') {
    $log_message = date('Y-m-d H:i:s') . " - " . $error_message . PHP_EOL;
    error_log($log_message, 3, $log_file);
}

function handleError(Exception $e) {
    $error_message = "Error: " . $e->getMessage() . " in file: " . $e->getFile() . " on line: " . $e->getLine();

    switch ($e) {
        case $e instanceof DatabaseError:
            $error_message = "Database Error: " . $error_message;
            logError($error_message);
            break;
        case $e instanceof UploadException:
            $error_message = "Upload Error: " . $error_message;
            logError($error_message);
            break;
        case $e instanceof InputValidationException:
            $error_message = "Input Validation Error: " . $error_message;
            // For debugging during development
            //logError($error_message, $log_file);
            break;
        case $e instanceof SessionException:
            $error_message = "Session Error: " . $error_message;
            logError($error_message);
            break;
        case $e instanceof NotFoundError:
            $error_message = "Not Found Error: " . $error_message;
            logError($error_message);
            break;
        case $e instanceof UnauthorizedError:
            $error_message = "Unauthorized Error: " . $error_message;
            logError($error_message);
            break;
        case $e instanceof AdminError:
            $error_message = "Admin Error: " . $error_message;
            logError($error_message);
            break;
        case $e instanceof OrderProcessingError:
            $error_message = "Order Processing Error: " . $error_message;
            logError($error_message);
            break;
        default:
            $error_message = "Unknown Error: " . $error_message;
            logError($error_message);
            break;
    }

    // User-friendly error message
    $user_message = "We're sorry, something went wrong. Please try again later.";

    // specific user-friendly error messages for different types of errors
    if ($e instanceof DatabaseError) {
        $user_message = $e->getUserMessage();
    } elseif ($e instanceof UploadException) {
        $user_message = $e->getUserMessage();
    } elseif ($e instanceof InputValidationException) {
        $user_message = $e->getUserMessage();
    } elseif ($e instanceof SessionException) {
        $user_message = $e->getUserMessage();
    } elseif ($e instanceof NotFoundError) {
        $user_message = $e->getUserMessage();
    } elseif ($e instanceof UnauthorizedError) {
        $user_message = $e->getUserMessage();
    } elseif ($e instanceof AdminError) {
        $user_message = $e->getUserMessage();
    } elseif ($e instanceof OrderProcessingError) {
        $user_message = $e->getUserMessage();
    }

    // For debugging during development
    // $user_message = $user_message . $error_message;

    return $user_message;
}
?>
