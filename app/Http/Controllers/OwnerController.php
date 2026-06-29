<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Owner;
use App\Models\VoucherMapping;
use App\Models\Accountant;
use App\Models\Collector;
use Illuminate\Support\Facades\Hash;
use App\Services\TallyService;

class OwnerController extends Controller
{
    public function subscription()
    {
        return view('owner.subscription');
    }

    public function subscribe(Request $request)
    {
        $owner = auth('owner')->user();

        $owner->update([
            'is_subscribed' => "true"
        ]);

        return redirect()->route('owner.tally.dashboard')
            ->with('success', 'Subscription activated successfully.');
    }

    // Register form submit
    public function registerOwner(Request $request)
    {
        $request->validate([
            'owner_name' => 'required',
            'email' => 'required|email|unique:rms_owners,email',
            'phone' => 'nullable',
            'business_name' => 'required',
            'business_type' => 'required',
            'address' => 'nullable',
            'password' => 'required|min:6|confirmed',
        ]);

        Owner::create([
            'owner_name' => $request->owner_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'business_name' => $request->business_name,
            'business_type' => $request->business_type,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'status' => 'active',
            'is_subscribed' => "false",
            'subscription_expiry' => null,
        ]);

        return redirect()->route('owner.login')->with('success', 'Registration completed successfully. Please login.');
    }


    public function authenticate(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'status' => 'active'
        ];

