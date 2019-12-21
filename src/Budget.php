<?php

namespace App;

class Budget
{
    public $yearMonth;
    public $amount;

    public function __construct($yearMonth, $amount)
    {
        $this->amount = $amount;
        $this->yearMonth = $yearMonth;
    }
}
