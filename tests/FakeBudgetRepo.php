<?php

class FakeBudgetRepo implements IBudgetRepo
{
    private $budgetData = [
        '201912',
    ];

    public function getAll()
    {
        return $this->budgetData;
    }
}
