@extends('index')

@section('content')
    <div class="page-wrapper">
        <div class="container-fluid">
            <h3 class="card-title text-secondary font-bold">Welcome Mr. {{ $user['full_name'] }}</h3>
            <p class="card-subtitle mb-4">Manage project faster and easier with worknote</p>


            @if (Session::has('pesan'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ Session::get('pesan') }}.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="/project" method="GET">
                @csrf
                <div class="row mb-5">
                    <div class="col-12 text-center mb-2 d-flex justify-content-between">
                        <p class="m-0">Dari Tanggal</p>
                        <p class="m-0">Sampai Tanggal</p>
                    </div>
                    <div class="col-6 mb-2">
                        <input type="date" id="start_date" class="form-control" name="start_date">
                    </div>
                    <div class="col-6 mb-2">
                        <input type="date" id="end_date" class="form-control" name="end_date">
                    </div>
                    <div class="col-md-4 col-sm-6 mb-2">
                        <select name="progress" id="filter_progress" class="form-control">
                            <option value="">Filter Berdasarkan Progress</option>
                            @foreach ($progress_list as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-2">
                        <select name="priority" id="filter_priority" class="form-control">
                            <option value="">Filter Berdasarkan Prioritas</option>
                            <option value="HIGH">HIGH</option>
                            <option value="MEDIUM">MEDIUM</option>
                            <option value="LOW">LOW</option>
                        </select>
                    </div>
                    <div class="col-md-4 col-sm-6 mb-2">
                        <select name="project_type" id="project_type" class="form-control">
                            <option value="">Filter Project Type</option>
                            <option value="project">Project</option>
                            <option value="activity">Aktivitas</option>
                        </select>
                    </div>
                    @if (session()->get('user')['status'] == 'admin')
                        <div class="col-md-6 col-sm-12 mb-2">
                            <select name="company" id="company" class="form-control">
                                <option value="">Select Company</option>
                                <option value="AKASTRA">Akastra</option>
                                <option value="KSC">KSC</option>
                            </select>
                        </div>
                        <div class="col-md-6 col-sm-12 mb-2">
                            <select name="division" id="division" class="form-control">
                                <option value="">Select Division</option>
                                @if (is_array($division) || is_object($division))
                                    @foreach ($division as $item)
                                        <option value="{{ $item->division }}">{{ $item->division }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    @endif
                    <div
                        class="col-12 mt-2 mb-1 d-flex gap-2 justify-content-end flex-sm-column flex-md-row align-items-end">
                        <button type="submit"
                            class="btn btn-success float-end text-white font-bold flex-1 d-flex align-items-center gap-2"
                            style="width: fit-content">
                            <ion-icon name="search"></ion-icon>
                            Filter
                        </button>
                        <a class="btn btn-danger float-end text-white font-bold flex-1 d-flex align-items-center gap-2"
                            style="width: fit-content" href="{{ route('project') }}">
                            <ion-icon name="filter"></ion-icon>
                            Reset
                        </a>
                        <button type="button"
                            class="btn float-end text-white font-bold btn-info d-flex align-items-center add-button gap-2"
                            style="width: fit-content">
                            <ion-icon name="add-circle"></ion-icon>
                            Project
                        </button>
                    </div>
                </div>
            </form>

            @if (Session::has('error'))
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-danger" role="alert">
                            <h4 class="alert-heading">Oops! Mohon maaf</h4>
                            <p>Data yang anda maksud tidak tersedia</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                @foreach ($projects['data'] as $item)
                    <div class="col-12">
                        <div class="card mb-3 rounded-3">
                            <div class="card-header rounded-3 d-flex align-items-center justify-content-between">
                                <p class="m-0 font-bold">{{ strtoupper($item['division']) . ' at ' . $item['company'] }}</p>
                                <div class="d-flex gap-2">
                                    @if ($item['progress'] == 'WORKING ON IT')
                                        @php
                                            $classes = 'btn-info';
                                        @endphp
                                    @elseif($item['progress'] == 'TO DO')
                                        @php
                                            $classes = 'btn-warning';
                                        @endphp
                                    @elseif($item['progress'] == 'PENDING' or $item['progress'] == 'STUCK')
                                        @php
                                            $classes = 'btn-danger';
                                        @endphp
                                    @else
                                        @php
                                            $classes = 'btn-success';
                                        @endphp
                                    @endif
                                    <a href="#"
                                        class="btn text-white font-bold {{ $classes }}">{{ $item['progress'] }}</a>


                                    @if ($item['priority'] == 'HIGH')
                                        <a href="#"
                                            class="btn text-white font-bold btn-success">{{ $item['priority'] }}</a>
                                    @elseif($item['priority'] == 'MEDIUM')
                                        <a href="#"
                                            class="btn text-white font-bold btn-warning">{{ $item['priority'] }}</a>
                                    @else
                                        <a href="#"
                                            class="btn text-white font-bold btn-warning">{{ $item['priority'] }}</a>
                                    @endif
                                </div>
                            </div>
                            <div class="row g-0 justify-content-center align-items-center">
                                @if ($item['image'])
                                    <div class="col-md-4">
                                        <img src="/assets/thumbnail/{{ $item['image'] }}" class="img-fluid rounded-5"
                                            alt="card" style="object-fit: cover; width: 100%">
                                    </div>
                                @endif
                                <div class="{{ $item['image'] ? 'col-md-8' : 'col-md-12' }}">
                                    <div class="card-body">
                                        <h5 class="card-title font-bold">{{ $item['project'] }}</h5>
                                        <p class="card-text">{{ substr($item['description'], 0, 200) . '...' }}</p>
                                        <p class="card-text"><small class="text-muted">
                                                Last updated at {{ date('d F Y', strtotime($item['updated_at'])) }}</small>
                                        </p>
                                        <p class="font-bold font-secondary m-0 mb-2">Estimasi Biaya</p>
                                        <input type="text" class="form-control mb-3" readonly
                                            value="{{ $item['estimation_cost'] }}">
                                        <div class="d-flex align-items-center justify-content-end">
                                            <button
                                                class="btn btn-info text-white detail-button font-bold d-flex align-items-center gap-2"
                                                data-id="{{ $item['project_id'] }}">
                                                <ion-icon name="search"></ion-icon>
                                                Lihat Detail
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        @if ($projects['data'])
                            @foreach ($projects['links'] as $item)
                                @if ($item['url'])
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $item['url'] . $request_url }}">
                                            {{ str_replace('&laquo;', '', str_replace('&raquo;', '', $item['label'])) }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    </ul>
                </nav>

            </div>
        </div>
    </div>

    @include('components.modal')
@endsection


@section('script')
    <script>
        $(document).ready(function() {
            var params = @json($params);
            console.log(params);
            const field = ['company', 'division', 'end_date', 'start_date', 'progress', 'project_type', 'priority'];
            const element = ['company', 'division', 'end_date', 'start_date', 'filter_progress', 'project_type',
                'filter_priority'
            ];
            field.forEach((param, index) => {
                if (params[param]) {
                    $(`#${element[index]}`).val(params[param]);
                }
            });
        });



        $(document).ready(() => {
            $('#thumbnail-image').change(function() {
                const file = this.files[0];
                console.log(file);
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $('#imgPreview').attr('src', event.target.result);
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endsection
