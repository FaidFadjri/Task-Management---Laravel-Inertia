@extends('index')

@section('content')
    <div class="page-wrapper">
        <div class="container-fluid">
            <h3 class="card-title text-secondary font-bold">Welcome Mr. {{ $user['full_name'] }}</h3>
            <p class="card-subtitle mb-4">Manage project faster and easier with worknote</p>

            <div class="row">
                @if ($pagination)
                    <?php $data = $projects['data']; ?>
                @else
                    <?php $data = $projects; ?>
                @endif

                @foreach ($data as $item)
                    <div class="col-md-6 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                {{ $user['division'] . ' at ' . $user['company'] }}
                            </div>
                            <div class="card-body">
                                <div class="d-flex mb-3 gap-2">
                                    @if ($item['progress'] == 'On Progress')
                                        <a href="#"
                                            class="btn text-white font-bold btn-primary">{{ strtoupper($item['progress']) }}</a>
                                    @elseif($item['progress'] == 'To Do')
                                        <a href="#"
                                            class="btn text-white font-bold btn-warning">{{ strtoupper($item['progress']) }}</a>
                                    @elseif($item['progress'] == 'Pending' or $item['progress'] == 'Stuck')
                                        <a href="#"
                                            class="btn text-white font-bold btn-danger">{{ strtoupper($item['progress']) }}</a>
                                    @else
                                        <a href="#"
                                            class="btn text-white font-bold btn-success">{{ strtoupper($item['progress']) }}</a>
                                    @endif

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

                                <h5 class="card-title font-bold">{{ $item['title'] }}</h5>
                                <p class="card-subtitle">{{ $item['description'] }}</p>
                                <div class="mb-1">
                                    <label for="estimasi" class="font-bold fst-italic">Estimasi Biaya</label>
                                    <input type="text" class="form-control mb-2 rounded-md"
                                        value="{{ number_format($item['estimation_cost'], 2, ',', '.') }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        @if ($pagination)
                            @foreach ($projects['links'] as $item)
                                @if ($item['url'])
                                    <li class="page-item">
                                        <a class="page-link"
                                            href="{{ $item['url'] }}">{{ str_replace('&laquo;', '', str_replace('&raquo;', '', $item['label'])) }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    </ul>
                </nav>

            </div>
        </div>
    </div>
@endsection
