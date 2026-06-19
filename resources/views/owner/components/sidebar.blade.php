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

           

        </ul>
    </div>
</div>