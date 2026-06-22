@include('owner.components.header')
<div id="main-wrapper">
    <div class="nav-header">
        <a href="#" class="brand-logo">
            <svg width="120" height="50" viewBox="0 0 120 50" xmlns="http://www.w3.org/2000/svg">
                <text x="55" y="32"
                    font-size="22"
                    font-family="Arial, sans-serif"
                    font-weight="bold"
                    fill="#4E3F6B">
                    RMS
                </text>
            </svg>
        </a>
        <div class="nav-control">
            <div class="hamburger">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
            </div>
        </div>
    </div>

    @include('owner.components.navbar')
    @include('owner.components.sidebar')

    <div class="content-body default-height">
        <div class="container-fluid">

            <div class="row">

                <div class="col-12">

                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title">Ledger Vouchers</h4>
                            <span class="badge bg-primary">
                              {{  $under }}
                            </span>
                        </div>

                        <div class="card-body">
                            <div class="row g-3 mb-4">

                                   @php

$firstCardTitle = $primaryLabel;
$secondCardTitle = $secondaryLabel;

$cards = [
    [
        'title' => $firstCardTitle,
        'value' => $summary['sale'] ?? 0,
        'bg'    => 'warning'
    ],
    [
        'title' => $secondCardTitle,
        'value' => $summary['receipts'] ?? 0,
        'bg'    => 'info'
    ],
    [
        'title' => 'Opening Balance',
        'value' => abs($openingBalance ?? 0),
        'bg'    => 'primary'
    ],
    [
        'title' => 'Closing Balance',
        'value' => abs($closingBalance ?? 0),
        'bg'    => 'success'
    ],
];

@endphp
                                    @foreach($cards as $card)
                                        <div class="col-xl-3 col-lg-4 col-md-6">
                                            <div class="card border-0 shadow-sm h-100">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h6 class="text-muted mb-2">
                                                                {{ $card['title'] }}
                                                            </h6>
                                                            <h4 class="mb-0 fw-bold">
                                                                ₹ {{ number_format($card['value'],2) }}
                                                            </h4>
                                                        </div>
                                                        <div
                                                            class="rounded-circle bg-{{ $card['bg'] }}"
                                                            style="
                                                                width:60px;
                                                                height:60px;
                                                                display:flex;
                                                                align-items:center;
                                                                justify-content:center;
                                                                color:#fff;
                                                                font-size:22px;
                                                                font-weight:bold;">
                                                            ₹
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                    
                                    <ul class="nav nav-pills mb-4" id="voucherTabs">

    <li class="nav-item me-2">
        <button class="nav-link active"
            data-bs-toggle="pill"
            data-bs-target="#sales-tab">

            {{ $primaryLabel }}
            ({{ count($primaryVouchers) }})

        </button>
    </li>

    <li class="nav-item me-2">
        <button class="nav-link"
            data-bs-toggle="pill"
            data-bs-target="#receipt-tab">

            {{ $secondaryLabel }}
            ({{ count($secondaryVouchers) }})

        </button>
    </li>

    {{-- Others Tab --}}
    <li class="nav-item me-2">
        <button class="nav-link"
            data-bs-toggle="pill"
            data-bs-target="#others-tab">

            Others ({{ count($journalVouchers) }})

        </button>
    </li>

</ul>
                                </div>
                                <style>
                                    .card {
                                        border-radius: 12px;
                                        transition: all .3s ease;
                                    }
                                    .card:hover {
                                        transform: translateY(-4px);
                                        box-shadow: 0 10px 25px rgba(0,0,0,.12);
                                    }
                                    .card h4 {
                                        font-size: 22px;
                                    }
                                    .card h6 {
                                        font-size: 14px;
                                        text-transform: uppercase;
                                    }
                                </style>
                               <div class="tab-content">

                                {{-- Sales Tab --}}
                                <div class="tab-pane fade show active" id="sales-tab">

                                    @php $voucherList = $primaryVouchers; @endphp

                                    <div class="table-responsive">
                                        <table id="example11" class="display">
                                            <thead>
                                                <tr>
                                                    <th>Sr.No</th>
                                                    <th>Date</th>
                                                    <th>Particulars</th>
                                                    <th>Vch No</th>
                                                    <th class="text-end">Debit</th>
                                                 
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($voucherList as $index => $voucher)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>

                                                        <td>
                                                            {{ !empty($voucher['date'])
                                                                ? \Carbon\Carbon::parse($voucher['date'])->format('d M Y')
                                                                : '-' }}
                                                        </td>

                                                        <td>{{ $voucher['particulars'] ?? '-' }}</td>

                                                        <td>{{ $voucher['voucher_number'] ?? '-' }}</td>

                                                        <td class="text-end">
                                                            ₹ {{ number_format($voucher['debit'] ?? 0, 2) }}
                                                        </td>

                                                         
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center">
                                                            No Sales Vouchers Found
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                                {{-- Receipt Tab --}}
                                <div class="tab-pane fade" id="receipt-tab">

                                    @php $voucherList = $secondaryVouchers; @endphp

                                    <div class="table-responsive">
                                        <table id="example12" class="display">
                                            <thead>
                                                <tr>
                                                    <th>Sr.No</th>
                                                    <th>Date</th>
                                                    <th>Particulars</th>
                                                    <th class="text-end">Credit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($voucherList as $index => $voucher)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>

                                                        <td>
                                                            {{ !empty($voucher['date'])
                                                                ? \Carbon\Carbon::parse($voucher['date'])->format('d M Y')
                                                                : '-' }}
                                                        </td>

                                                        <td>{{ $voucher['particulars'] ?? '-' }}</td>


                                                        <td class="text-end">
                                                            ₹ {{ number_format($voucher['credit'] ?? 0, 2) }}
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center">
                                                            No Receipt Vouchers Found
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                                {{-- Others Tab --}}
                                <div class="tab-pane fade" id="others-tab">

                                    @php $voucherList = $journalVouchers; @endphp

                                    <div class="table-responsive">
                                        <table id="example13" class="display">
                                            <thead>
                                                <tr>
                                                    <th>Sr.No</th>
                                                    <th>Date</th>
                                                    <th>Particulars</th>
                                                    <th>Voucher Type</th>
                                                    <th>Vch No</th>
                                                    <th class="text-end">Debit</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($voucherList as $index => $voucher)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>

                                                        <td>
                                                            {{ !empty($voucher['date'])
                                                                ? \Carbon\Carbon::parse($voucher['date'])->format('d M Y')
                                                                : '-' }}
                                                        </td>

                                                        <td>{{ $voucher['particulars'] ?? '-' }}</td>

                                                        <td>{{ $voucher['voucher_type'] ?? '-' }}</td>

                                                        <td>{{ $voucher['voucher_number'] ?? '-' }}</td>

                                                        <td class="text-end">
                                                            ₹ {{ number_format($voucher['debit'] ?? 0, 2) }}
                                                        </td>

                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center">
                                                            No Other Vouchers Found
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>
@include('owner.components.footer')