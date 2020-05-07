Changelog
=========

v1.1.1
------

* Removes `api_root` override that should not have been committed

v1.1.0
------

* Adds Web API endpoints to get an Alma URL for an order paid with Alma, and to validate such a payment
  upon customer return. See `etc/webapi.xml` for endpoints URLs and `Model/Api/Payment.php` for 
  implementation.
  
* Adds 3 API Configuration fields to override `return_url`, `ipn_callback_url` and `customer_cancel_url` 
  in created payments.

v1.0.7
------

* Order status fix

v1.0.6
------

* Order status fix

v1.0.5
------

* Fixes a bug with order status management on Magento 2.3.2
* Adds some data collection for risk/fraud prevention

v1.0.4
------

* Fixes obfuscated API keys values being retried as Alma API credentials


v1.0.3
------

* Fixes incorrect version requirements in Composer manifest

v1.0.2
------

* Fixes class not found `Throwable` on some PHP versions

v1.0.1
------

Let's start following semver.

* Switches logo image to SVG
* Adds User-Agent string containing the module's version, Magento version, PHP client and PHP versions, to all requests going to Alma's API.

v1.0.0
------

This version evolved for a while without any version bump 🤷‍♂️  
Features in the latest push to this release:

* Module can be configured in Test and Live mode
* A message displays below the cart and in the minicart to indicate whether the purchase is eligible to monthly installments
* The module adds a payment method to the checkout, which redirects the user to Alma's payment page.  
If everything goes right (i.e. Customer doesn't cancel, pays the right amount, ... ), the order is validated upon customer return.