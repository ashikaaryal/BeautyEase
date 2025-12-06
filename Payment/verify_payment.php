<?php
include('../includes/connect.php');

header('Content-Type: application/json');

class KhaltiPayment {
    private $secret_key;
    private $verification_url;
    
    public function __construct() {
        // Replace with your actual Khalti secret key
              $this->secret_key = "5f3811ecdf774c769b1b2e693294a982"; // Your secret key here
        $this->verification_url = "https://khalti.com/api/v2/payment/verify/";
    }
    
    public function verifyPayment($token, $amount) {
        $payload = [
            "token" => $token,
            "amount" => $amount
        ];
        
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->verification_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                "Authorization: Key " . $this->secret_key,
                "Content-Type: application/json",
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        
        curl_close($curl);
        
        if ($err) {
            return [
                'success' => false,
                'message' => 'cURL Error: ' . $err
            ];
        }
        
        $response_data = json_decode($response, true);
        
        if ($http_code === 200) {
            return [
                'success' => true,
                'data' => $response_data,
                'message' => 'Payment verified successfully'
            ];
        } else {
            return [
                'success' => false,
                'message' => $response_data['detail'] ?? 'Payment verification failed',
                'error_code' => $http_code
            ];
        }
    }
}

// Get the raw POST data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data || !isset($data['token']) || !isset($data['amount'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid data received'
    ]);
    exit;
}

$token = $data['token'];
$amount = $data['amount'];
$booking_id = $data['booking_id'];

// Initialize Khalti payment class
$khalti = new KhaltiPayment();

// Verify the payment
$result = $khalti->verifyPayment($token, $amount);

if ($result['success']) {
    // Update the booking status in the database to 'paid'
    $update_query = mysqli_query($conn, "UPDATE bookings SET payment_status = 'paid' WHERE id = '$booking_id'");
    
    if ($update_query) {
        $result['message'] = 'Payment verified and booking confirmed successfully';
    } else {
        $result['message'] = 'Payment verified but failed to update booking status: ' . mysqli_error($conn);
    }
}

echo json_encode($result);
?>