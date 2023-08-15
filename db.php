<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

include 'credentials.php';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);


function formatUKAddress($address) {
    // Split the address into lines
    $lines = explode("\n", $address);
    
    // Process each line
    foreach ($lines as &$line) {
        $line = ucwords(strtolower($line));  // Convert the first letter of each word to uppercase
    }

    // Join the lines back together
    $formattedAddress = implode("\n", $lines);

    // Ensure the last 7 characters are uppercase
    $formattedAddress = substr($formattedAddress, 0, -7) . strtoupper(substr($formattedAddress, -7));

    return $formattedAddress;
}







/* CRUD */
// Create: This operation is used to insert or add new records to the database.
// Read: This operation is used to retrieve or fetch data from the database.
// Update: This operation is used to modify or change existing data in the database.
// Delete: This operation is used to remove or delete records from the database.

/* Customer Functions: */
// Add New customer                          function addNewCustomer(PDO $pdo, $customerData) {
// Fetch List of Customers in archive        function fetchArchivedCustomers(PDO $pdo) {
// Fetch List of Customers not in archive    function fetchActiveCustomers(PDO $pdo) {
// Update customer by Customer ID            function updateCustomer(PDO $pdo, $customerId, $customerData): bool {
// Delete customer by CustomerID             function deleteCustomer(PDO $pdo, int $customerId): bool {
// Fetch Customer from CustomerID            function fetchCustomerDetails(PDO $pdo, $customerID) {

/* Customer Event Functions: */
// List all events for all customers         function fetchAllEvents(PDO $pdo) {
// Add new Event for CustomerID              function addNewEvent(PDO $pdo, $customerID, $EventDate, $EventType, $isBooked) {
// Fetch List of Events from CustomerID      function fetchEventsForCustomer(PDO $pdo, $customerID) {
// Fetch Event by EventID                    function fetchEvent(PDO $pdo, $EventID) {
// Update Event by EventID                   function updateEvent(PDO $pdo, $EventID, $EventData) {
// Delete Event by EventID                   function deleteEventWithQuotes(PDO $pdo, $EventID): bool {

/* Quote Functions: */
// Fetch List of QuoteIDs from EventID       function fetchQuoteIDs(PDO $pdo, $EventID) {
// Fetch Quote from a QuoteID                function fetchQuote(PDO $pdo, $quoteID) {
// Fetch Quote lines for a QuoteID           function fetchQuoteLines(PDO $pdo, $quoteID) {
// Add new Quote for EventID                 function addQuote(PDO $pdo, $EventID, $quoteData) {
// Update Quote for a QuoteID                function updateQuote(PDO $pdo, $quoteID, $quoteData): bool {
// Delete Quote for a QuoteID                function deleteQuote(PDO $pdo, $quoteID) {

/* Quote Line Functions: */
// Add new Quote Line for QuoteID            function addQuoteLine(PDO $pdo, $quoteID, $quoteLineData) {
// Update a QuoteLine for a QuoteLineID      function updateQuoteLine(PDO $pdo, $quoteLineID, $quoteLineData): bool {
// Delete a QuoteLineID                      function deleteQuoteLine(PDO $pdo, $quoteLineID): bool {
	




/* CUSTOMER FUNCTIONS 
This is my MySQL table for the customers database:
CREATE TABLE Customers (
    CustomerID INT AUTO_INCREMENT,
    FirstName VARCHAR(255),
    LastName VARCHAR(255),
    PhoneNumber VARCHAR(20),
    Email VARCHAR(255),
    DeliveryAddress VARCHAR(255),
	IsArchived tinyint(1),
    PRIMARY KEY (CustomerID)
);

 $customerData = array(
    "FirstName" => "John",
    "LastName" => "Doe",
    "PhoneNumber" => "07234567890",
    "Email" => "johndoe@example.com",
    "DeliveryAddress" => "123 Main St, Anytown, UK",
	"IsArchived" => false
); 

*/

