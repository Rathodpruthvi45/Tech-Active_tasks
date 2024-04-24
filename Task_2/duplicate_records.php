<?php


function calculateDuplicatePercentage($data)
{
    $totalRecords = count($data);
    $uniqueRecords = array_unique($data);
    $uniqueCount = count($uniqueRecords);
    $duplicateCount = $totalRecords - $uniqueCount;
    $duplicatePercentage = ($duplicateCount / $totalRecords) * 100;
    return $duplicatePercentage;
}


$sampleData = [
    "John",
    "Jane",
    "John",
    "Doe",
    "Jane",
    "Smith",
    "Doe"
];

// Calculate the percentage of duplicate records
$duplicatePercentage = calculateDuplicatePercentage($sampleData);

// Output the result
echo "Percentage of duplicate records: " . $duplicatePercentage . "%";

?>