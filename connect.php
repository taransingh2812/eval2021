
<?php
try {
    $dbh = new PDO(
        "mysql:host=localhost;dbname=customerInfo",
        "root",
        ''
    );
} catch (Exception $e) {
    die("ERROR: Couldn't connect. {$e->getMessage()}");
}
