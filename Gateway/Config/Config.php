<?php
/**
 * 2018 Alma / Nabla SAS
 *
 * THE MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and
 * to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the
 * Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 * CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 *
 * @author    Alma / Nabla SAS <contact@getalma.eu>
 * @copyright 2018 Alma / Nabla SAS
 * @license   https://opensource.org/licenses/MIT The MIT License
 *
 */

namespace Alma\MonthlyPayments\Gateway\Config;

use Alma\API\Client;
use Alma\API\RequestError;
use Alma\MonthlyPayments\Gateway\Config\PaymentPlans\PaymentPlansConfigInterface;
use Alma\MonthlyPayments\Gateway\Config\PaymentPlans\PaymentPlansConfigInterfaceFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Config extends \Magento\Payment\Gateway\Config\Config
{
    const CODE = 'alma_monthly_payments';

    const ORDER_PAYMENT_URL = 'PAYMENT_URL';

    const CONFIG_SORT_ORDER = 'sort_order';
    const CONFIG_DEBUG = 'debug';
    const CONFIG_API_MODE = 'api_mode';
    const CONFIG_LIVE_API_KEY = 'live_api_key';
    const CONFIG_TEST_API_KEY = 'test_api_key';
    const CONFIG_ELIGIBILITY_MESSAGE = 'eligibility_message';
    const CONFIG_NON_ELIGIBILITY_MESSAGE = 'non_eligibility_message';
    const CONFIG_SHOW_ELIGIBILITY_MESSAGE = 'show_eligibility_message';
    const CONFIG_TITLE = 'title';
    const CONFIG_DESCRIPTION = 'description';
    const CONFIG_EXCLUDED_PRODUCT_TYPES = 'excluded_product_types';
    const CONFIG_EXCLUDED_PRODUCTS_MESSAGE = 'excluded_products_message';
    const CONFIG_FULLY_CONFIGURED = 'fully_configured';
    const CONFIG_RETURN_URL = 'return_url';
    const CONFIG_IPN_CALLBACK_URL = 'ipn_callback_url';
    const CONFIG_CUSTOMER_CANCEL_URL = 'customer_cancel_url';
    const CONFIG_MERCHANT_ID = 'merchant_id';
    const CONFIG_PAYMENT_PLANS = 'payment_plans';

    private $pathPattern;
    private $methodCode;
    private $plansConfigFactory;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param PaymentPlansConfigInterfaceFactory $plansConfigFactory
     * @param null $methodCode
     * @param string $pathPattern
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        PaymentPlansConfigInterfaceFactory $plansConfigFactory,
        $methodCode = null,
        $pathPattern = self::DEFAULT_PATH_PATTERN
    ) {
        parent::__construct($scopeConfig, $methodCode, $pathPattern);

        $this->methodCode = $methodCode;
        $this->pathPattern = $pathPattern;
        $this->plansConfigFactory = $plansConfigFactory;
    }

    /**
     * @inheritdoc
     */
    public function setMethodCode($methodCode)
    {
        $this->methodCode = $methodCode;
    }

    /**
     * @inheritdoc
     */
    public function setPathPattern($pathPattern)
    {
        $this->pathPattern = $pathPattern;
    }

    /**
     * @param string $field
     * @return string
     */
    public function getFieldPath(string $field)
    {
        return sprintf($this->pathPattern, $this->methodCode, $field);
    }

    /**
     * @param $field
     * @param null $default
     * @param null $storeId
     * @return mixed|null
     */
    public function get($field, $default = null, $storeId = null)
    {
        $value = parent::getValue($field, $storeId);

        if ($value === null) {
            $value = $default;
        }

        return $value;
    }

    /**
     * @return int
     */
    public function getSortOrder()
    {
        return (int)$this->get(self::CONFIG_SORT_ORDER);
    }

    /**
     * @return bool
     */
    public function canLog()
    {
        return (bool)(int)$this->get(self::CONFIG_DEBUG, false);
    }

    /**
     * @return mixed|null
     */
    public function getActiveMode()
    {
        return $this->get(self::CONFIG_API_MODE, Client::LIVE_MODE);
    }

    /**
     * @return mixed|null
     */
    public function getActiveAPIKey()
    {
        $mode = $this->getActiveMode();

        switch ($mode) {
            case Client::LIVE_MODE:
                $apiKeyType = self::CONFIG_LIVE_API_KEY;
                break;
            default:
                $apiKeyType = self::CONFIG_TEST_API_KEY;
        }

        return $this->get($apiKeyType);
    }

    /**
     * @return mixed|null
     */
    public function getLiveKey()
    {
        return $this->get(self::CONFIG_LIVE_API_KEY, '');
    }

    /**
     * @return mixed|null
     */
    public function getTestKey()
    {
        return $this->get(self::CONFIG_TEST_API_KEY, '');
    }

    /**
     * @return bool
     */
    public function needsAPIKeys()
    {
        return empty(trim($this->getLiveKey())) || empty(trim($this->getTestKey()));
    }

    /**
     * @return mixed|null
     */
    public function getEligibilityMessage()
    {
        return $this->get(self::CONFIG_ELIGIBILITY_MESSAGE);
    }

    /**
     * @return mixed|null
     */
    public function getNonEligibilityMessage()
    {
        return $this->get(self::CONFIG_NON_ELIGIBILITY_MESSAGE);
    }

    /**
     * @return bool
     */
    public function showEligibilityMessage()
    {
        return (bool)(int)$this->get(self::CONFIG_SHOW_ELIGIBILITY_MESSAGE);
    }

    /**
     * @return mixed|null
     */
    public function getPaymentButtonTitle()
    {
        return $this->get(self::CONFIG_TITLE);
    }

    /**
     * @return mixed|null
     */
    public function getPaymentButtonDescription()
    {
        return $this->get(self::CONFIG_DESCRIPTION);
    }

    /**
     * @return false|string[]
     */
    public function getExcludedProductTypes()
    {
        return explode(',', $this->get(self::CONFIG_EXCLUDED_PRODUCT_TYPES));
    }

    /**
     * @return mixed|null
     */
    public function getExcludedProductsMessage()
    {
        return $this->get(self::CONFIG_EXCLUDED_PRODUCTS_MESSAGE);
    }

    /**
     * @return bool
     */
    public function isFullyConfigured()
    {
        return !$this->needsAPIKeys() && (bool)(int)$this->get(self::CONFIG_FULLY_CONFIGURED, false);
    }

    /**
     * @return mixed|null
     */
    public function getReturnUrl()
    {
        return $this->get(self::CONFIG_RETURN_URL);
    }

    /**
     * @return mixed|null
     */
    public function getIpnCallbackUrl()
    {
        return $this->get(self::CONFIG_IPN_CALLBACK_URL);
    }

    /**
     * @return mixed|null
     */
    public function getCustomerCancelUrl()
    {
        return $this->get(self::CONFIG_CUSTOMER_CANCEL_URL);
    }

    /**
     * @return mixed|null
     */
    public function getMerchantId()
    {
        return $this->get(self::CONFIG_MERCHANT_ID);
    }

    /**
     * @return PaymentPlansConfigInterface
     */
    public function getPaymentPlansConfig()
    {
        $data = $this->get(self::CONFIG_PAYMENT_PLANS, []);

        /** @var PaymentPlansConfigInterface $plansConfig */
        $plansConfig = $this->plansConfigFactory->create(["data" => $data]);

        if (empty($data) && $this->isFullyConfigured()) {
            // No plans config data has ever been saved – fetch what we need
            try {
                $plansConfig->updateFromApi();
            } catch (RequestError $e) {
                // TODO: log error (circumvent circular dependency between Logger & Config)
            }
        }

        return $plansConfig;
    }
}
