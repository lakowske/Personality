<?php
require_once('Database.php');
require_once('Supplier.php');

$database = new Database();
$databaseSupplier = new Supplier($database);

?>