function addNewCustomer(PDO $pdo, $customerData) {
    try {
        // Start a transaction
        $pdo->beginTransaction();

        // Prepare an SQL query to insert a new customer into the Customers table
        $stmt = $pdo->prepare("INSERT INTO Customers (FirstName, LastName, PhoneNumber, Email, DeliveryAddress, IsArchived) VALUES (:FirstName, :LastName, :PhoneNumber, :Email, :DeliveryAddress, :IsArchived)");

        // Bind parameters
        $stmt->bindParam(':FirstName', $customerData['FirstName']);
        $stmt->bindParam(':LastName', $customerData['LastName']);
        $stmt->bindParam(':PhoneNumber', $customerData['PhoneNumber']);
        $stmt->bindParam(':Email', $customerData['Email']);
        $stmt->bindParam(':DeliveryAddress', $customerData['DeliveryAddress']);
        $stmt->bindParam(':IsArchived', $customerData['IsArchived']);

        // Execute the query
        $stmt->execute();

        // Check for errors
        $errorInfo = $stmt->errorInfo();
        if ($errorInfo[0] != '00000') {
            echo '<pre>';
            var_dump($errorInfo);
            echo '</pre>';

            // Rollback the transaction and terminate the script
            $pdo->rollback();
            exit();
        }

        // Commit the transaction
        $pdo->commit();

        // Print the ID of the newly inserted customer
        $lastInsertId = $pdo->lastInsertId();
        //echo 'New customer ID: ' . $lastInsertId;

        // Return the ID of the newly inserted customer
        return $lastInsertId;
    } catch (PDOException $e) {
        // Rollback the transaction
        $pdo->rollback();

        // Print the error message
        echo "Error!: " . $e->getMessage() . "</br>";

        // Return false
        return false;
    }
}

function fetchArchivedCustomers(PDO $pdo) {
    // Start the transaction
    $pdo->beginTransaction();

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare('SELECT * FROM Customers WHERE IsArchived = ?');

        // Execute the SQL statement with the parameter
        $stmt->execute([1]);

        // Fetch the results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Commit the transaction
        $pdo->commit();

        // Return the results
        return $results;

    } catch(PDOException $e) {
        // In case of error, rollback the transaction
        $pdo->rollback();

        // Print the error message
        print "Error!: " . $e->getMessage() . "</br>";

        // Print the var_dump of the errorInfo
        var_dump($pdo->errorInfo());
    }
}

function fetchActiveCustomers(PDO $pdo) {
    // Start the transaction
    $pdo->beginTransaction();

    try {
        // Prepare the SQL statement
        $stmt = $pdo->prepare('SELECT * FROM Customers WHERE IsArchived = ?');

        // Execute the SQL statement with the parameter
        $stmt->execute([0]);

        // Fetch the results
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Commit the transaction
        $pdo->commit();

        // Return the results
        return $results;

    } catch(PDOException $e) {
        // In case of error, rollback the transaction
        $pdo->rollback();

        // Print the error message
        print "Error!: " . $e->getMessage() . "</br>";

        // Print the var_dump of the errorInfo
        var_dump($pdo->errorInfo());
    }
}

function updateCustomer(PDO $pdo, $customerId, $customerData): bool {
    /*
	
    $customerData = [
    'FirstName' => 'John',
    'LastName' => 'Doe',
    'Email' => 'john.doe@example.com'
    ];
	
    In this example, the FirstName will be updated to 'John', LastName 
	to 'Doe', and Email to 'john.doe@example.com'.

    Remember that you should only include the fields that you want to 
	update in the $customerData array. If a field is not included in 
	the array, its value should remain unchanged in the database.
	
    */

    // Start the transaction
    try {
        $pdo->beginTransaction();

        // Construct the SQL query
        $query = "UPDATE Customers SET ";
        $params = array();
        foreach ($customerData as $field => $value) {
            $query .= "`".str_replace("`", "``", $field)."` = :".$field.", ";
            $params[$field] = $value;
        }
        $query = substr($query, 0, -2); // remove last comma
        $query .= " WHERE CustomerID = :customerId";
        $params['customerId'] = $customerId;

        // Prepare and execute the SQL query
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        // Check for faults
        if ($stmt->errorCode() != '00000') {
            var_dump($stmt->errorInfo());
        }

        // Commit the transaction
        $pdo->commit();

        return true;
    } catch(PDOException $e) {
        // Rollback the transaction on error
        $pdo->rollback();
        print "Error!: " . $e->getMessage() . "<br/>";
        return false;
    }
}