        if (Auth::guard('owner')->attempt($credentials, $request->get('remember'))) {

            $owner = Auth::guard('owner')->user();

            Log::info('Owner login successful', [
                'email' => $owner->email,
                'name' => $owner->owner_name,
                'time' => now()
            ]);

            // Subscription Check
            if ($owner->is_subscribed !== 'true') {

                return redirect()->route('owner.subscription')
                    ->with('error', 'Please buy a subscription plan first.');
            }

            return redirect()->route('owner.tally.dashboard');

        } else {

            Log::warning('Owner login failed', [
                'email' => $request->email,
                'time' => now()
            ]);

            session()->flash('error', 'Either Email/Password is incorrect');

            return back()->withInput($request->only('email'));
        }
    }



    public function signOut()
    {
        // Capture owner info before logout
        $owner = Auth::guard('owner')->user();

        if ($owner) {
            Log::info('Owner logged out', [
                'email' => $owner->email,
                'name' => $owner->owner_name,
                'time' => now()
            ]);
        }

        Auth::guard('owner')->logout(); // Logs out the owner user
        session()->flash('success', 'You have been logged out successfully.');

        return redirect()->route('owner.login'); // Redirect to named route
    }

    public function dashboard()
    {
        return view('owner.dashboard');

    }


    public function accountants()
    {
        $accountants = Accountant::all();
        return view('owner.accountant.accountants-list', compact('accountants'));

    }

    public function changeStatus(Request $request)
    {
        $accountant = Accountant::findOrFail($request->id);

        $accountant->status = $accountant->status == 'active'
            ? 'inactive'
            : 'active';

        $accountant->save();

        return redirect()->route('owner.accountants.index')->with('success', 'Accountant status updated successfully!');
    }

    public function createAccountant()
    {
        return view('owner.accountant.accountant-create');

    }


    public function storeAccountant(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:rms_accountants,email',
            'phone' => 'required|min:10|max:15',
            'address' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);


        $accountant = Accountant::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'pass' => $request->password,
            'status' => 'active',
        ]);


        return redirect()->route('owner.accountants.index')->with('success', 'Accountant created successfully!');

    }


    public function editAccountant($id)
    {
        $accountant = Accountant::find($id);
        return view('owner.accountant.accountant-edit', compact('accountant'));

    }



    public function updateAccountant(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:rms_accountants,email,' . $id,
            'phone' => 'required|digits:10|unique:rms_accountants,phone,' . $id,
            'address' => 'required|string|max:255',
        ]);


        $accountant = Accountant::findOrFail($id);

        $accountant->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('owner.accountants.index')->with('success', 'Accountant updated successfully!');
    }


    public function collectors()
    {
        $collectors = Collector::select('rms_accountants.*', 'rms_collectors.*', 'rms_accountants.name as accountant_name')
            ->from('rms_collectors')
            ->join('rms_accountants', 'rms_collectors.accountant_id', '=', 'rms_accountants.id')
            ->get();
        // echo "<pre>"; print_r($collectors); die;
        return view('owner.collectors.collectors-list', compact('collectors'));

    }

    public function createCollector()
    {
        return view('owner.collectors.collector-create');

    }


    public function storeCollector(Request $request)
    {
        $request->validate([
            'accountant_id' => 'required|exists:rms_accountants,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:rms_collectors,email',
            'phone' => 'required|min:10|max:15|unique:rms_collectors,phone',
            'address' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        Collector::create([
            'accountant_id' => $request->accountant_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'pass' => $request->password,
            'status' => 'active',
        ]);

        return redirect()
            ->route('owner.collectors.index')
            ->with('success', 'Collector created successfully!');
    }


    public function changeStatusCollector(Request $request)
    {
        $collector = Collector::findOrFail($request->id);

        $collector->status = $collector->status == 'active'
            ? 'inactive'
            : 'active';

        $collector->save();

        return redirect()->route('owner.collectors.index')->with('success', 'Collector status updated successfully!');
    }


    public function editCollector($id)
    {
        $collector = Collector::find($id);
        return view('owner.collectors.collector-edit', compact('collector'));

    }


    public function updateCollector(Request $request, $id)
    {
        $request->validate([
            'accountant_id' => 'required|exists:rms_accountants,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:rms_collectors,email,' . $id,
            'phone' => 'required|digits:10|unique:rms_collectors,phone,' . $id,
            'address' => 'required|string|max:255',
        ]);


        $collector = Collector::findOrFail($id);

        $collector->update([
            'accountant_id' => $request->accountant_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('owner.collectors.index')->with('success', 'Collector updated successfully!');
    }







    protected TallyService $tally;

    public function __construct(TallyService $tally)
    {
        $this->tally = $tally;
    }


    public function company()
    {
        // echo "done"; die;
        try {

            $xml = $this->tally->getCompanies();

            $xmlObj = simplexml_load_string($xml);

            if ($xmlObj) {
                $tallyConnected = true;
            }


            $companies = [];

            if ($xmlObj) {

                // XML structure ke hisab se adjust karna padega
                $nodes = $xmlObj->xpath("//*[local-name()='COMPANY']");

                if ($nodes) {

                    foreach ($nodes as $company) {

                        $companies[] = [
                            'name' => (string) ($company['NAME'] ?? $company->NAME)
                        ];
                    }
                }
            }

            return view(
                'owner.tally.index',
                compact('companies', 'xml', 'tallyConnected')
            );

        } catch (\Exception $e) {

            return view('owner.tally.index', [
                'companies' => [],
                'tallyConnected' => false,
                'error' => $e->getMessage()
            ]);
        }
    }





    public function ledgers(Request $request)
    {
        $company = $request->company;

        $xml = $this->tally->getLedgers($company);

        dd($xml);
    }



    public function syncAll()
    {
        try {
            // Tally se data fetch
            $companies = $this->tally->getCompanies();

            session([
                'last_sync' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tally Sync Completed',
                'data' => [
                    'companies' => $companies,
                ]
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function companyDetails($company)
    {
        $company = urldecode($company);

        return view(
            'owner.tally.company-details',
            compact('company')
        );
    }



  public function companyLedgers($company)
{
    try {

        $company = urldecode($company);

        $xml = $this->tally->getLedgers($company);

        // Remove invalid XML entities like &#4;
        $xml = preg_replace('/&#x?0*4;?/i', '', $xml);
        $xml = preg_replace('/&#[0-8];|&#1[0-9];|&#2[0-9];|&#3[0-1];/', '', $xml);

        // Remove control characters
        $xml = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $xml);

        libxml_use_internal_errors(true);

        $xmlObj = simplexml_load_string($xml);

        if ($xmlObj === false) {
            foreach (libxml_get_errors() as $error) {
                dump($error->message);
            }
        }

        $xmlObj = simplexml_load_string($xml);

        $ledgers = [];

        if ($xmlObj) {

            $nodes = $xmlObj->xpath("//*[local-name()='LEDGER']");

            if ($nodes) {

                foreach ($nodes as $ledger) {

                    $name  = (string)($ledger['NAME'] ?? '');
                    $under = (string)($ledger->PARENT ?? '');

                    // Sirf Sundry Debtors aur Sundry Creditors
                    if (
                        in_array(
                            trim($under),
                            ['Sundry Debtors', 'Sundry Creditors']
                        )
                    ) {
                        $ledgers[] = [
                            'name'  => $name,
                            'under' => $under,
                        ];
                    }
                }
            }
        }

        return view(
            'owner.tally.ledgers',
            compact('company', 'ledgers')
        );

    } catch (\Exception $e) {
        dd($e->getMessage());
    }
}

    public function ledgerVouchers($company, $ledger, $under = null)
    {
    
        $under   = urldecode($under);

        try {

            $voucherMappings = VoucherMapping::where('company', $company)
                            ->pluck('mapped_to', 'voucher_type')
                            ->toArray();

            $company = urldecode($company);
            $ledger  = urldecode($ledger);

            $xml = $this->tally->getLedgerVouchers(
                $company,
                $ledger
            );

            // echo "<pre>"; print_r($xml); die;

            $xml = preg_replace('/&#(?:0*4);?/i', '', $xml);
            $xml = preg_replace(
                '/[\x00-\x08\x0B\x0C\x0E-\x1F]/',
                '',
                $xml
            );

            libxml_use_internal_errors(true);

            $xmlObj = simplexml_load_string($xml);

            if ($xmlObj === false) {

                $errors = [];

                foreach (libxml_get_errors() as $error) {
                    $errors[] = trim($error->message);
                }

                return back()->with(
                    'error',
                    'XML Parse Error: ' . implode(', ', $errors)
                );
            }

            $vouchers = [];

            $nodes = $xmlObj->xpath(
                "//*[local-name()='VOUCHER']"
            );

            if ($nodes) {

                foreach ($nodes as $voucher) {

                    $belongsToLedger = false;

                    $particulars = [];

                    $ledgerAmount = 0;

                    if (isset($voucher->{'ALLLEDGERENTRIES.LIST'})) {

                        foreach ($voucher->{'ALLLEDGERENTRIES.LIST'} as $entry) {

                            $entryLedger = trim((string)($entry->LEDGERNAME ?? ''));

                            $amount = (float)($entry->AMOUNT ?? 0);

                            if (strcasecmp($entryLedger, $ledger) === 0) {

                                $belongsToLedger = true;

                                // ADD instead of overwrite
                                $ledgerAmount += $amount;

                            } else {

                                if (!empty($entryLedger)) {
                                    $particulars[] = $entryLedger;
                                }
                            }
                        }
                    }

                                    if (!$belongsToLedger) {
                        continue;
                    }

                                        $debit = 0;
                    $credit = 0;

                    if ($ledgerAmount < 0) {

                        $debit = abs($ledgerAmount);

                    } elseif ($ledgerAmount > 0) {

                        $credit = abs($ledgerAmount);
                    }

                    $voucherType = trim((string)($voucher->VOUCHERTYPENAME ?? ''));

                    $vouchers[] = [

                        'date' => (string)($voucher->DATE ?? ''),

                        'particulars' => !empty($particulars)
                            ? implode(', ', array_unique($particulars))
                            : (string)($voucher->NARRATION ?? ''),

                        'voucher_type' => $voucherType,

                        'mapped_type' => trim(
                            $voucherMappings[$voucherType] ?? 'Others'
                        ),

                        'voucher_number' => (string)($voucher->VOUCHERNUMBER ?? ''),

                        'debit' => $debit,

                        'credit' => $credit,

                        'master_id' => (string)($voucher->MASTERID ?? ''),
                    ];
                }
            }

            // Opening / Closing Balance
            $balanceXml = $this->tally->getLedgerDetails(
                $company,
                $ledger
            );

            $balanceObj = simplexml_load_string($balanceXml);

            $openingBalance = 0;
            $closingBalance = 0;

            $balanceObj = simplexml_load_string($balanceXml);

            if ($balanceObj !== false) {

                if (
                    isset($balanceObj->BODY->DATA->COLLECTION->LEDGER)
                ) {

                    $ledgerData = $balanceObj->BODY->DATA->COLLECTION->LEDGER;

                    $openingBalance = (float) (
                        $ledgerData->OPENINGBALANCE ?? 0
                    );

                    $closingBalance = (float) (
                        $ledgerData->CLOSINGBALANCE ?? 0
                    );
                }
            }

            usort($vouchers, function ($a, $b) {
                return strcmp(
                    $b['date'],
                    $a['date']
                );
            });

            // Summary

            $salesVouchers = collect($vouchers)
                ->filter(function ($item) {
                    return strtolower(trim($item['mapped_type'])) === 'sales';
                })
                ->sortByDesc('date')
                ->values()
                ->toArray();

            $receiptVouchers = collect($vouchers)
                ->filter(function ($item) {
                    return strtolower(trim($item['mapped_type'])) === 'receipt';
                })
                ->sortByDesc('date')
                ->values()
                ->toArray();

            $journalVouchers = collect($vouchers)
                ->filter(function ($item) {

                    return !in_array(
                        strtolower(trim($item['mapped_type'])),
                        [
                            'sales',
                            'receipt',
                            'purchase',
                            'payment'
                        ]
                    );

                })
                ->sortByDesc('date')
                ->values()
                ->toArray();

            $summary = [
                'sale'     => collect($vouchers)->sum('debit'),
                'receipts' => collect($vouchers)->sum('credit'),
            ];

          if ($under === "Sundry Debtors") {

            // Sales Tab - sirf Sales vouchers, debit side
            $primaryVouchers = collect($vouchers)
                ->filter(fn($item) =>
                    strtolower(trim($item['mapped_type'])) === 'sales'
                    && ($item['debit'] ?? 0) > 0
                )
                ->sortByDesc('date')
                ->values()
                ->toArray();

            // Receipts Tab - saare Credit entries (koi bhi voucher type)
            $secondaryVouchers = collect($vouchers)
                ->filter(fn($item) => ($item['credit'] ?? 0) > 0)
                ->sortByDesc('date')
                ->values()
                ->toArray();

            // Others Tab - saare Debit entries jo Sales nahi hain
            $journalVouchers = collect($vouchers)
                ->filter(fn($item) =>
                    strtolower(trim($item['mapped_type'])) !== 'sales'
                    && ($item['debit'] ?? 0) > 0
                )
                ->sortByDesc('date')
                ->values()
                ->toArray();

            // Totals
            $totalSales    = collect($primaryVouchers)->sum('debit');
            $totalOthers   = collect($journalVouchers)->sum('debit');
            $totalDebit    = $totalSales + $totalOthers;
            $totalCredit   = collect($secondaryVouchers)->sum('credit');
            $pendingAmount = max(0, $totalDebit - $totalCredit);

            $summary = [
                'sale'     => $totalDebit,
                'receipts' => $totalCredit,
                'pending'  => $pendingAmount,
            ];

            $primaryLabel   = 'Total Debit';
            $secondaryLabel = 'Total Credit';

        } elseif ($under === "Sundry Creditors") {

            // Purchase Tab - sirf Purchase vouchers, credit side
            $primaryVouchers = collect($vouchers)
                ->filter(fn($item) =>
                    strtolower(trim($item['mapped_type'])) === 'purchase'
                    && ($item['credit'] ?? 0) > 0
                )
                ->sortByDesc('date')
                ->values()
                ->toArray();

            // Payments Tab - saare Debit entries (koi bhi voucher type)
            $secondaryVouchers = collect($vouchers)
                ->filter(fn($item) => ($item['debit'] ?? 0) > 0)
                ->sortByDesc('date')
                ->values()
                ->toArray();

            // Others Tab - saare Credit entries jo Purchase nahi hain
            $journalVouchers = collect($vouchers)
                ->filter(fn($item) =>
                    strtolower(trim($item['mapped_type'])) !== 'purchase'
                    && ($item['credit'] ?? 0) > 0
                )
                ->sortByDesc('date')
                ->values()
                ->toArray();

            // Totals
            $totalPurchase = collect($primaryVouchers)->sum('credit');
            $totalOthers   = collect($journalVouchers)->sum('credit');
            $totalCredit   = $totalPurchase + $totalOthers;
            $totalDebit    = collect($secondaryVouchers)->sum('debit');
            $pendingAmount = max(0, $totalCredit - $totalDebit);

            $summary = [
                'sale'     => $totalCredit,
                'receipts' => $totalDebit,
                'pending'  => $pendingAmount,
            ];

            $primaryLabel   = 'Total Credit';
            $secondaryLabel = 'Total Debit';

        } else {

            $primaryVouchers   = [];
            $secondaryVouchers = [];
            $journalVouchers   = [];
            $primaryLabel      = 'Primary';
            $secondaryLabel    = 'Secondary';

            $summary = [
                'sale'     => 0,
                'receipts' => 0,
                'pending'  => 0,
            ];
        }
 

            return view(
                'owner.tally.ledger-vouchers',
                compact(
                    'company',
                    'ledger',
                    'vouchers',
                    'summary',
                    'openingBalance',
                    'closingBalance',
                    'salesVouchers',
                    'receiptVouchers',
                    'journalVouchers',
                    'under',
                    'primaryVouchers',
                    'secondaryVouchers',
                    'primaryLabel',
                    'secondaryLabel'
                )
            );

        } catch (\Throwable $e) {

            \Log::error(
                'Ledger Voucher Error',
                [
                    'message' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile(),
                ]
            );

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }





    public function voucherMappings($company)
    {
        $xml = $this->tally->getVoucherTypes($company);

        $xmlObj = simplexml_load_string($xml);

        $voucherTypes = [];

        if ($xmlObj !== false) {

            $nodes = $xmlObj->xpath("//*[local-name()='VOUCHERTYPE']");

            if (!empty($nodes)) {

                foreach ($nodes as $node) {

                    $name = '';

                    if (isset($node->NAME)) {
                        $name = (string) $node->NAME;
                    }

                    if (empty($name) && isset($node['NAME'])) {
                        $name = (string) $node['NAME'];
                    }

                    if (!empty($name)) {
                        $voucherTypes[] = [
                            'name' => $name
                        ];
                    }
                }
            }
        }

        $savedMappings = VoucherMapping::pluck('mapped_to', 'voucher_type')->toArray();

        return view(
            'owner.tally.voucher-mappings',
            compact('voucherTypes', 'savedMappings', 'company')
        );
    }


    public function saveVoucherMappings(Request $request)
    {
        $voucherTypes = array_keys($request->mapping);

        VoucherMapping::where('company', $request->company)
            ->whereNotIn('voucher_type', $voucherTypes)
            ->delete();

        foreach ($request->mapping as $voucherType => $mappedTo) {

            VoucherMapping::updateOrCreate(
                [
                    'company'      => $request->company,
                    'voucher_type' => $voucherType
                ],
                [
                    'mapped_to' => $mappedTo
                ]
            );
        }

        return back()->with('success', 'Voucher Mapping Saved Successfully.');
    }
}
