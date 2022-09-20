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
                        <a class="dropdown-item edit-profile" id="edit-profile-button"><i
                                class="ti-user m-r-5 m-l-5"></i>
                            Edit Account
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


<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/project/save_user" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="username">Username</label>
                        <input type="text" id="edit_username" class="form-control" name="user[username]">
                    </div>
                    <div class="mb-2">
                        <label for="fullname">Full Name</label>
                        <input type="text" id="edit_full_name" class="form-control" name="user[full_name]">
                    </div>
                    <div class="mb-2">
                        <label for="email">Email</label>
                        <input type="text" id="edit_email" class="form-control" name="user[email]">
                    </div>
                    <div class="mb-2">
                        <label for="password">Password</label>
                        <input type="password" id="edit_password" class="form-control" name="user[password]">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

<script>
    $('#edit-profile-button').click(function(e) {
        e.preventDefault();
        axios.get('/project/account').then(function(response) {
            const user = response.data;
            setAccount(user)
        })
        $("#editModal").modal('show');
    });

    const setAccount = (user) => {
        const field = ['username', 'full_name', 'email', 'password'];
        field.forEach(element => {
            $(`#edit_${element}`).val(user[element]);
        });
    }
</script>
