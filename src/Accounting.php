<?php

namespace App;

class Accounting
{
    private $repo;
    private $currentData;

    public function __construct(IBudgetRepo $budgetRepo)
    {
        $this->repo = $budgetRepo;
    }

    public function queryBudget(\DateTime $start, \DateTime $end)
    {
        $this->currentData = $this->repo->getAll();
        $startYear = $start->format('Y');
        $endYear = $end->format('Y');
        $startMonth = $start->format('m');
        $endMonth = $end->format('m');
        $startDay = $start->format('d');
        $endDay = $end->format('d');

        $diffDays = $endDay - $startDay + 1;

        if ($startYear === $endYear && $startMonth === $endMonth) {
            foreach ($this->currentData as $row) {
                if ($row->yearMonth == $start->format('Ym')) {
                    $actualDays = cal_days_in_month(CAL_GREGORIAN, $startMonth, $startYear);

                    if ($diffDays == $actualDays) {
                        return $row->amount;
                    }

                    return $row->amount / $actualDays * $diffDays;
                }
            }
        }

        $total = 0;
        for ($s = $start->getTimestamp();
                $s <= $end->getTimestamp();
                    $s = strtotime(date('Y-m', $s).'-01'.' +1 month')) {
            $ym = date('Ym', $s);
            $year = date('Y', $s);
            $month = date('m', $s);
            $amount = $this->getAmount($ym);

            if (0 == $amount) {
                continue;
            }

            $actualDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            if ($s == $start->getTimestamp()) {
                $calculatedDays = $actualDays - date('d', $s) + 1;
            } elseif (date('Ym', $s) == $endYear.$endMonth) {
                $calculatedDays = $endDay;
            } else {
                $calculatedDays = $actualDays;
            }

            $total += ($amount / $actualDays) * $calculatedDays;
        }

        return $total;
    }

    private function getAmount(string $ym)
    {
        foreach ($this->currentData as $data) {
            if ($data->yearMonth == $ym) {
                return $data->amount;
            }
        }

        return 0;
    }
}
