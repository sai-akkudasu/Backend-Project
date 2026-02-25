<?php

class BankAccount {
    public $accountHolder;   
    protected $balance;      
    private $pin;            
    public function __construct($holder, $balance, $pin) {
        $this->accountHolder = $holder;
        $this->balance = $balance;
        $this->pin = $pin;
    }
    public function showBalance($enteredPin) {
        if ($enteredPin === $this->pin) {
            return $this->balance;
        } else {
            return "Incorrect PIN!";
        }
    }
}


$account1 = new BankAccount("Sai", 1000, "1234");
echo "Account Holder: " . $account1->accountHolder . "<br>";
echo "Balance: $" . $account1->showBalance("1234") . "<br>";

// Trying to access protected/private directly will cause error
 echo $account1->balance; // Error
 echo $account1->pin;     // Error
?>
