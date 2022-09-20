<aside class="left-sidebar" data-sidebarbg="skin6">
    <div class="scroll-sidebar">
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                        href="{{ route('dashboard') }}" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span
                            class="hide-menu">Dashboard</span></a></li>

                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link"
                        href="{{ route('project') }}" aria-expanded="false">
                        <i class="mdi mdi-border-all"></i>
                        <span class="hide-menu">Projects</span>
                    </a>
                </li>
                @if (session()->get('user')['status'] == 'admin')
                    <li class="sidebar-item">
                        <a class="sidebar-link waves-effect waves-dark sidebar-link" href="pages-profile.html"
                            aria-expanded="false">
                            <i class="mdi mdi-account-network"></i>
                            <span class="hide-menu">User Management</span>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
