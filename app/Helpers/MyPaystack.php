<?php

namespace App\Helpers;

use Paystack;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class MyPaystack extends Paystack
{

    private $contains;
    protected $trsnref;
    protected $bank;
    private $secretKey;
    private $baseUrl;
    private $bearerHeader;

    public function __construct()
    {
        $this->setKey();
        $this->setBaseUrl();
        $this->setBearerHeader();
    }

    /**
     * Get secret key from Paystack config file
     */
    private function setKey()
    {
        $this->secretKey = Config('paystack.secretKey');
    }

    /**
     * Get Base Url from Paystack config file
     */
    private function setBaseUrl()
    {
        $this->baseUrl = Config('paystack.paymentUrl');
    }

    //set bearer header
    final private function setBearerHeader()
    {
        $this->bearerHeader = Http::withHeaders(["Authorization" => "Bearer {$this->secretKey}"]);
    }

    //get response
    private function contains($response)
    {
        $contains = \json_decode($response->getBody()->getContents());
        return $contains;
    }

    //Get Bank method
    private function getBankCode($bank)
    {
        $codename = Arr::pluck($this->getBank(), 'code', 'name');
        $code = $codename[$bank];

        return $code;
    }

    //Get banks
    public function getBank()
    {
        $response = $this->bearerHeader->get($this->baseUrl . '/bank');
        $contains = $this->contains($response)->data;

        return $contains;
    }
    //BVN verification method
    public function verifyBvn($bvn)
    {

        $response = $this->bearerHeader->Post($this->baseUrl . '/bank/resolve_bvn/:' . $bvn);
        return $this->contains($response);
    }

    //Bank validation method
    public function validateBank($bank, $accountNumber)
    {
        $code = $this->getBankCode($bank);
        $response = $this->bearerHeader
            ->get($this->baseUrl . '/bank/resolve?account_number=' . $accountNumber . '&bank_code=' . $code);

        return $this->contains($response);
    }

    // verify bvn and account name match
    public function verifyBvnAccountName($bvn, $accountNumber, $bank, $first_name, $last_name, $middle_name = NULL)
    {
        $code = $this->getBankCode($bank);
        $response = $this->basicHeader->Post($this->baseUrl . '/bvn/match', [
            'BVN' => $bvn,
            'account_number' => $accountNumber,
            'bank_code' => $code,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'middle_name' => $middle_name,
        ]);

        return $this->contains($response);
    }

    public function getPaymentData($trsnref)
    {
        $response = $this->bearerHeader
            ->get($this->baseUrl . '/transaction/verify/' . $trsnref);

        return $response;
    }


    public function agentVerification($type, $name, $account_number, $bank_code, $currency){
        $response = $this->bearerHeader->post($this->baseUrl . '/transferrecipient',[

            'type' => $type,
            'name' => $name,
            'account_number' => $account_number,
            'bank_code' => $bank_code,
            'currency' => $currency
        ]);

        return $this->contains($response);
        //
    }


    public function sendMoney($balance,$amount,$recipient,$reason){
        $response = $this->bearerHeader->post($this->baseUrl . '/transfer', [
                    'source' => $balance,
                    'amount' => $amount,
                    'recipient' => $recipient,
                    'reason' => $reason

        ]);

        return $this->contains($response);
    }



    public function Otp($transfer_code,$otp){
        $response =$this->bearerHeader->post($this->baseUrl. '/transfer/finalize_transfer',[

                     "transfer_code" =>$transfer_code ,
                     "otp" => $otp
            ]);
            return $this->contains($response);
    }
}