function deleteCustomer(PDO $pdo, int $customerId): bool {
    try {
        $pdo->beginTransaction();

        // Sanitize inputs
        $customerId = filter_var($customerId, FILTER_SANITIZE_NUMBER_INT);

        // Prepare statements
        $deleteQuoteItemsStmt = $pdo->prepare("
            DELETE QuoteItems FROM QuoteItems
            INNER JOIN Quotes ON Quotes.QuoteID = QuoteItems.QuoteID
            INNER JOIN Events ON Events.EventID = Quotes.EventID
            WHERE Events.CustomerID = :customerId
        ");
        $deleteQuotesStmt = $pdo->prepare("
            DELETE Quotes FROM Quotes
            INNER JOIN Events ON Events.EventID = Quotes.EventID
            WHERE Events.CustomerID = :customerId
        ");
        $deleteEventsStmt = $pdo->prepare("DELETE FROM Events WHERE CustomerID = :customerId");
        $deleteCustomerStmt = $pdo->prepare("DELETE FROM Customers WHERE CustomerID = :customerId");

        // Bind parameters and execute statements
        $deleteQuoteItemsStmt->bindParam(':customerId', $customerId, PDO::PARAM_INT);
        $deleteQuotesStmt->bindParam(':customerId', $customerId, PDO::PARAM_INT);
        $deleteEventsStmt->bindParam(':customerId', $customerId, PDO::PARAM_INT);
        $deleteCustomerStmt->bindParam(':customerId', $customerId, PDO::PARAM_INT);
        
        $deleteQuoteItemsStmt->execute();
        $deleteQuotesStmt->execute();
        $deleteEventsStmt->execute();
        $deleteCustomerStmt->execute();

        // Check for faults and report issues
        if ($deleteQuoteItemsStmt->errorCode() != '00000' || 
            $deleteQuotesStmt->errorCode() != '00000' || 
            $deleteEventsStmt->errorCode() != '00000' || 
            $deleteCustomerStmt->errorCode() != '00000') {

            var_dump($deleteQuoteItemsStmt->errorInfo());
            var_dump($deleteQuotesStmt->errorInfo());
            var_dump($deleteEventsStmt->errorInfo());
            var_dump($deleteCustomerStmt->errorInfo());
            $pdo->rollback();
            return false;
        } else {
            $pdo->commit();
            return true;
        }

    } catch(PDOException $e) {
        $pdo->rollback();
        print "Error!: " . $e->getMessage();
        return false;
    }
}

function fetchCustomerDetails(PDO $pdo, $customerID) {
    // SQL query
    $sql = 'SELECT * FROM Customers WHERE CustomerID = ?';

    try {
        // Begin transaction
        $pdo->beginTransaction();

        // Prepare statement
        $stmt = $pdo->prepare($sql);

        // Execute statement with customerID
        $stmt->execute([$customerID]);

        // Commit transaction
        $pdo->commit();

        // Fetch row as an associative array
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // If no matching customer is found, return null
        if ($result === false) {
            return null;
        }

        // Return the fetched row
        return $result;

    } catch(PDOException $e) {
        // Rollback transaction in case of an error
        $pdo->rollback();
        print "Error!: " . $e->getMessage() . "</br>";
        // Print error info and terminate execution
        var_dump($stmt->errorInfo());
        exit;
    }
}



/* Event FUNCTIONS 


This is my MySQL table for the Events database:
CREATE TABLE Events (
    EventID INT AUTO_INCREMENT,
    CustomerID INT,
    EventDate DATE,
    EventType VARCHAR(255),
    IsBooked BOOLEAN,
    PRIMARY KEY (EventID),
    FOREIGN KEY (CustomerID) REFERENCES Customers(CustomerID)
);

 $EventData = array(
    "CustomerID" => "1",
    "EventDate" => "2020-12-31",
    "EventType" => "Wedding",
    "IsBooked" => false
); 

*/


function fetchAllEvents(PDO $pdo) {
    // Prepare a SQL query to join the four tables and select the relevant columns
/*     $sql = "
	    SELECT 
	        Customers.FirstName, 
	        Customers.LastName, 
	        Customers.Email, 
	        Events.EventDate, 
	        Events.EventType, 
	        Events.IsBooked, 
	        Quotes.IntroductionText, 
	        Quotes.DepositPaid, 
	        Quotes.DepositDueDate, 
	        Quotes.FinalPaymentDueDate, 
	        Quotes.Notes 
	    FROM 
		    Customers 
	    INNER JOIN Events ON 
			Customers.CustomerID = Events.CustomerID 
	    INNER JOIN Quotes ON
    		Events.EventID = Quotes.EventID 
	    INNER JOIN QuoteItems ON 
		    Quotes.QuoteID = QuoteItems.QuoteID 
	    ORDER BY Events.EventDate"; */
		
		// Just one event
/*         $sql = "
		SELECT 
		    Customers.FirstName, 
			Customers.LastName, 
			Customers.Email, 
			Events.EventDate, 
			Events.EventType, 
			Events.IsBooked, 
			Quotes.IntroductionText, 
			Quotes.DepositPaid, 
			Quotes.DepositDueDate, 
			Quotes.FinalPaymentDueDate, 
			Quotes.Notes 
		FROM 
			Customers 
		INNER JOIN Events ON 
			Customers.CustomerID = Events.CustomerID 
		INNER JOIN Quotes ON 
			Events.EventID = Quotes.EventID 
		LEFT JOIN (
			SELECT 
			QuoteID, 
			SUM(Cost) AS TotalCost 
		FROM 
			QuoteItems 
		GROUP BY 
			QuoteID) AS QuoteItems ON 
			Quotes.QuoteID = QuoteItems.QuoteID 
		ORDER BY 
			Events.EventDate"; */
			
			$sql = "SELECT Customers.CustomerID, Events.EventID, Customers.FirstName, Customers.LastName, Customers.Email, Events.EventDate, Events.EventType, Events.IsBooked, Quotes.IntroductionText, Quotes.DepositPaid, Quotes.DepositDueDate, Quotes.FinalPaymentDueDate, Quotes.Notes FROM Customers INNER JOIN Events ON Customers.CustomerID = Events.CustomerID INNER JOIN Quotes ON Events.EventID = Quotes.EventID LEFT JOIN (SELECT QuoteID, SUM(Cost) AS TotalCost FROM QuoteItems GROUP BY QuoteID) AS QuoteItems ON Quotes.QuoteID = QuoteItems.QuoteID ORDER BY Customers.LastName, Events.EventDate";

			
    // Use a try-catch block to handle any errors
    try {
        // Start a transaction
        $pdo->beginTransaction();

        // Execute the query and fetch the results as an associative array
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        // Check for any errors
        if ($stmt->errorInfo()[0] != "00000") {
            var_dump($stmt->errorInfo());
            $pdo->rollback();
            return null;
        }

        // Commit the transaction
        $pdo->commit();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the results
        return $results;

    } catch(PDOException $e) {
        // Rollback the transaction and print the error message
        $pdo->rollback();
        print "Error!: " . $e->getMessage() . "</br>";
        return null;
    }
}

 

function addNewEvent(PDO $pdo, $customerID, $EventDate, $EventType, $isBooked) {
    $sql = 'INSERT INTO Events (CustomerID, EventDate, EventType, IsBooked) VALUES (?, ?, ?, ?)';
    
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$customerID, $EventDate, $EventType, $isBooked]);

        if ($stmt->errorInfo()[0] != "00000") {
            var_dump($stmt->errorInfo());
            $pdo->rollback();
            return null;
        }
        
        $pdo->commit();
        return $pdo->lastInsertId();
        
    } catch(PDOException $e) {
        $pdo->rollback();
        print "Error!: " . $e->getMessage() . "</br>";
        return null;
    }
}

