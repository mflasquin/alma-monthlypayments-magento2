<?php


namespace Alma\MonthlyPayments\Gateway\Config\PaymentPlans;


use Alma\API\Entities\FeePlan;

interface PaymentPlanConfigInterface
{
    /**
     * @return array
     */
    public static function transientKeys();

    /**
     * @param FeePlan $plan
     * @return string
     */
    public static function keyForFeePlan(FeePlan $plan);

    /**
     * @param FeePlan $plan
     * @return array
     */
    public static function defaultConfigForFeePlan(FeePlan $plan);

    /**
     * @return array
     */
    public function toArray();

    /**
     * @return string
     */
    public function planKey();

    /**
     * @return array
     */
    public function getPaymentData();

    /**
     * @return string
     */
    public function kind();

    /**
     * @return bool
     */
    public function isAllowed();

    /**
     * @return bool
     */
    public function isEnabled();

    /**
     * @return int
     */
    public function installmentsCount();

    /**
     * @return bool
     */
    public function isDeferred();

    /**
     * @return string|null
     */
    public function deferredType();

    /**
     * @return int
     */
    public function deferredDays();

    /**
     * @return int
     */
    public function deferredMonths();

    /**
     * Returns deferred duration in days – approximate value (invariably using 30 days for 1 month) but it's OK as it's
     * mainly being used for sorting purposes.
     */
    public function deferredDurationInDays();

    /**
     * @return int
     */
    public function deferredDuration();

    /**
     * @return int
     */
    public function minimumAmount();

    /**
     * @param int $amount
     * @return mixed
     */
    public function setMinimumAmount(int $amount);

    /**
     * @return int
     */
    public function minimumAllowedAmount();

    /**
     * @return int
     */
    public function maximumAmount();

    /**
     * @param int $amount
     * @return mixed
     */
    public function setMaximumAmount(int $amount);

    /**
     * @return int
     */
    public function maximumAllowedAmount();

    /**
     * @return int
     */
    public function variableMerchantFees();

    /**
     * @return int
     */
    public function fixedMerchantFees();

    /**
     * @return int
     */
    public function variableCustomerFees();

    /**
     * @return int
     */
    public function fixedCustomerFees();

    /**
     * @return string|null
     */
    public function logoFileName();
}
