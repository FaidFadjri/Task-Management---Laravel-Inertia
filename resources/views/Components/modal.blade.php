<script src="/assets/libs/jquery/dist/jquery.min.js"></script>



<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Tambahkan Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/project/add" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="add_title">Nama project atau aktivitas</label>
                        <input type="text" class="form-control" id="add_title" name="project[title]"
                            placeholder="Ketik judul project atau aktivitas" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_due_date">Tenggang waktu</label>
                        <input type="date" class="form-control" id="add_due_date" name="project[due_date]"
                            placeholder="Ketik judul project atau aktivitas" required>
                    </div>
                    <div class="mb-3">
                        <label for="add_progress">Progress</label>
                        <select name="project[progress]" id="add_progress" class="form-control" required>
                            <option value="">Pilih Progress</option>
                            @foreach ($progress_list as $item)
                                <option value="{{ $item }}">{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="add_priority">Priority</label>
                        <select name="project[priority]" id="add_priority" class="form-control" required>
                            <option value="">Pilih Priority</option>
                            <option value="HIGH">HIGH</option>
                            <option value="MEDIUM">MEDIUM</option>
                            <option value="LOW">LOW</option>
                        </select>
                    </div>

                    <label for="">Upload Thumbnail -- Optional</label>
                    <div
                        class="d-flex w-100 align-items-center justify-content-center flex-sm-column flex-md-row gap-2">
                        <img src="https://kliknusae.com/img/404.jpg" alt="thumbnail" class="card-img rounded-2"
                            style="height: 200px; width: 80%; object-fit: contain" id="imgPreview">

                        <div class="mb-3">
                            <label for="formFile" class="form-label">Browse Image</label>
                            <input class="form-control" type="file" id="thumbnail-image"
                                accept="image/png, image/gif, image/jpeg" name="thumbnail">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                        <ion-icon name="save"></ion-icon> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Details Modal --}}
