<?php

namespace Alma\MonthlyPayments\Helpers\ShareOfCheckout;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class DateHelper extends AbstractHelper
{
    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    /**
     *
     * @param string $from
     * @param string $shareOfCheckoutEnabledDate
     * @param string|null $to
     *
     * @return array
     */
    public function getDatesInInterval(string $shareOfCheckoutEnabledDate, string $from, string $to = ''): array
    {
        $datesInInterval = [];
        if ($shareOfCheckoutEnabledDate == '' || $from === '') {
            return $datesInInterval;
        }
        if ($to === '') {
            $to = date('Y-m-d');
        }
        $startTimestamp =  $from;

        while ($startTimestamp < $to) {
            if ($startTimestamp > $shareOfCheckoutEnabledDate) {
                $datesInInterval[] = $startTimestamp;
            }
            $startTimestamp = $this->dateMoreOneDay($startTimestamp);
        }
        return $datesInInterval;
    }

    private function dateMoreOneDay(string $date): string
    {
        return date('Y-m-d', strtotime('+1 day', strtotime($date)));
    }

}