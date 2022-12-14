@extends('index')

@section('content')
    <div class="page-wrapper">
        <div class="container-fluid">
            @if (Session::has('pesan'))
                <div class="row">
                    <div class="col-12">
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ Session::get('pesan') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>
                </div>
            @endif
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-md-flex justify-content-between">
                                <div>
                                    <h4 class="card-title d-flex justify-content-between">
                                        Sales Summary
                                    </h4>
                                    <h6 class="card-subtitle">Ample admin Vs Pixel admin</h6>
                                </div>

                                @if (session()->get('user')['status'] == 'admin')
                                    <div class="d-flex gap-2">
                                        <select
                                            class="form-control {{ session()->get('user')['status'] == 'member' ? 'd-none' : '' }}"
                                            style="width: fit-content; height: fit-content;" id="filter_member">
                                            @if ($user_id == null)
                                                <option value="all" selected>All People</option>
                                            @else
                                                <option value="all">Semua</option>
                                            @endif
                                            @foreach ($member as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ $user_id == $user->id ? 'selected' : '' }}>{{ $user->full_name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <select name="company" id="company" class="form-control"
                                            style="width: fit-content; height: fit-content">
                                            <option value="">All Company</option>
                                            <option value="AKASTRA">Akastra</option>
                                            <option value="KSC">KSC</option>
                                        </select>
                                    </div>
                                @endif
                            </div>
                            <div id="chartdiv"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body overflow-auto" style="height: 500px">
                            <h4 class="card-title">User Performance</h4>
                            <h6 class="card-subtitle mb-4">Average completed task</h6>
                            @foreach ($performance as $item)
                                <div class="pb-3 d-flex align-items-center">
                                    <img src="/img/{{ $item['image'] }}" alt="foto" style="height: 50px"
                                        class="rounded-circle">
                                    <div class="ms-3">
                                        <h5 class="mb-0 fw-bold">{{ $item['name'] }}</h5>
                                        <span class="text-muted fs-6">{{ $item['task_complete'] }} Completed Task</span>
                                    </div>
                                    <div class="ms-auto">
                                        <span class="badge bg-light text-muted">
                                            @if (session()->get('user')['status'] == 'admin')
                                                @if ($item['task_complete'])
                                                    {{ round((intval($item['task_complete']) / intval($max_performance['task_complete'])) * 100, 2) }}
                                                    %
                                                @endif
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>


            {{-- Recent Activities areas --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-md-flex">
                                <div>
                                    <h4 class="card-title">Recent Activity</h4>
                                    <h5 class="card-subtitle">Monitoring aktivitas pengguna dari login, menambahkan project
                                        hingga logout</h5>
                                </div>
                                <div class="ms-auto">
                                    <p>{{ date('d F Y') }}</p>
                                </div>
                            </div>
                            <div class="table-responsive">
                                @if ($recent_activities)
                                    <table class="table mb-0 table-hover align-middle text-nowrap">
                                        <thead>
                                            <tr>
                                                <th class="border-top-0">Pengguna</th>
                                                <th class="border-top-0">Aktivitas</th>
                                                <th class="border-top-0">Tanggal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($recent_activities as $item)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <div class="m-r-10">
                                                                <img src="/img/{{ $item['image'] }}" alt="thumbnail"
                                                                    class="thumbnail rounded-circle" style="height: 50px">
                                                            </div>
                                                            <div class="">
                                                                <h4 class="m-0 font-16">{{ $item['full_name'] }}</h4>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $item['activity'] }}</td>
                                                    <td>{{ date('d F Y', strtotime($item['created_at'])) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="container">
                                        <p class="font-bold">belum ada data aktivitas</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- recent projects area --}}
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Recent Projects</h4>
                            <p class="card-subtitle">monitoring project terbaru </p>
                        </div>
                        <div class="comment-widgets scrollable cursor-pointer">
                            @if ($recent_projects)
                                @foreach ($recent_projects as $item)
                                    <div class="d-flex flex-row comment-row m-t-0">
                                        <div class="p-2"><img src="/img/{{ $item['image'] }}" alt="user"
                                                width="50" class="rounded-circle"></div>
                                        <div class="d-flex flex-column">
                                            <div class="comment-text w-100 d-flex flex-column">
                                                <h6 class="font-medium font-bold">:: {{ $item['full_name'] }}</h6>
                                                <span class="m-0 d-block font-bold">{{ $item['project'] }}</span>
                                                <span>{{ $item['description'] }}</span>
                                            </div>
                                            <div class="d-flex justify-content-start gap-2 mt-2 ms-2"
                                                style="align-items: center;">
                                                @if ($item['progress'] == 'TODO')
                                                    @php
                                                        $classes = 'bg-info';
                                                    @endphp
                                                @elseif($item['progress'] == 'WORKING ON IT')
                                                    @php
                                                        $classes = 'bg-warning';
                                                    @endphp
                                                @elseif($item['progress'] == 'COMPLETE')
                                                    @php
                                                        $classes = 'bg-success';
                                                    @endphp
                                                @else
                                                    @php
                                                        $classes = 'bg-danger';
                                                    @endphp
                                                @endif

                                                <p
                                                    class="m-0 {{ $classes }} px-3 fs-6 rounded-pill font-bold text-white">
                                                    {{ $item['progress'] }}
                                                </p>
                                                <p class="m-0 font-medium text-secondary">Due date :
                                                    {{ $item['due_date'] }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="container">
                                    <p class="font-bold">belum ada data projects yang tersedia </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Last Interact</h4>
                            <div class="card-subtitle">
                                <small>Terakhir user melakukan interaksi dengan aplikasi</small>
                            </div>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">user</th>
                                        <th scope="col">last interact date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($last_interact as $index => $item)
                                        <tr>
                                            <th scope="row">{{ $index + 1 }}</th>
                                            <td>{{ $item['name'] }}</td>
                                            <td>{{ $item['date'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="footer text-center">
            Thanks for <a href="https://www.wrappixel.com">WrapPixel</a> for make this dashboard templates. I am really
            appreciate it
        </footer>
    </div>


    <style>
        #chartdiv {
            width: 100%;
            height: 500px;
        }
    </style>

@endsection



@section('script')
    <script>
        am5.ready(function() {


            var data = @json($performance_chart);

            // Create root element
            var root = am5.Root.new("chartdiv");
            root.setThemes([
                am5themes_Animated.new(root)
            ]);


            // Create chart
            var chart = root.container.children.push(am5percent.PieChart.new(root, {
                layout: root.verticalLayout,
                innerRadius: am5.percent(50)
            }));


            // Create series
            var series = chart.series.push(am5percent.PieSeries.new(root, {
                valueField: "value",
                categoryField: "category",
                alignLabels: false
            }));

            series.labels.template.setAll({
                textType: "circular",
                centerX: 0,
                centerY: 0,
                maxWidth: 10,
                fontSize: 0.000001
            });

            var sliceTemplate = series.slices.template;



            series.slices.template.events.on("click", function(event) {
                var category = event.target.dataItem.dataContext.category;
                location.href = `/project?progress=${category}`
            })


            sliceTemplate.setAll({
                draggable: false,
                templateField: "settings",
                cornerRadius: 4,
            });


            series.get("colors").set("colors", [
                am5.color("#fc9003"),
                am5.color("#0c8fc7"),
                am5.color("#09b08c"),
                am5.color("#c4c4c4"),
                am5.color("#b81442"),
                am5.color("#fc6603")
            ]);

            series.data.setAll([{
                value: data.todo,
                category: 'TO DO'
            }, {
                value: data.progress,
                category: 'WORKING ON IT',
            }, {
                value: data.complete,
                category: 'COMPLETE'
            }, {
                value: data.stuck,
                category: 'STUCK'
            }, {
                value: data.pending,
                category: 'PENDING'
            }])

            var legend = chart.children.push(am5.Legend.new(root, {
                centerX: am5.percent(50),
                x: am5.percent(50),
                marginTop: 15,
                marginBottom: 15,
            }));

            legend.data.setAll(series.dataItems);
            series.appear(1000, 100);
        });
    </script>


    <script>
        $(document).ready(function() {


            var company = @json($company);
            if (company) {
                $("#company").val(company);
            }


            $('#filter_member').change(function(e) {
                e.preventDefault();
                var member = $(this).val();
                member === 'all' ? (location.href = '/') : (location.href = `/?user_id=${member}`)
            });


            $("#company").change(function(e) {
                e.preventDefault();
                var company = $(this).val();
                company === '' ? (location.href = "/") : (location.href = `/?company=${company}`)
            });
        });
    </script>
@endsection
