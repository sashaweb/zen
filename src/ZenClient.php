<?php

namespace App;

use GuzzleHttp;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;

class ZenClient
{
    private $_client;

    const HTTP_GET = 'GET';
    const HTTP_POST = 'POST';
    const HTTP_PATCH = 'PATCH';
    const HTTP_DELETE = 'DELETE';

    const API_KEY = 'SomeApiKey';
    const BASE_URI = 'https://api.zen.com/v1/';

    function __construct()
    {
        $this->_client = new GuzzleHttp\Client();
    }

    private function send($httpMethod, $uri, $requestOptions = null)
    {
        $options = [
            'base_uri' => self::BASE_URI,
            'headers' => [
                'Authorization' => self::API_KEY,
                'request-id' => \uniqid()
            ]
        ];

        if (!empty($requestOptions['headers'])) {
            $options['headers'] = array_merge($options['headers'], $requestOptions['headers']);
        }

        if (!empty($requestOptions['query'])) {
            $options['query'] = $requestOptions['query'];
        }

        if (!empty($requestOptions['data'])) {
            $options['json'] = $requestOptions['data'];
        }

        try {
            $response = $this->_client->request($httpMethod, $uri, $options);
            return \json_decode($response->getBody()->getContents());
        } catch (ClientException $e) {
            print_r( \json_decode($e->getResponse()->getBody()->getContents()));
        } catch (GuzzleException $e) {
            echo $e->getMessage();
        } catch (ConnectException $e) {
            echo $e->getMessage();
        }
    }


    public function getTerminals($query)
    {
        $options['query'] = $query;
        return $this->send(self::HTTP_GET, 'transactions', $options);
    }

    public function createTransaction($data)
    {
        $options['data'] = $data;
        return $this->send(self::HTTP_POST, 'transactions', $options);
    }

    public function getTransaction($id)
    {
        return $this->send(self::HTTP_GET, "transactions/{$id}");
    }

    public function getTransactionDetails($merchantTransactionId)
    {
        return $this->send(self::HTTP_GET, "transactions/merchant/{$merchantTransactionId}");
    }

    public function refund($data)
    {
        $options['data'] = $data;
        return $this->send(self::HTTP_POST, "transactions/refund", $options);
    }

    public function renewAuthorization($data)
    {
        $options['data'] = $data;
        return $this->send(self::HTTP_POST, "transactions/renewAuthorization", $options);
    }

    public function getPaymentProfilesSavedCards($query, $extended = null, $externalCustomerId = null)
    {
        $options['query'] = $query;
        $options['headers'] = [
            'extended' => $extended,
            'external-customer-id' => $externalCustomerId
        ];
        return $this->send(self::HTTP_GET, 'payment-profiles/card', $options);
    }

    public function removePaymentProfilesSavedCard($id, $externalCustomerId = null)
    {
        $options['headers'] = [
            'external-customer-id' => $externalCustomerId
        ];
        return $this->send(self::HTTP_DELETE, "payment-profiles/card/{$id}", $options);
    }

    public function getPaymentProfilesSavedCard($id, $terminalId)
    {
        $options['headers'] = [
            'terminal-id' => $terminalId
        ];
        return $this->send(self::HTTP_GET, "payment-profiles/card/{$id}", $options);
    }

    public function updatePaymentProfilesSavedCard($id, $terminalId, $data, $externalCustomerId = null)
    {
        $options['headers'] = [
            'terminal-id' => $terminalId,
            'external-customer-id' => $externalCustomerId
        ];
        $options['data'] = $data;
        return $this->send(self::HTTP_PATCH, "payment-profiles/card/{$id}", $options);
    }

    public function CalculateAuthorizationAmount($data)
    {
        $options['data'] = $data;
        return $this->send(self::HTTP_POST, "authorization/calculate", $options);
    }

    public function CreateCustomer($terminalId, $data)
    {
        $options['headers'] = [
            'terminal-id' => $terminalId,
        ];
        $options['data'] = $data;
        return $this->send(self::HTTP_POST, "customers", $options);
    }

    public function FetchSavedSubscriptionCustomerList($terminalId, $query)
    {
        $options['headers'] = [
            'terminal-id' => $terminalId,
        ];
        $options['query'] = $query;
        return $this->send(self::HTTP_GET, "customers", $options);
    }

    public function FetchSavedSubscriptionCustomer($merchantCustomerId, $terminalId)
    {
        $options['headers'] = [
            'terminal-id' => $terminalId,
        ];
        return $this->send(self::HTTP_GET, "customers/{$merchantCustomerId}", $options);
    }

    public function UpdateSavedSubscriptionCustomer($merchantCustomerId, $terminalId, $data)
    {
        $options['headers'] = [
            'terminal-id' => $terminalId,
        ];
        $options['data'] = $data;
        return $this->send(self::HTTP_PATCH, "customers/{$merchantCustomerId}", $options);
    }

    public function RemoveSavedSubscriptionCustomer($merchantCustomerId, $terminalId)
    {
        $options['headers'] = [
            'terminal-id' => $terminalId,
        ];
        return $this->send(self::HTTP_DELETE, "customers/{$merchantCustomerId}", $options);
    }


    public function GetListSavedCardsByCustomer($terminalId, $query, $extended = null, $externalCustomerId = null)
    {
        $options['query'] = $query;
        $options['headers'] = [
            'terminal-id' => $terminalId,
            'extended' => $extended,
            'external-customer-id' => $externalCustomerId
        ];
        return $this->send(self::HTTP_GET, "payout-profiles/card", $options);
    }

    public function OnceCustomerCardSave($data, $terminalId, $extended = null, $externalCustomerId = null)
    {
        $options['headers'] = [
            'terminal-id' => $terminalId,
            'extended' => $extended,
            'external-customer-id' => $externalCustomerId
        ];
        $options['data'] = $data;
        return $this->send(self::HTTP_POST, "payout-profiles/card", $options);
    }

    public function RemovesSavedPayoutCard($terminalId, $extended = null, $externalCustomerId = null)
    {
        $options['headers'] = [
            'terminal-id' => $terminalId,
            'extended' => $extended,
            'external-customer-id' => $externalCustomerId
        ];
        return $this->send(self::HTTP_POST, "payout-profiles/card", $options);
    }

    public function CreatePayout($terminalId, $data)
    {
        $options['headers'] = [
            'terminal-id' => $terminalId,
        ];
        $options['data'] = $data;
        return $this->send(self::HTTP_POST, "payout-profiles/card", $options);
    }

}

