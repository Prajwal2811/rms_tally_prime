<div class="dlabnav">
    <div class="dlabnav-scroll">
        <ul class="metismenu" id="menu">

            <!-- Dashboard -->
            <li>
                <a href="{{ route('owner.tally.dashboard') }}" aria-expanded="false">
                    <i class="fas fa-home"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <!-- Accountants -->
            <li>
                <a href="javascript:void(0);" aria-expanded="false">
                    <i class="fas fa-user-tie"></i>
                    <span class="nav-text">Accountants</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{  route('owner.accountants.index') }}">All Accountants</a></li>
                    <li><a href="{{ route('owner.accountants.create') }}">Add Accountant </a></li>
                </ul>
            </li>


            <!-- Collectors -->
            <li>
                <a href="javascript:void(0);" aria-expanded="false">
                    <i class="fas fa-user-tie"></i>
                    <span class="nav-text">Collectors</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="{{  route('owner.collectors.index') }}">All Collectors</a></li>
                    <li><a href="{{ route('owner.collectors.create') }}">Add Collector </a></li>
                </ul>
            </li>



            {{-- Company --}}
            

         

            {{-- <li>
                <a href="javascript:void(0);" aria-expanded="false">
                    <i class="fas fa-users"></i>
                    <span class="nav-text">Debtors</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="#">All Debtors</a></li>
                    <li><a href="#">Debtor Ledger</a></li>
                    <li><a href="#">ABCD Analysis</a></li>
                </ul>
            </li>

            <!-- Outstanding -->
            <li>
                <a href="javascript:void(0);" aria-expanded="false">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span class="nav-text">Outstanding</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="#">All Outstanding</a></li>
                    <li><a href="#">Ageing Report</a></li>
                    <li><a href="#">Invoice Details</a></li>
                </ul>
            </li>

            <!-- Follow-ups -->
            <li>
                <a href="javascript:void(0);" aria-expanded="false">
                    <i class="fas fa-phone-alt"></i>
                    <span class="nav-text">Follow-ups</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="#">Today Follow-ups</a></li>
                    <li><a href="#">Pending Follow-ups</a></li>
                    <li><a href="#">Follow-up History</a></li>
                </ul>
            </li>

            <!-- Collections -->
            <li>
                <a href="javascript:void(0);" aria-expanded="false">
                    <i class="fas fa-hand-holding-usd"></i>
                    <span class="nav-text">Collections</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="#">Collection Dashboard</a></li>
                    <li><a href="#">Payments</a></li>
                    <li><a href="#">Promise to Pay</a></li>
                </ul>
            </li>

            <!-- Reminders -->
            <li>
                <a href="javascript:void(0);" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    <span class="nav-text">Reminders</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="#">All Reminders</a></li>
                    <li><a href="#">Due Today</a></li>
                    <li><a href="#">Overdue</a></li>
                </ul>
            </li>

            <!-- Reports -->
            <li>
                <a href="javascript:void(0);" aria-expanded="false">
                    <i class="fas fa-chart-bar"></i>
                    <span class="nav-text">Reports</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="#">Outstanding Report</a></li>
                    <li><a href="#">Debtor Ledger Report</a></li>
                    <li><a href="#">Follow-up Report</a></li>
                    <li><a href="#">Collection Report</a></li>
                </ul>
            </li>

            <!-- Users -->
            <li>
                <a href="javascript:void(0);" aria-expanded="false">
                    <i class="fas fa-user-cog"></i>
                    <span class="nav-text">Users</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="#">Staff / Accountant</a></li>
                    <li><a href="#">Collectors</a></li>
                    <li><a href="#">Role Management</a></li>
                </ul>
            </li>

            <!-- Tally Integration -->
            <li>
                <a href="javascript:void(0);" aria-expanded="false">
                    <i class="fas fa-plug"></i>
                    <span class="nav-text">Tally Sync</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="#">Manual Sync</a></li>
                    <li><a href="#">Sync Logs</a></li>
                    <li><a href="#">Data Status</a></li>
                </ul>
            </li>

            <!-- Subscription -->
            <li>
                <a href="javascript:void(0);" aria-expanded="false">
                    <i class="fas fa-credit-card"></i>
                    <span class="nav-text">Subscription</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="#">My Plan</a></li>
                    <li><a href="#">Billing History</a></li>
                    <li><a href="#">Upgrade Plan</a></li>
                </ul>
            </li>

            <!-- Settings -->
            <li>
                <a href="javascript:void(0);" aria-expanded="false">
                    <i class="fas fa-cog"></i>
                    <span class="nav-text">Settings</span>
                </a>
                <ul aria-expanded="false">
                    <li><a href="#">Company Profile</a></li>
                    <li><a href="#">Notifications</a></li>
                    <li><a href="#">System Settings</a></li>
                </ul>
            </li> --}}

        </ul>
    </div>
</div>