<?php
 require __DIR__ . "/db.php";


 trait Comma
 {
    public function addCommas($val)
    {
      return number_format($val);
    }
 }

 class Transaction_history 
 {
    use Comma;
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
 
    if ($row['transaction_type'] == 'Deposit' && $row['beneficiary_id'] == $this->sender_id){
        echo "Bank Deposit of <span style='background-color: rgb(164, 240, 149); color:black; padding: 0 2px 0 2px;'>₦{$this->addCommas($row['transaction_amount'])}</span> {$row['transaction_date']} <br>";
    }
    if ($row['transaction_type'] == 'Withdraw' && $row['beneficiary_id'] == $this->sender_id){
        echo "Bank Withdrawal of <span style='background-color: rgb(248, 74, 74); color:black; padding: 0 2px 0 2px;'>₦{$this->addCommas($row['transaction_amount'])}</span> {$row['transaction_date']} <br>";
    }
    if ($row['transaction_type'] == 'Transfer' && $row['sender_id'] == $this->sender_id){
        echo "Transfer <span style='background-color: rgb(248, 74, 74); color:black; padding: 0 2px 0 2px;'>₦{$this->addCommas($row['transaction_amount'])}</span> to {$row['beneficiary_name']} {$row['transaction_date']}<br>";
    }
    if ($row['transaction_type'] == 'Transfer' && $row['beneficiary_id'] == $this->sender_id){
        echo  "Transfer <span style='background-color: rgb(164, 240, 149); color:black; padding: 0 2px 0 2px;'>₦{$this->addCommas($row['transaction_amount'])}</span>  from {$row['sender_name']}  {$row['transaction_date']} <br>";
    }
    if ($row['transaction_type'] == 'Airtime'){
        echo "Airtime <span>₦{$this->addCommas($row['transaction_amount'])} {$row['network_provider']}  {$row['transaction_date']} </span><br>";
    }
        if ($row['transaction_type'] == 'Data'){
        echo "Data " . $row['network_provider'] . ' ' . $row['data_bundle'] . ' ' . $row['transaction_date'] . "<br>";
    }

    }
    }

public function transaction_history_cards()
{
    global $conn;
    $sql = "SELECT * FROM transactions WHERE sender_id = ? OR beneficiary_id = ? 
    ORDER BY STR_TO_DATE(transaction_date, '%b %D, %Y %H:%i:%s') DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $this->sender_id, $this->beneficary_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<div class='transaction-container' style='display: flex; flex-wrap: wrap; justify-content: center; gap: 20px;'>";

    $hasTransaction = false;
    while ($row = $result->fetch_assoc()) {
        $hasTransaction = true;
        $bgColor = $row['transaction_type'] == 'Deposit' || ($row['transaction_type'] == 'Transfer' && $row['beneficiary_id'] == $this->sender_id) ? 'green' : 'rgb(248, 74, 74)';

        echo "<div  class='transaction-card' style='border: 1px solid #ccc; border-radius: 8px; padding: 15px; flex: 1 1 calc(100% - 40px); max-width: calc(100% - 40px); background-color: #f9f9f9; box-sizing: border-box;'>";


        switch ($row['transaction_type']) {
            case 'Deposit':
                echo "<h3 style='margin: 0; color: $bgColor;'>Deposit</h3>";
                echo "<p><strong>Amount:</strong> ₦{$this->addCommas($row['transaction_amount'])}</p>";
                echo "<p><strong>Transaction ID:</strong> {$row['transaction_id']}</p>";
                echo "<p><strong>Date:</strong> {$row['transaction_date']}</p>";
                break;

            case 'Withdraw':
                echo "<h3 style='margin: 0; color: $bgColor;'>Withdrawal</h3>";
                echo "<p><strong>Amount:</strong> ₦{$this->addCommas($row['transaction_amount'])}</p>";
                echo "<p><strong>Date:</strong> {$row['transaction_date']}</p>";
                echo "<p><strong>Transaction ID:</strong> {$row['transaction_id']}</p>";

                break;

            case 'Transfer':
                if ($row['sender_id'] == $this->sender_id) {
                    echo "<h3 style='margin: 0; color: $bgColor;'>Transfer</h3>";
                    echo "<p><strong>Amount:</strong> ₦{$this->addCommas($row['transaction_amount'])}</p>";
                    echo "<p><strong>Beneficiary:</strong> {$row['beneficiary_name']} | {$row['beneficiary_account_no']}</p>";
                    echo "<p><strong>Transaction ID:</strong> {$row['transaction_id']}</p>";
                } else {
                    echo "<h3 style='margin: 0; color: $bgColor;'>Transfer</h3>";
                    echo "<p><strong>Amount:</strong> ₦{$this->addCommas($row['transaction_amount'])}</p>";
                    echo "<p><strong>Sender details:</strong> {$row['sender_name']} | {$row['sender_account_no']}</p>";
                    echo "<p><strong>Transaction ID:</strong> {$row['transaction_id']}</p>";

                }
                echo "<p><strong>Date:</strong> {$row['transaction_date']}</p>";
                break;

            case 'Airtime':
                echo "<h3 style='margin: 0; color: $bgColor;'>Airtime</h3>";
                echo "<p><strong>Amount:</strong> ₦{$this->addCommas($row['transaction_amount'])}</p>";
                echo "<p><strong>Network:</strong> {$row['network_provider']}</p>";
                echo "<p><strong>Beneficiary no:</strong> {$row['beneficiary_phone_no']}</p>";
                echo "<p><strong>Date:</strong> {$row['transaction_date']}</p>";
                echo "<p><strong>Transaction ID:</strong> {$row['transaction_id']}</p>";

                break;

            case 'Data':
                echo "<h3 style='margin: 0; color: $bgColor;'>Data</h3>";
                echo "<p><strong>Amount:</strong> ₦{$this->addCommas($row['transaction_amount'])}</p>";
                echo "<p><strong>Network:</strong> {$row['network_provider']}</p>";
                echo "<p><strong>Bundle:</strong> {$row['data_bundle']}</p>";
                echo "<p><strong>Beneficiary no:</strong> {$row['beneficiary_phone_no']}</p>";
                echo "<p><strong>Date:</strong> {$row['transaction_date']}</p>";
                echo "<p><strong>Transaction ID:</strong> {$row['transaction_id']}</p>";
                break;

            default:
                echo "<h3 style='margin: 0; color: $bgColor;'>Unknown Transaction</h3>";
                echo "<p><strong>Details:</strong> No Tr</p>";
                break;
        }

        echo "</div>";
    }

    if (!$hasTransaction) {
        echo "<div class='transaction-card no-transaction' style='width:100%;'><h3>Not available</h3><p>No transactions found.</p></div>";
    }

    echo "</div>";
}
}
 
?>