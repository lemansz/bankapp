
<?php

require __DIR__ . "/db.php";

$day = date('M dS, Y H:i:s');

class Transactions 
{
   public $sender_id;
   public $beneficiary_id;
   public $transaction_amount;
   public $beneficiary_name;
   public $sender_name;
   public $sender_account_no;
   public $beneficiary_account_no;
   public $transaction_type;
   public $network_provider;
   public $transaction_remark;
   public $data_bundle;
   public $beneficiary_phone_no;
   public $transaction_date;

   public function record_transfer()
   {
       global $conn;

       $sql = "INSERT INTO transactions (sender_id, 
       beneficiary_id, 
       transaction_amount, 
       beneficiary_name, 
       sender_name, 
       sender_account_no, 
       beneficiary_account_no, 
       transaction_type, 
       transaction_remark, 
       transaction_date)
       VALUES (?,?,?,?,?,?,?,?,?,?)
       ";
       $stmt = $conn->prepare($sql);
       $stmt->bind_param("ssssssssss", $this->sender_id, $this->beneficiary_id, $this->transaction_amount, $this->beneficiary_name,$this->sender_name,$this->sender_account_no,$this->beneficiary_account_no,$this->transaction_type,$this->transaction_remark,$this->transaction_date);
       $stmt->execute();
       $stmt->close();
    }


   public function record_airtime_transaction()
   {
    global $conn;
    
    $sql = "INSERT INTO transactions (sender_id,
    transaction_amount,
    transaction_type, 
    network_provider,
    beneficiary_phone_no,
    transaction_date)
    VALUES (?,?,?,?,?,?)
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $this->sender_id,$this->transaction_amount,$this->transaction_type,$this->network_provider, $this->beneficiary_phone_no,$this->transaction_date);
    $stmt->execute();
    $stmt->close();
   } 


   public function record_data_transaction()
   {
    global $conn;
    $sql = "INSERT INTO transactions (sender_id,
    transaction_amount, 
    transaction_type, 
    network_provider,
    data_bundle,
    beneficiary_phone_no,
    transaction_date)
    VALUES (?,?,?,?,?,?,?)
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $this->sender_id,$this->transaction_amount,$this->transaction_type,$this->network_provider,$this->data_bundle,$this->beneficiary_phone_no,$this->transaction_date);
    $stmt->execute();
    $stmt->close();
   }    
}
?>