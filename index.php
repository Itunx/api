<?php

header("Access-Control-Allow-Origin:* ");
header("Access-Control-Allow-Headers:* ");
header("Access-Control-Allow-Methods: GET, POST,  DELETE, OPTIONS");


include 'Dbconnection.php';

$database = new Database();
$conn = $database->getConnection();


$user = file_get_contents('php://input');
print_r($user);
$method = $_SERVER['REQUEST_METHOD'];

// //convert array of product specific furniture to string






// function convert_array(array $product_specs){
//     if(!is_array($product_specs)){
//         return $product_specs;
//     }
//     else{
//         return $product_specs[0].'*'.$product_specs[1].'*'.$product_specs[2];

//     }


// }


// function convert_array($product_specs){
//     if(!is_array($product_specs)){
//         // If input is a string, convert it to an array
//         $product_specs = explode(',', $product_specs);
//     }
    
//     if(!is_array($product_specs) || count($product_specs) < 3){
//         return ['Enter valid product specifications'];
//     }

//     return $product_specs[0].'*'.$product_specs[1].'*'.$product_specs[2];
// }



switch ($method) {
    
    case "POST":

        $user = json_decode(file_get_contents('php://input'));
        // print_r($user->product_specific);
        
        // print_r(json_encode(convert_array($user->product_specific)));
        // return
        json_encode($user->product_specific);
        

        $sql = "INSERT INTO products (id, sku, name, price, product_specific, created_at) VALUES (Null, :sku, :name, :price, :product_specific, :created_at)";
        $stmt = $conn->prepare($sql);

        $created_at = date('Y-m-d');

        $stmt->bindParam(':sku', $user->sku);
        $stmt->bindParam(':name', $user->name);
        $stmt->bindParam(':price', $user->price);
        $product_specific_string = ( $user->product_specific);
        // print_r($product_specific_string);
     
        // $product_specific_string = ( $user->product_specific);
$stmt->bindParam(':product_specific', $product_specific_string);

        // $stmt->bindParam(':product_specific', $user->product_specific);
        $stmt->bindParam(':created_at', $created_at);

        if ($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Record successfully created.'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to create record.'];
        }

        echo json_encode($response);
        break;


    case "GET":
        $sql = "SELECT * FROM products";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
        break;

   
    case "DELETE":
        $idsString = $_GET['ids'];
        if (!$idsString) {
            // If the 'ids' parameter is not present or is not an array, return an error response.
            http_response_code(400); // Bad Request
            echo json_encode(array('error' => 'Invalid or missing ids parameter'));
            exit;
        }
        $sql = "DELETE FROM products WHERE id IN ($idsString)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            // If there was an error preparing the query, return an error response.
            http_response_code(500); // Internal Server Error
            echo json_encode(array('error' => 'Could not prepare SQL query'));
            exit;
        }

        if ($stmt->execute()) {
            $response = ['status' => 1, 'message' => 'Product delete successfully .'];
        } else {
            $response = ['status' => 0, 'message' => 'Failed to delete products.'];
        }

        echo json_encode($response);
}