function fetchEventsForCustomer(PDO $pdo, $customerID) {
    $sql = 'SELECT * FROM Events WHERE CustomerID = ?';

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$customerID]);

        if ($stmt->errorInfo()[0] != "00000") {
            var_dump($stmt->errorInfo());
            $pdo->rollback();
            return null;
        }
        
        $pdo->commit();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        
    } catch(PDOException $e) {
        $pdo->rollback();
        print "Error!: " . $e->getMessage() . "</br>";
        return null;
    }
}

function fetchEvent(PDO $pdo, $EventID) {
    try {
        // Prepare SQL statement to fetch Event details and associated customer details
        $sql = "
            SELECT 
                e.EventID, 
                e.CustomerID, 
                e.EventDate, 
                e.EventType, 
                e.IsBooked,
                c.FirstName,
                c.LastName,
                c.PhoneNumber,
                c.Email,
                c.DeliveryAddress
            FROM 
                Events e
            JOIN 
                Customers c ON e.CustomerID = c.CustomerID
            WHERE 
                e.EventID = :EventID
        ";
        
        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);
        
        // Bind the EventID parameter
        $stmt->bindParam(':EventID', $EventID, PDO::PARAM_INT);
        
        // Execute the SQL statement
        $stmt->execute();
        
        // Fetch the result as an associative array
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result;
    } catch (PDOException $e) {
        // Handle potential database errors here
        error_log("Database error: " . $e->getMessage());
        return false;
    }
}