<div class="modal fade" id="detailModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex gap-2 align-items-center">
                    <h5 class="modal-title" id="staticBackdropLabel">Detail Modal</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('update') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 mb-2 overflow-auto h-100 font-bold">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <img src="https://images.unsplash.com/photo-1453728013993-6d66e9c9123a?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8dmlld3xlbnwwfHwwfHw%3D&w=1000&q=80"
                                    alt="logo" class="thumbnail rounded-circle"
                                    style="height: 40px; width: 40px; object-fit:cover" id="image">
                                <input type="text" class="form-control bg-transparent border-0 rounded font-bold"
                                    value="{{ session()->get('user')['full_name'] }}" id="full_name" readonly
                                    disabled>

                            </div>
                            <textarea name="project[description]" id="description" class="form-control mb-3" cols="30" rows="10"
                                placeholder="Ketik Deksripsi"></textarea>


                            <!-- Project id setup -->
                            <input type="hidden" id="project_id" name="project[id]">

                            <div class="mb-2">
                                <label for="progress">Progress</label>
                                <select id="progress" class="form-control" name="project[progress]">
                                    @foreach ($progress_list as $item)
                                        <option value="{{ $item }}">{{ $item }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="priority">Priority</label>
                                <select id="priority" class="form-control" name="project[priority]">
                                    <option value="HIGH">HIGH</option>
                                    <option value="MEDIUM">MEDIUM</option>
                                    <option value="LOW">LOW</option>
                                </select>
                            </div>
                            <div class="accordion mb-3" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                            aria-expanded="true" aria-controls="collapseOne">
                                            Cost Detail
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="mb-2">
                                                <label for="estimation_cost">Estimasi Biaya</label>
                                                <input type="text" class="form-control" id="estimation_cost"
                                                    placeholder="NULL" name="project[estimation_cost]">
                                            </div>
                                            <div class="mb-2">
                                                <label for="actual_cost">Aktual Biaya</label>
                                                <input type="text" class="form-control" id="actual_cost"
                                                    placeholder="NULL" name="project[actual_cost]">
                                            </div>
                                            <div class="mb-2">
                                                <label for="estimation_revenue">Estimasi Revenue</label>
                                                <input type="text" class="form-control" id="estimation_revenue"
                                                    placeholder="NULL" name="project[estimation_revenue]">
                                            </div>
                                            <div class="mb-2">
                                                <label for="actual_revenue">Aktual Revenue</label>
                                                <input type="text" class="form-control" id="actual_revenue"
                                                    placeholder="NULL" name="project[actual_revenue]">
                                            </div>
                                            <div class="mb-2">
                                                <label for="estimation_cpus">Estimasi CPUs</label>
                                                <input type="text" class="form-control" id="estimation_cpus"
                                                    placeholder="NULL" name="project[estimation_cpus]">
                                            </div>
                                            <div class="mb-2">
                                                <label for="actual_cpus">Aktual CPUs</label>
                                                <input type="text" class="form-control" id="actual_cpus"
                                                    placeholder="NULL" name="project[actual_cpus]">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2 overflow-auto">
                            <div class="container-fluid mb-3 d-flex flex-column align-items-center justify-content-end p-2"
                                style="background: #f4f4f4; min-height: 50vh;" id="comment-areas">
                                {{-- <div class="mb-3 w-100 d-flex flex-row-reverse gap-2">
                                    <div
                                        class="bg-white w-100 border-0 d-flex flex-column py-2 align-items-center justify-content-center">
                                        <p class="m-0">How are you sir ?</p>
                                        <p class="m-0 fs-6">- faidfadjri</p>
                                    </div>
                                    <img src="https://cdn.searchenginejournal.com/wp-content/uploads/2022/06/image-search-1600-x-840-px-62c6dc4ff1eee-sej-1520x800.png"
                                        style="height: 40px; width: 40px" class="rounded-circle">
                                </div> --}}
                            </div>
                            <div class="d-flex gap-3">
                                <input type="text" class="form-control" placeholder="Kirimkan komentar">
                                <button class="btn btn-success text-white font-bold">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-danger text-white d-flex align-items-center delete-button gap-2">
                        <ion-icon name="trash"></ion-icon> Hapus
                    </button>
                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                        <ion-icon name="save"></ion-icon> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    $(document).on('click', '.detail-button', function(param) {
        var id = $(this).attr('data-id');
        axios.post("/detail", {
            id: id
        }).then(function(response) {
            const field = ['image', 'full_name', 'description', 'progress', 'priority',
                'estimation_cost', 'actual_cost', 'estimation_revenue', 'actual_revenue',
                'estimation_cpus', 'actual_cpus', 'project_id'
            ];
            const data = response.data.detail;
            const comment = response.data.comment;
            const me = response.data.me;

            field.forEach(element => {
                $(`#${element}`).val(data[element]);
            });





            //----- create comment element
            let html = '';
            comment.forEach(element => {
                html +=
                    `<div class="mb-3 w-100 d-flex ${element.id_user == me.id ? "flex-row-reverse" : "flex-row"} gap-2">`;
                html +=
                    '        <div class="bg-white w-100 border-0 d-flex flex-column py-2 align-items-center justify-content-center">';
                html += `       <p class="m-0">${element.comment}</p>`;
                html += `       <p class="m-0 fs-6">- ${element.username}</p>`;
                html += '   </div>';
                html += `   <img src="/img/${element.image}";
                                        style="height: 40px; width: 40px" class="rounded-circle">`;
                html += ' </div>';
            });

            $("#comment-areas").html(html);




            $("#detailModal").modal('show');
            $(document).on('click', '.delete-button', function(param) {
                Swal.fire({
                    title: 'Yakin hapus data ini?',
                    text: 'data akan dihapus permanen',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire('Data berhasil di hapus!', '', 'success')
                    }
                })
            })


        }).catch(function(error) {
            console.log(error);
        })
    })

    $(document).on('click', '.add-button', function(param) {
        $("#addModal").modal('show');
    })
</script>
