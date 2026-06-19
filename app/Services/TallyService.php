<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TallyService
{
    protected string $url;

    public function __construct()
    {
        $this->url = config('tally.url');
    }

    /**
     * Send XML request to Tally
     */
    public function request(string $xml): string
    {
        Log::info('Tally Request XML', [
            'xml' => $xml
        ]);

        try {

            $response = Http::timeout(120)
                ->withHeaders([
                    'Content-Type' => 'text/xml',
                ])
                ->withBody($xml, 'text/xml')
                ->post($this->url);

            Log::info('Tally Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return $response->body();

        } catch (\Throwable $e) {

            Log::error('Tally Exception', [
                'message' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Companies
     */
    public function getCompanies()
    {
        $xml = <<<XML
            <ENVELOPE>
                <HEADER>
                    <VERSION>1</VERSION>
                    <TALLYREQUEST>EXPORT</TALLYREQUEST>
                    <TYPE>COLLECTION</TYPE>
                    <ID>List of Companies</ID>
                </HEADER>

                <BODY>
                    <DESC>
                        <STATICVARIABLES>
                            <SVEXPORTFORMAT>XML</SVEXPORTFORMAT>
                        </STATICVARIABLES>
                    </DESC>
                </BODY>
            </ENVELOPE>
            XML;

        return $this->request($xml);
    }



    /**
     * Ledgers
     */
    public function getLedgers(string $company)
    {
        $xml = <<<XML
    <ENVELOPE>
        <HEADER>
            <VERSION>1</VERSION>
            <TALLYREQUEST>EXPORT</TALLYREQUEST>
            <TYPE>COLLECTION</TYPE>
            <ID>Ledger Collection</ID>
        </HEADER>

        <BODY>
            <DESC>

                <STATICVARIABLES>
                    <SVCURRENTCOMPANY>{$company}</SVCURRENTCOMPANY>
                    <SVEXPORTFORMAT>XML</SVEXPORTFORMAT>
                </STATICVARIABLES>

                <TDL>
                    <TDLMESSAGE>

                        <COLLECTION NAME="Ledger Collection">
                            <TYPE>Ledger</TYPE>
                            <FETCH>NAME,PARENT</FETCH>
                        </COLLECTION>

                    </TDLMESSAGE>
                </TDL>

            </DESC>
        </BODY>
    </ENVELOPE>
    XML;

        return $this->request($xml);
    }



    /**
     * Ledger Wise Vouchers
     */

    protected function cleanXml(string $xml): string
    {
        return preg_replace(
            '/[\x00-\x08\x0B\x0C\x0E-\x1F]/',
            '',
            $xml
        );
    }

    public function getLedgerVouchers(string $company, string $ledgerName)
    {
        $company = htmlspecialchars($company, ENT_XML1);
        $ledgerName = htmlspecialchars($ledgerName, ENT_XML1);

        $xml = <<<XML
            <ENVELOPE>
                <HEADER>
                    <VERSION>1</VERSION>
                    <TALLYREQUEST>EXPORT</TALLYREQUEST>
                    <TYPE>COLLECTION</TYPE>
                    <ID>Ledger Vouchers</ID>
                </HEADER>

                <BODY>
                    <DESC>

                        <STATICVARIABLES>
                            <SVCURRENTCOMPANY>{$company}</SVCURRENTCOMPANY>
                            <SVEXPORTFORMAT>XML</SVEXPORTFORMAT>
                        </STATICVARIABLES>

                        <TDL>
                            <TDLMESSAGE>

                                <COLLECTION NAME="Ledger Vouchers">

                                    <TYPE>Voucher</TYPE>

                                    <FETCH>
                                        DATE,
                                        VOUCHERNUMBER,
                                        VOUCHERTYPENAME,
                                        PARTYLEDGERNAME,
                                        NARRATION,
                                        MASTERID,
                                        ALLLEDGERENTRIES.LIST.LEDGERNAME,
                                        ALLLEDGERENTRIES.LIST.AMOUNT,
                                        LEDGERENTRIES.LIST.LEDGERNAME,
                                        LEDGERENTRIES.LIST.AMOUNT
                                    </FETCH>

                                </COLLECTION>

                            </TDLMESSAGE>
                        </TDL>

                    </DESC>
                </BODY>
            </ENVELOPE>
        XML;


        return $this->request($xml);
    }


    public function getLedgerDetails($company, $ledgerName)
    {
        $company = htmlspecialchars($company, ENT_XML1);
        $ledgerName = htmlspecialchars($ledgerName, ENT_XML1);

        $xml = <<<XML
<ENVELOPE>

    <HEADER>
        <VERSION>1</VERSION>
        <TALLYREQUEST>EXPORT</TALLYREQUEST>
        <TYPE>COLLECTION</TYPE>
        <ID>LedgerDetails</ID>
    </HEADER>

    <BODY>

        <DESC>

            <STATICVARIABLES>
                <SVCURRENTCOMPANY>{$company}</SVCURRENTCOMPANY>
                <SVEXPORTFORMAT>XML</SVEXPORTFORMAT>
            </STATICVARIABLES>

            <TDL>

                <TDLMESSAGE>

                    <COLLECTION NAME="LedgerDetails">

                        <TYPE>Ledger</TYPE>

                        <FILTER>LedgerFilter</FILTER>

                        <FETCH>
                            NAME,
                            OPENINGBALANCE,
                            CLOSINGBALANCE
                        </FETCH>

                    </COLLECTION>

                    <SYSTEM TYPE="Formulae" NAME="LedgerFilter">
                        \$Name = "{$ledgerName}"
                    </SYSTEM>

                </TDLMESSAGE>

            </TDL>

        </DESC>

    </BODY>

</ENVELOPE>
XML;

        return $this->request($xml);
    }


    public function getVoucherTypes(string $company)
{
    $xml = <<<XML
<ENVELOPE>
    <HEADER>
        <VERSION>1</VERSION>
        <TALLYREQUEST>EXPORT</TALLYREQUEST>
        <TYPE>COLLECTION</TYPE>
        <ID>Voucher Type Collection</ID>
    </HEADER>

    <BODY>
        <DESC>
            <STATICVARIABLES>
                <SVCURRENTCOMPANY>{$company}</SVCURRENTCOMPANY>
                <SVEXPORTFORMAT>\$\$SysName:XML</SVEXPORTFORMAT>
            </STATICVARIABLES>

            <TDL>
                <TDLMESSAGE>

                    <COLLECTION NAME="Voucher Type Collection">
                        <TYPE>Voucher Type</TYPE>
                        <FETCH>Name</FETCH>
                    </COLLECTION>

                </TDLMESSAGE>
            </TDL>

        </DESC>
    </BODY>
</ENVELOPE>
XML;


    return $this->request($xml);
}
}