function updateEvent(PDO $pdo, $EventID, $EventData): bool {
    try {
        $pdo->beginTransaction();

        $query = "UPDATE Events SET ";
        $params = array();
        foreach ($EventData as $field => $value) {
            $query .= "`".str_replace("`", "``", $field)."` = :".$field.", ";
            $params[$field] = $value;
        }
        $query = substr($query, 0, -2); // remove last comma
        $query .= " WHERE EventID = :EventID";
        $params['EventID'] = $EventID;

        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        if ($stmt->errorCode() != '00000') {
            var_dump($stmt->errorInfo());
            $pdo->rollback();
            return false;
        }
        
        $pdo->commit();
        return true;
        
    } catch(PDOException $e) {
        $pdo->rollback();
        print "Error!: " . $e->getMessage() . "</br>";
        return false;
    }
}

function deleteEventWithQuotes(PDO $pdo, $EventID): bool {
    try {
        // Begin the transaction
        $pdo->beginTransaction();

        // Prepare and execute the statement to delete quotes associated with the Event
        $stmt = $pdo->prepare('DELETE FROM QuoteItems WHERE QuoteID IN (SELECT QuoteID FROM Quotes WHERE EventID = ?)');
        $stmt->execute([$EventID]);

        if ($stmt->errorInfo()[0] != "00000") {
            var_dump($stmt->errorInfo());
            $pdo->rollback();
            exit();
        }

        // Prepare and execute the statement to delete quotes for the Event
        $stmt = $pdo->prepare('DELETE FROM Quotes WHERE EventID = ?');
        $stmt->execute([$EventID]);

        if ($stmt->errorInfo()[0] != "00000") {
            var_dump($stmt->errorInfo());
            $pdo->rollback();
            exit();
        }

        // Prepare and execute the statement to delete the Event
        $stmt = $pdo->prepare('DELETE FROM Events WHERE EventID = ?');
        $stmt->execute([$EventID]);

        if ($stmt->errorInfo()[0] != "00000") {
            var_dump($stmt->errorInfo());
            $pdo->rollback();
            exit();
        }

        // Commit the transaction
        $pdo->commit();

        return true;
    } catch(PDOException $e) {
        // Roll back the transaction if something failed
        $pdo->rollback();
        print "Error!: " . $e->getMessage() . "</br>";
        return false;
    }
}


