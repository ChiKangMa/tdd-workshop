<?php

class FakeBudgetRepo implements IBudgetRepo
{
    public function getAll()
    {
        $arr = ['201803' => 93, '201804' => 30, '201805' => 310, '201912' => 3100, '202012' => 620];
        $result = [];
        foreach ($arr as $yearMonth => $amount) {
            $result[] = new \App\Budget($yearMonth, $amount);
        }
    }
}
