<?php
 require __DIR__ . "/db.php";

 class Transaction_history {
    public $sender_id;
    public $beneficary_id;
    public $transaction_history;

    public function fetch_transaction_history() {
        global $conn;
        $sql = "SELECT * FROM transactions WHERE sender_id = ? OR beneficiary_id = ? 
        ORDER BY STR_TO_DATE(transaction_date, '%b %D, %Y %H:%i:%s') DESC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $this->sender_id, $this->beneficary_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $this->transaction_history = array();
        
        while($row = $result->fetch_assoc()) {

            if ($row['transaction_type'] == 'Transfer' && $row['sender_id'] == $this->sender_id){
                echo "Transfer <span style='background-color: rgb(248, 74, 74); color:black; padding: 0 2px 0 2px;'>{$row['transaction_amount']}</span> to {$row['beneficiary_name']}  {$row['transaction_date']} <br>";
            }
            if ($row['transaction_type'] == 'Transfer' && $row['beneficiary_id'] == $this->sender_id){
                echo  "Transfer <span style='background-color: rgb(164, 240, 149); color:black; padding: 0 2px 0 2px;'>{$row['transaction_amount']}</span>  from {$row['sender_name']}  {$row['transaction_date']} <br>";
            }
            if ($row['transaction_type'] == 'Airtime'){
                echo "Airtime " . '₦' . $row['transaction_amount'] . ' ' . $row['network_provider'] . ' ' . $row['transaction_date'] . "<br>";
            }
               if ($row['transaction_type'] == 'Data'){
                echo "Data " . $row['network_provider'] . ' ' . $row['data_bundle'] . ' ' . $row['transaction_date'] . "<br>";
            }
        }
    }
 }

?>