<?php
/**
 * donations-free plugin for Craft CMS 3.x
 *
 * Free Braintree Donation System
 *
 * @link      https://endurant.org
 * @copyright Copyright (c) 2017 endurant
 */

namespace endurant\donationsfree\services;

use endurant\donationsfree\SmptMailer;

use Craft;
use craft\base\Component;

use endurant\donationsfree\models\Customer;
use endurant\donationsfree\models\Address;
use endurant\donationsfree\models\Card;
use endurant\donationsfree\models\Transaction;

/**
 * Donation Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    endurant
 * @package   Donationsfree
 * @since     1.0.0
 */
class DonationService extends Component
{
    // Public Methods
    // =========================================================================

    public function donate(array $params)
    {
        $customer = Customer::create($params);
        $address = Address::create($params);
        $card = new Card();
        $transaction = new Transaction();
        $transaction->amount = intval($params['amount']);
        $transaction->projectId = intval($params['projectId']);
        $transaction->projectName = $params['projectName'];

        $braintreeService = SmptMailer::$PLUGIN->braintreeService;

        $braintreeService->createCustomer($customer);
        $braintreeService->createAddress($customer, $address);
        $braintreeService->createCard($customer, $card, $params['nonce']);
        $braintreeService->createTransaction($customer, $transaction);

       try {
            $address = SmptMailer::$PLUGIN->addressService->saveAddress($address);
            $customer->addressId = $address->id;
            $customer = SmptMailer::$PLUGIN->customerService->saveCustomer($customer);
            $card->customerId = $customer->id;
            $card = SmptMailer::$PLUGIN->cardService->saveCard($card);
            $transaction->cardId = $card->id;
            $transaction = SmptMailer::$PLUGIN->transactionService->saveTransaction($transaction);

       } catch(\endurant\donationsfree\errors\DbSmtpMailerPluginException $e) {
           
       }
    }
}
