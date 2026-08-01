@if (session('login_msg'))
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0" role="alert">
            <i class="bi bi-hand-thumbs-up-fill me-2"></i> {{ session('login_msg') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
@endif
