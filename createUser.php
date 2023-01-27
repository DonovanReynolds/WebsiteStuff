<?php


$inData = getRequestInfo();

$userID = $inData["ID"];
$userFirstName = $inData["FirstName"];
$userLastName = $inData["LastName"];
$userEmail = $inData["Email"];
$userPhone = $inData["Phone"];
$userPassword = $inData["Password"];



$conn = new mysqli("localhost", "Tester", "Group12Rocks", "COP4331");

if ($conn->connect_error) 
{
   returnWithError( $conn->connect_error );
} 

else
{
   $stmt1 = $conn->prepare("SELECT * FROM Users WHERE Email = ?");
   $stmt1->bind_param("s", $userEmail);
   $stmt1->execute();
   $result = $stmt1->get_result();

   $stmt1->close();

   if($row = $result->fetch_assoc())
   {
      returnWithError("This Email is already in use!");
   }
   else
   {
      $stmt2 = $conn->prepare("INSERT INTO Users (ID, FirstName,LastName,Email,Phone,Password) VALUES (?,?,?,?,?,?)");
      $stmt2->bind_param("issss", $userID, $userFirstName,$userLastName,$userEmail,$userPhone,$userPassword);
      $stmt2->execute();
      $addContact = $stmt2->get_result();
      $stm2->close();
      returnWithSuccess();

   }

}

$conn->close();

function getRequestInfo()
{
   return json_decode(file_get_contents('php://input'), true);
}

function sendResultInfoAsJson( $obj )
{
   header('Content-type: application/json');
   echo $obj;
}

function returnWithError( $err )
{
   $retValue = '{"UserID":"0","FirstName":"","LastName":"","Email":"","Phone":"","error":"' . $err . '"}';
   sendResultInfoAsJson( $retValue );
}

function returnWithSuccess ( )
{
   global $contactUserID, $contactEmail, $contactFirstName, $contactLastName, $contactPhoneNumber;
   $retValue ='{"UserID":"'. $contactUserID .'","FirstName":"' . $contactFirstName . '","LastName":"' . $contactLastName . '","Email":"' . $contactEmail . '","Phone":"' . $contactPhoneNumber . '","error":""}';
   sendResultInfoAsJson( $retValue );
}
?>