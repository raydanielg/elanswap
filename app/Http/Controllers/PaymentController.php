<?php
// one-file handler for both webhook and form submission

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

define("APP_ID", "104"); // 🔹 set your real app ID here
function apiUrl($method){
    return "https://elan.co.tz/api/payments/selcom/" . ltrim($method, "/");
}

/**
 * Handle Webhook
 */
function updateStatus() {

        $result = json_decode(file_get_contents('php://input'));
		$order_id = $result->order_id;
        $status = $result->status;

        if ($status == 'paid') {
            $data['status'] = 'paid';
            $data['transid'] = $result->transid;
            $this->db->where('order_id', $order_id);
            $this->db->update('payments', $data);
            //Send sms to payer here 
            
        }


        echo json_encode([
            "status"     => "success",
            "message"    => "Webhook processed",
            "order_id"   => $order_id,
            "new_status" => $status
        ]);
}

/**
 * Handle Pay Order (form submission)
 */
function payOrder() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(["status" => "error", "message" => "Invalid request"]);
        exit;
    }

    $data = [
        'username' => $_POST['username'] ?? '',
        'phone'    => $_POST['phone']    ?? '',
        'amount'   => $_POST['amount']   ?? 0,
        'order_id' => $_POST['order_id'] ?? uniqid("ORD_"),
    ];

    $response = create_mno_order($data);
    echo json_encode($response);
}

/**
 * Create MNO Order via Selcom API
 */
function create_mno_order($data){
    $url = apiUrl("api/v1/create_mno_order");

    $postfields = http_build_query([
        "app_id"            => APP_ID,
        "order_firstname"   => $data['username'],
        "order_lastname"    => "Customer",
        "order_email"       => "info@elanbrands.net",
        "order_phone"       => $data['phone'],
        "amount"            => $data['amount'],
        "order_id"          => $data['order_id'],
        "currency"          => "TZS",
        "order_item_cont"   => 1,
        "service_name"      => "subscription",
        "is_reference_payment" => 1
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    if ($response === false) {
        return ["status" => false, "message" => "cURL error: " . curl_error($ch)];
    }
    curl_close($ch);

    $selcomResponse = json_decode($response);

    if(!empty($selcomResponse->reference) && is_numeric($selcomResponse->reference)){
        $data['reference']   = $selcomResponse->reference; 
        $data['payment_url'] = $selcomResponse->payment_url;

        // $CI->db->insert('payments', $data);

        return push($data);
    }

    return [
        "status"     => false,
        "payment_id" => "",
        "message"    => "Tafadhali Jaribu Tena",
        "url"        => "",
        "mode"       => "mno",
    ];
}

/**
 * Push request (USSD)
 */
function push($data){
    $url = apiUrl("initiatePushUSSD");

    $postfields = http_build_query([
        'project_id'         => APP_ID,
        'phone'              => $data['phone'],
        'order_id'           => $data['order_id'],
        'is_reference_payment' => 0
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    if ($result === false) {
        return ["status" => false, "message" => "cURL error: " . curl_error($ch)];
    }
    curl_close($ch);

    $res = json_decode($result);

    if(!empty($res->resultcode) && $res->resultcode == "000"){
        return [
            "status"     => true,
            "message"   => "Please check your phone to input PIN",
            "url"           => $data['payment_url'],
            "order_id"     => $data['order_id'],
            "reference"     => $data['reference'],
            "mode"       => "mno",
        ];
    }

    return [
        "status"  => false,
        "message" => $res->message ?? "Unknown error",
        "url"     => "",
        "mode"    => "mno",
    ];
}
 