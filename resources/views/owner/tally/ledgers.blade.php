	@include('owner.components.header')
    <div id="main-wrapper">
		<div class="nav-header">
            <a href="#" class="brand-logo">
				<svg width="120" height="50" viewBox="0 0 120 50" xmlns="http://www.w3.org/2000/svg">

					 

					<!-- RMS Text -->
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
                <!-- row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="col-10 mx-auto mt-4">
                                @if (session('success'))
                                    <div  id="successAlert" class="alert alert-success alert-dismissible fade show text-center" role="alert">
                                        <button class="btn-close" data-bs-dismiss="alert"></button> 
                                         {{ session('success') }}
                                    </div>
                                @endif
                            </div>
                            <script>
                                setTimeout(function () {
                                    let alert = document.getElementById('successAlert');
                                    if (alert) {
                                        let bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                                        bsAlert.close();
                                    }
                                }, 3000); // 3 seconds
                            </script>
                            <div class="card-header d-flex justify-content-between">
                                <h4 class="card-title">Ledger List</h4>
                                <span class="badge bg-primary">
                                    {{ count($ledgers) }}
                                </span>
                            </div>
                            
                            

                           <div class="card-body">

                            <ul class="nav nav-pills mb-4" id="ledgerTabs" role="tablist">

                                <li class="nav-item me-2">
                                    <button class="nav-link active"
                                        data-bs-toggle="pill"
                                        data-bs-target="#debtors-tab">
                                        Sundry Debtors
                                        ({{ collect($ledgers)->where('under', 'Sundry Debtors')->count() }})
                                    </button>
                                </li>

                                <li class="nav-item me-2">
                                    <button class="nav-link"
                                        data-bs-toggle="pill"
                                        data-bs-target="#creditors-tab">
                                        Sundry Creditors
                                        ({{ collect($ledgers)->where('under', 'Sundry Creditors')->count() }})
                                    </button>
                                </li>

                            </ul>

                            <div class="tab-content">

                                <!-- Debtors -->
                                <div class="tab-pane fade show active" id="debtors-tab">

                                    <div class="table-responsive">
                                         <table id="example11" class="display" style="min-width: 845px">
                                            <thead>
                                                <tr>
                                                    <th>Sr.No</th>
                                                    <th>Ledger Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                                @php $i = 1; @endphp

                                                @forelse($ledgers as $ledger)

                                                    @if(($ledger['under'] ?? '') == 'Sundry Debtors')

                                                        <tr>
                                                            <td>{{ $i++ }}</td>

                                                            <td>{{ $ledger['name'] }}</td>

                                                            <td>
                                                                <a href="{{ route(
                                                                    'owner.tally.ledger.vouchers',
                                                                    [
                                                                        'company' => urlencode($company),
                                                                        'ledger'  => urlencode($ledger['name'])
                                                                    ]
                                                                ) }}"
                                                                class="btn btn-primary btn-sm">
                                                                    View Vouchers
                                                                </a>
                                                            </td>
                                                        </tr>

                                                    @endif

                                                @empty

                                                    <tr>
                                                        <td colspan="3" class="text-center">
                                                            No Debtors Found
                                                        </td>
                                                    </tr>

                                                @endforelse

                                            </tbody>
                                        </table>
                                    </div>

                                </div>

                                <!-- Creditors -->
                                <div class="tab-pane fade" id="creditors-tab">

                                    <div class="table-responsive">
                                         <table id="example12" class="display" style="min-width: 845px">
                                            <thead>
                                                <tr>
                                                    <th>Sr.No</th>
                                                    <th>Ledger Name</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>

                                                @php $j = 1; @endphp

                                                @forelse($ledgers as $ledger)

                                                    @if(($ledger['under'] ?? '') == 'Sundry Creditors')

                                                        <tr>
                                                            <td>{{ $j++ }}</td>

                                                            <td>{{ $ledger['name'] }}</td>

                                                            <td>
                                                                <a href="{{ route(
                                                                    'owner.tally.ledger.vouchers',
                                                                    [
                                                                        'company' => urlencode($company),
                                                                        'ledger'  => urlencode($ledger['name'])
                                                                    ]
                                                                ) }}"
                                                                class="btn btn-primary btn-sm">
                                                                    View Vouchers
                                                                </a>
                                                            </td>
                                                        </tr>

                                                    @endif

                                                @empty

                                                    <tr>
                                                        <td colspan="3" class="text-center">
                                                            No Creditors Found
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

       
		@include('owner.components.footer')