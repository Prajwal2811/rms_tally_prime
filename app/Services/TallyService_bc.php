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
                    <ID>List of Ledgers</ID>
                </HEADER>

                <BODY>
                    <DESC>

                        <STATICVARIABLES>
                            <SVCURRENTCOMPANY>{$company}</SVCURRENTCOMPANY>
                            <SVEXPORTFORMAT>XML</SVEXPORTFORMAT>
                        </STATICVARIABLES>

                    </DESC>
                </BODY>
            </ENVELOPE>
            XML;

        return $this->request($xml);
    }


    /**
     * Ledger Invoices
    */

    public function getLedgerInvoices($company, $ledger)
    {
        $xml = <<<XML
    <ENVELOPE>
        <HEADER>
            <VERSION>1</VERSION>
            <TALLYREQUEST>Export</TALLYREQUEST>
            <TYPE>Collection</TYPE>
            <ID>Voucher Collection</ID>
        </HEADER>

        <BODY>
            <DESC>

                <STATICVARIABLES>
                    <SVCURRENTCOMPANY>{$company}</SVCURRENTCOMPANY>
                    <SVEXPORTFORMAT>XML</SVEXPORTFORMAT>
                </STATICVARIABLES>

                <TDL>
                    <TDLMESSAGE>

                        <COLLECTION NAME="Voucher Collection" ISMODIFY="No">
                            <TYPE>Voucher</TYPE>
                            <FETCH>Date, VoucherNumber, PartyLedgerName, Amount</FETCH>

                            <FILTERS>SalesFilter</FILTERS>
                        </COLLECTION>

                        <SYSTEM TYPE="Formulae" NAME="SalesFilter">
                            \$VoucherTypeName = "Sales"
                        </SYSTEM>

                    </TDLMESSAGE>
                </TDL>

            </DESC>
        </BODY>
    </ENVELOPE>
    XML;

        return $this->request($xml);
    }
     

    public function getLedgerReceipts($company, $ledger)
    {
        $ledger = htmlspecialchars($ledger, ENT_XML1);

        $xml = <<<XML
    <ENVELOPE>
        <HEADER>
            <VERSION>1</VERSION>
            <TALLYREQUEST>Export</TALLYREQUEST>
            <TYPE>Collection</TYPE>
            <ID>Receipt Voucher Collection</ID>
        </HEADER>

        <BODY>
            <DESC>

                <STATICVARIABLES>
                    <SVCURRENTCOMPANY>{$company}</SVCURRENTCOMPANY>
                    <SVEXPORTFORMAT>XML</SVEXPORTFORMAT>
                </STATICVARIABLES>

                <TDL>
                    <TDLMESSAGE>

                        <COLLECTION NAME="Receipt Voucher Collection" ISMODIFY="No">
                            <TYPE>Voucher</TYPE>

                            <FETCH>
                                Date,
                                VoucherNumber,
                                PartyLedgerName,
                                Amount,
                                VoucherTypeName
                            </FETCH>

                            <FILTERS>
                                ReceiptFilter,
                                LedgerFilter
                            </FILTERS>

                        </COLLECTION>

                        <SYSTEM TYPE="Formulae" NAME="ReceiptFilter">
                            \$VoucherTypeName = "Receipt"
                        </SYSTEM>

                        <SYSTEM TYPE="Formulae" NAME="LedgerFilter">
                            \$PartyLedgerName = "{$ledger}"
                        </SYSTEM>

                    </TDLMESSAGE>
                </TDL>

            </DESC>
        </BODY>
    </ENVELOPE>
    XML;

        return $this->request($xml);
    }
}