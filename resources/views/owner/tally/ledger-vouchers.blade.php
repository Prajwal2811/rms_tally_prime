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

                        <div class="card-header">
                            <div>
                                <h4 class="card-title">
                                    Ledger Vouchers
                                </h4>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row g-3 mb-4">

                                   @php
                                        $cards = [
                                            [
                                                'title' => 'Sale',
                                                'value' => $summary['sale'] ?? 0,
                                                'bg'    => 'warning'
                                            ],
                                            [
                                                'title' => 'Receipts',
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
                                                                font-weight:bold;
                                                            "
                                                        >
                                                            ₹
                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    @endforeach

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
                                    {{-- <div class="table-responsive">
                                        <table id="example" class="display" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="width:5%">Sr.No</th>
                                                    <th style="width:10%">Date</th>
                                                    <th style="width:35%">Particulars</th>
                                                    <th style="width:15%">Vch Type</th>
                                                    <th style="width:10%">Vch No</th>
                                                    <th style="width:12%">Debit</th>
                                                    <th style="width:13%">Credit</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                                @forelse($vouchers as $index => $voucher)

                                                    <tr>

                                                        <td>{{ $index + 1 }}</td>

                                                        <td>
                                                            {{ !empty($voucher['date'])
                                                                ? \Carbon\Carbon::parse($voucher['date'])->format('d M Y')
                                                                : '-' }}
                                                        </td>

                                                        <td style="white-space: normal; word-break: break-word;">
                                                            {{ $voucher['particulars'] ?? '-' }}
                                                        </td>

                                                        <td>
                                                            {{ $voucher['voucher_type'] ?? '-' }}
                                                        </td>

                                                        <td>
                                                            {{ $voucher['voucher_number'] ?? '-' }}
                                                        </td>

                                                        <td class="text-end">
                                                            @if(($voucher['debit'] ?? 0) > 0)
                                                                ₹ {{ number_format($voucher['debit'], 2) }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>

                                                        <td class="text-end">
                                                            @if(($voucher['credit'] ?? 0) > 0)
                                                                ₹ {{ number_format($voucher['credit'], 2) }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>

                                                    </tr>

                                                @empty

                                                    <tr>
                                                        <td colspan="7" class="text-center">
                                                            No vouchers found
                                                        </td>
                                                    </tr>

                                                @endforelse

                                            </tbody>

                                        </table>

                                    </div> --}}

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

@include('owner.components.footer')