function getNextEvent(PDO $pdo) {
    try {
        // Begin the transaction
        $pdo->beginTransaction();

        // Prepare the SQL statement
        $stmt = $pdo->prepare("
            SELECT 
                E.EventID,
                C.CustomerID,
                C.FirstName,
                C.LastName,
                E.EventDate,
                E.EventType
            FROM 
                Events AS E
            JOIN 
                Customers AS C ON E.CustomerID = C.CustomerID
            WHERE
                E.EventDate >= CURDATE()
            ORDER BY 
                E.EventDate ASC
            LIMIT 
                1
        ");

        // Execute the prepared statement
        $stmt->execute();

        // Check for any errors
        if ($stmt->errorInfo()[0] != "00000") {
            var_dump($stmt->errorInfo());
            $pdo->rollback();
            exit();
        }

        // Fetch the result
        $nextEvent = $stmt->fetch(PDO::FETCH_ASSOC);

        // Commit the transaction
        $pdo->commit();

        if ($nextEvent) {
            return [
                "CustomerID" => $nextEvent['CustomerID'],
                "EventID" => $nextEvent['EventID'],
                "FirstName" => $nextEvent['FirstName'],
                "LastName" => $nextEvent['LastName'],
                "EventDate" => $nextEvent['EventDate'],
                "EventType" => $nextEvent['EventType']
            ];
        } else {
            return null;
        }
    } catch (\PDOException $e) {
        // Rollback the transaction in case of any errors
        $pdo->rollback();
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
}





/* QUOTE FUNCTIONS 
This is the MySQL table for the quote:
CREATE TABLE Quotes (
    QuoteID INT AUTO_INCREMENT PRIMARY KEY,
    EventID INT,
    IntroductionText TEXT,
    DepositPaid DECIMAL(10,2),
    DepositDueDate DATE,
    FinalPaymentDueDate DATE,
    Notes TEXT,
    FOREIGN KEY (EventID) REFERENCES Events(EventID)
);

 $quoteData = array(
    "EventID" => "1",
    "IntroductionText" => "Introduction",
    "DepositPaid" => 0.00,
    "DepositDueDate" => "2020-12-31",
	"FinalPaymentDueDate" => "2020-12-31",
	"Notes" => "Notes"
); 

*/

function fetchQuoteIDs(PDO $pdo, $EventID) {
    try {
        // Prepare and execute the statement to fetch the QuoteIDs associated with the EventID
        $stmt = $pdo->prepare('SELECT QuoteID FROM Quotes WHERE EventID = ?');
        $stmt->execute([$EventID]);
        
        if ($stmt->errorInfo()[0] != "00000") {
            var_dump($stmt->errorInfo());
            exit();
        }

        // Fetch all QuoteIDs as an array
        $quoteIDs = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

        return $quoteIDs;
    } catch(PDOException $e) {
        print "Error!: " . $e->getMessage() . "</br>";
        return false;
    }
}

function fetchQuote(PDO $pdo, $quoteID) {
    try {
        // Prepare and execute the statement to fetch the quote with the given quoteID
        $stmt = $pdo->prepare('SELECT * FROM Quotes WHERE QuoteID = ?');
        $stmt->execute([$quoteID]);
        
        if ($stmt->errorInfo()[0] != "00000") {
            var_dump($stmt->errorInfo());
            exit();
        }

        // Fetch the quote as an associative array
        $quote = $stmt->fetch(PDO::FETCH_ASSOC);

        return $quote;
    } catch(PDOException $e) {
        print "Error!: " . $e->getMessage() . "</br>";
        return false;
    }
}

function fetchQuoteLines(PDO $pdo, $quoteID) {
    try {
        // Prepare and execute the statement to fetch all line items associated with the quoteID
        $stmt = $pdo->prepare('SELECT * FROM QuoteItems WHERE QuoteID = ? ORDER BY OrderIndex ASC');
        $stmt->execute([$quoteID]);
        
        if ($stmt->errorInfo()[0] != "00000") {
            var_dump($stmt->errorInfo());
            exit();
        }

        // Fetch all line items as an associative array
        $quoteLines = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $quoteLines;
    } catch(PDOException $e) {
        print "Error!: " . $e->getMessage() . "</br>";
        return false;
    }
}

function addQuote(PDO $pdo, $EventID, $quoteData): int {
    try {
		
        // Start a transaction
        $pdo->beginTransaction();
        // Prepare the SQL statement
        $sql = 'INSERT INTO Quotes (EventID, IntroductionText, DepositPaid, DepositDueDate, FinalPaymentDueDate, Notes) VALUES (?, ?, ?, ?, ?, ?)';
        $stmt = $pdo->prepare($sql);
        
        // Execute the statement with the quote data
        $stmt->execute([$EventID, $quoteData['IntroductionText'], $quoteData['DepositPaid'], $quoteData['DepositDueDate'], $quoteData['FinalPaymentDueDate'], $quoteData['Notes']]);
        
        if ($stmt->errorInfo()[0] != "00000") {
            var_dump($stmt->errorInfo());
            exit();
        }

        // Print the last inserted id
        //print $pdo->lastInsertId();
        $pdo->commit();
        return $pdo->lastInsertId();
    } catch(PDOException $e) {
        $pdo->rollback();
        print "Error!: " . $e->getMessage() . "</br>";
        return false;
    }
}

function updateQuote(PDO $pdo, $quoteID, $quoteData): bool {
    // quoteData is an associative array with keys matching the column names in the Quotes table
    // e.g. $quoteData = ['IntroductionText' => 'New text', 'DepositPaid' => 100.00];

    try {
        // Start the transaction
        $pdo->beginTransaction();

        // Construct the SQL query
        $query = "UPDATE Quotes SET ";
        $params = array();
        foreach ($quoteData as $field => $value) {
            $query .= "`".str_replace("`", "``", $field)."` = :".$field.", ";
            $params[$field] = $value;
        }
        $query = substr($query, 0, -2); // remove last comma
        $query .= " WHERE QuoteID = :quoteID";
        $params['quoteID'] = $quoteID;

        // Prepare and execute the SQL query
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        // Check for faults
        if ($stmt->errorCode() != '00000') {
            var_dump($stmt->errorInfo());
            exit();
        }

        // Commit the transaction
        $pdo->commit();

        return true;
    } catch(PDOException $e) {
        // Rollback the transaction on error
        $pdo->rollback();
        print "Error!: " . $e->getMessage() . "<br/>";
        return false;
    }
}

function deleteQuote(PDO $pdo, $quoteID) {
    try {
        // Begin transaction
        $pdo->beginTransaction();

        // Delete quote items
        $sql = "DELETE FROM QuoteItems WHERE QuoteID = :quoteID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':quoteID', $quoteID, PDO::PARAM_INT);
        $stmt->execute();

        // Delete the quote
        $sql = "DELETE FROM Quotes WHERE QuoteID = :quoteID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':quoteID', $quoteID, PDO::PARAM_INT);
        $stmt->execute();
        
        // If any error occurs, PDOException will be thrown and the code will jump to the catch block
        $pdo->commit();
        return true;
    } catch(PDOException $e) {
        // If any error occurred, rollback the transaction
        $pdo->rollback();
        print "Error!: " . $e->getMessage() . "<br/>";
        var_dump($stmt->errorInfo());
        exit();
    }
    return false;
}



/* QUOTE LINE FUNCTIONS 
This is the MySQL table for the quote line items:
CREATE TABLE QuoteItems (
    ItemID INT AUTO_INCREMENT PRIMARY KEY,
    QuoteID INT,
    Details TEXT,
    DeliverTo TEXT,
    CostPerItem DECIMAL(10,2),
    NumberOfItems INT,
    Cost DECIMAL(10,2),
    OrderIndex INT,
    FOREIGN KEY (QuoteID) REFERENCES Quotes(QuoteID)
);


 $quoteLineData = array(
    "QuoteID" => "1",
    "Details" => "Item Detail",
    "DeliverTo" => "Venue",
    "CostPerItem" => 5.50,
    "NumberOfItems" => 1,
    "Cost" => 5.50
); 

*/

function addQuoteLine(PDO $pdo, $quoteID, $quoteLineData) {
    try {
        $pdo->beginTransaction();
        
        $sql = 'INSERT INTO QuoteItems (QuoteID, Details, DeliverTo, CostPerItem, NumberOfItems, Cost, OrderIndex) 
                VALUES (:quoteID, :details, :deliverTo, :costPerItem, :numberOfItems, :cost, :orderIndex)';

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':quoteID', $quoteID, PDO::PARAM_INT);
        $stmt->bindParam(':details', $quoteLineData['Details'], PDO::PARAM_STR);
        $stmt->bindParam(':deliverTo', $quoteLineData['DeliverTo'], PDO::PARAM_STR);
        $stmt->bindParam(':costPerItem', $quoteLineData['CostPerItem'], PDO::PARAM_STR);
        $stmt->bindParam(':numberOfItems', $quoteLineData['NumberOfItems'], PDO::PARAM_INT);
        $stmt->bindParam(':cost', $quoteLineData['Cost'], PDO::PARAM_STR);
        $stmt->bindParam(':orderIndex', $quoteLineData['OrderIndex'], PDO::PARAM_INT);

        $stmt->execute();

        $insertedId = $pdo->lastInsertId();

        $pdo->commit();
        
        return $insertedId;

    } catch(PDOException $e) {
        $pdo->rollback();
        print "Error!: " . $e->getMessage() . "</br>";
        var_dump($stmt->errorInfo());
        exit();
    }

    return false;
}

function deleteQuoteLine(PDO $pdo, $quoteLineID): bool {
    try {
        $pdo->beginTransaction();

        // Prepared statement to delete a quote line with the given quoteLineID
        $stmt = $pdo->prepare('DELETE FROM QuoteItems WHERE ItemID = :quoteLineID');

        // Bind parameters
        $stmt->bindParam(':quoteLineID', $quoteLineID, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Check for faults
        if ($stmt->errorCode() != '00000') {
            var_dump($stmt->errorInfo());
            exit();
        }

        $pdo->commit();

        return true;
    } catch(PDOException $e) {
        $pdo->rollback();
        print "Error!: " . $e->getMessage() . "</br>";
        exit();
    }

    return false;
}

function fetchQuoteLine(PDO $pdo, $LineItemID) {
    try {
		
        $stmt = $pdo->prepare('SELECT * FROM QuoteItems WHERE ItemID  = ?');
        $stmt->execute([$LineItemID]);
        
        if ($stmt->errorInfo()[0] != "00000") {
            var_dump($stmt->errorInfo());
            exit();
        }

        // Fetch all line items as an associative array
        //$quoteLines = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$quoteLine = $stmt->fetch(PDO::FETCH_ASSOC);

        return $quoteLine;
    } catch(PDOException $e) {
        print "Error!: " . $e->getMessage() . "</br>";
        return false;
    }
}

function updateQuoteLine(PDO $pdo, $quoteLineID, $quoteLineData): bool {
    /*
	
    $quoteLineData = [
        'QuoteID' => 1,
        'Details' => 'This is a test',
        'DeliverTo' => '123 Test St',
        'CostPerItem' => 10.00,
        'NumberOfItems' => 2,
        'Cost' => 20.00,
        'OrderIndex' => 1
    ];
	
    In this example, the QuoteID will be updated to '1', Details to 
    'This is a test', DeliverTo to '123 Test St', CostPerItem to '10.00', 
    NumberOfItems to '2', Cost to '20.00', and OrderIndex to '1'.

    Remember that you should only include the fields that you want to 
    update in the $quoteLineData array. If a field is not included in 
    the array, its value should remain unchanged in the database.
	
    */

    // Start the transaction
    try {
        $pdo->beginTransaction();

        // Construct the SQL query
        $query = "UPDATE QuoteItems SET ";
        $params = array();
        foreach ($quoteLineData as $field => $value) {
            $query .= "`".str_replace("`", "``", $field)."` = :".$field.", ";
            $params[$field] = $value;
        }
        $query = substr($query, 0, -2); // remove last comma
        $query .= " WHERE ItemID = :quoteLineID";
        $params['quoteLineID'] = $quoteLineID;

        // Prepare and execute the SQL query
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        // Check for faults
        if ($stmt->errorCode() != '00000') {
            var_dump($stmt->errorInfo());
        }

        // Commit the transaction
        $pdo->commit();

        return true;
    } catch(PDOException $e) {
        // Rollback the transaction on error
        $pdo->rollback();
        print "Error!: " . $e->getMessage() . "<br/>";
        return false;
    }
}

function swapOrderIndex(PDO $pdo, $item1_id, $item2_id) {
    // Fetch the two items from the database
	
	//echo '<pre> PROCESSING: var_dump($item1_id); <br>';
	//var_dump($item1_id);
	//echo '</pre>';
	//
	//echo '<pre> PROCESSING: var_dump($item2_id); <br>';
	//var_dump($item2_id);
	//echo '</pre>';
	//
        $item1 = fetchQuoteLine($pdo, $item1_id);
        $item2 = fetchQuoteLine($pdo, $item2_id);
	//
	//echo '<pre> PROCESSED: var_dump($item1); <br>';
	//var_dump($item1);
	//echo '</pre>';
	//echo '<pre> PROCESSED: var_dump($item2); <br>';
	//var_dump($item2);
	//echo '</pre>';
	//
	//
	//echo '<pre> PROCESSED: var_dump($item1["OrderIndex"]); <br>';
	//var_dump($item1['OrderIndex']);
	//echo '</pre>';
	//echo '<pre> PROCESSED: var_dump($item2["OrderIndex"]); <br>';
	//var_dump($item2['OrderIndex']);
	//echo '</pre>';
	
    // Swap the OrderIndex values
    $tempIndex = $item1['OrderIndex'];
    $item1['OrderIndex'] = $item2['OrderIndex'];
    $item2['OrderIndex'] = $tempIndex;

    // Update the items in the database
    updateQuoteLine($pdo, $item1_id, $item1);
    updateQuoteLine($pdo, $item2_id, $item2);
}

?>
