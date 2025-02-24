<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ url('/') }}">
                <span class="logo-name">Warung Tani</span>
            </a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Main</li>

            <li class="dropdown {{ request()->is('/') ? 'active' : '' }}">
                <a href="{{ url('/') }}" class="nav-link">
                    <i data-feather="monitor"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            @if (auth()->user()->role->name == 'admin')
                <li class="dropdown {{ request()->is('transactions*') ? 'active' : '' }}">
                    <a href="#" class="menu-toggle nav-link has-dropdown">
                        <i data-feather="briefcase"></i>
                        <span>Transactions</span>
                    </a>
                    <ul class="dropdown-menu" style="border: none!important">
                        <li class="{{ request()->is('transactions') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('transactions.index') }}">Transaction List</a>
                        </li>
                    </ul>
                </li>

                <li class="dropdown {{ request()->is('history*') ? 'active' : '' }}">
                    <a href="#" class="menu-toggle nav-link has-dropdown">
                        <i data-feather="file-text"></i>
                        <span>Histories</span>
                    </a>
                    <ul class="dropdown-menu" style="border: none!important">
                        <li class="{{ request()->is('history') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('history.index') }}">History List</a>
                        </li>
                    </ul>
                </li>

                <li class="dropdown {{ request()->is('customers*') ? 'active' : '' }}">
                    <a href="#" class="menu-toggle nav-link has-dropdown">
                        <i data-feather="users"></i>
                        <span>Customers</span>
                    </a>
                    <ul class="dropdown-menu" style="border: none!important">
                        <li class="{{ request()->is('customers') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('customers.index') }}">Customer List</a>
                        </li>
                    </ul>
                </li>
            @endif

            @if (auth()->user()->role->name == 'user')
                <li class="dropdown {{ request()->is('transactions-owner*') ? 'active' : '' }}">
                    <a href="#" class="menu-toggle nav-link has-dropdown">
                        <i data-feather="briefcase"></i>
                        <span>Transactions</span>
                    </a>
                    <ul class="dropdown-menu" style="border: none!important">
                        <li class="{{ request()->is('transactions-owner') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('transactions-owner.index') }}">Transaction List</a>
                        </li>
                    </ul>
                </li>

                <li class="dropdown {{ request()->is('history-owner*') ? 'active' : '' }}">
                    <a href="#" class="menu-toggle nav-link has-dropdown">
                        <i data-feather="file-text"></i>
                        <span>Histories</span>
                    </a>
                    <ul class="dropdown-menu" style="border: none!important">
                        <li class="{{ request()->is('history-owner') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('history-owner.index') }}">History List</a>
                        </li>
                    </ul>
                </li>

                <li class="dropdown {{ request()->is('products*') ? 'active' : '' }}">
                    <a href="#" class="menu-toggle nav-link has-dropdown">
                        <i data-feather="database"></i>
                        <span>Products</span>
                    </a>
                    <ul class="dropdown-menu" style="border: none!important">
                        <li class="{{ request()->is('products') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('products.index') }}">Product List</a>
                        </li>
                    </ul>
                </li>

                <li class="dropdown {{ request()->is('add-users*') ? 'active' : '' }}">
                    <a href="#" class="menu-toggle nav-link has-dropdown">
                        <i data-feather="users"></i>
                        <span>Users</span>
                    </a>
                    <ul class="dropdown-menu" style="border: none!important">
                        <li class="{{ request()->is('add-users') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('add-users.index') }}">User List</a>
                        </li>
                    </ul>
                </li>
            @endif
        </ul>
    </aside>
</div>
