<header class="topbar" data-navbarbg="skin6">
    <nav class="navbar top-navbar navbar-expand-md navbar-light">
        <div class="navbar-header" data-logobg="skin6">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <b class="logo-icon">
                    <img src="/assets/logo.png" alt="homepage" class="dark-logo" style="height: 30px; 30px" />
                </b>
                <span class="logo-text">
                    <h3 class="m-0 font-bold">WorkNote</h3>
                </span>
            </a>
            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i
                    class="mdi mdi-menu"></i></a>
        </div>
        <div class="navbar-collapse collapse" id="navbarSupportedContent" data-navbarbg="skin5">
            <ul class="navbar-nav float-start me-auto">
                <li class="nav-item search-box">
                    <a class="nav-link waves-effect waves-dark">
                        <i class="mdi mdi-magnify me-1"></i>
                        <span class="font-16">Search</span>
                    </a>
                    <form class="app-search position-absolute" method="GET" action="/search">
                        <input type="text" class="form-control" placeholder="Search &amp; enter" name="keyword">
                        <a class="srh-btn"><i class="mdi mdi-window-close"></i></a>
                    </form>
                </li>
            </ul>
            <ul class="navbar-nav float-end">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="#"
                        id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="/img/{{ session()->get('user')['image'] }}" alt="user" class="rounded-circle"
                            width="31">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end user-dd animated" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="javascript:void(0)"><i class="ti-user m-r-5 m-l-5"></i>
                            Ganti Foto Profil
                        </a>
                        <a class="dropdown-item" href="logout">
                            <i class="ti-wallet m-r-5 m-l-5"></i>
                            Logout</a>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
