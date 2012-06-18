<?php
require_once('Database.php');
require_once('Supplier.php');

$database = Database::fromGlobals();
$databaseSupplier = new Supplier($database);

?>