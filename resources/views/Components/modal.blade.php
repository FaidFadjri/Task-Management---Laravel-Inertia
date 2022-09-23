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
                        <input type="text" class="form-control" id="add_title" name="project[project]"
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

                    <div class="mb-3">
                        <label for="add_project_type">Project Type</label>
                        <select name="project[project_type]" id="add_project_type" class="form-control" required>
                            <option value="">Pilih Project Type</option>
                            <option value="PROJECT">PROJECT</option>
                            <option value="ACTIVITY">ACTIVITY</option>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex gap-2 align-items-center">
                    <h5 class="modal-title" id="staticBackdropLabel">Detail Modal</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 mb-2 overflow-auto h-100 font-bold">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <img src="https://images.unsplash.com/photo-1453728013993-6d66e9c9123a?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8dmlld3xlbnwwfHwwfHw%3D&w=1000&q=80"
                                    alt="logo" class="thumbnail rounded-circle"
                                    style="height: 40px; width: 40px; object-fit:cover" id="image-profile-thumbnail">
                                <input type="text" class="form-control bg-transparent border-0 rounded font-bold"
                                    value="{{ session()->get('user')['full_name'] }}" id="full_name" readonly
                                    disabled>
                            </div>
                            <div class="mb-2">
                                <input type="text" name="project[project]" id="project" class="form-control">
                            </div>
                            <textarea name="project[description]" id="description" class="form-control mb-3" cols="30" rows="10"
                                placeholder="Ketik Deksripsi"></textarea>

                            <!-- Project id setup -->
                            <input type="hidden" id="project_id" name="project[id]">
                            <div class="mb-2">
                                <select id="progress" class="form-control" name="project[progress]">
                                    @foreach ($progress_list as $item)
                                        <option value="{{ $item }}">{{ $item }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                            aria-expanded="true" aria-controls="collapseTwo">
                                            Files
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body d-flex flex-column gap-2" id="files-areas">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 mb-2">
                            <div id="comment-areas">
                            </div>
                            <div class="d-flex gap-3 mb-3">
                                <input type="text" class="form-control" placeholder="Kirimkan komentar"
                                    name="comment" id="comment">
                                <button
                                    class="btn btn-success text-white font-bold d-flex align-items-center justify-content-center"
                                    type="button" id="send-comment">Send</button>
                            </div>
                            <div class="accordion mb-3" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                            aria-expanded="true" aria-controls="collapseOne">
                                            More Info
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse"
                                        aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="mb-2">
                                                <label for="priority">Priority</label>
                                                <select id="priority" class="form-control" name="project[priority]">
                                                    <option value="HIGH">HIGH</option>
                                                    <option value="MEDIUM">MEDIUM</option>
                                                    <option value="LOW">LOW</option>
                                                </select>
                                            </div>
                                            <div class="mb-2">
                                                <label for="due_date">Due Date</label>
                                                <input type="date" class="form-control" id="due_date"
                                                    placeholder="NULL" name="project[due_date]">
                                            </div>
                                            <div class="mb-2">
                                                <label for="project_type">Project Type</label>
                                                <select name="project[project_type]" id="type" class="form-control">
                                                    <option value="PROJECT">Project</option>
                                                    <option value="ACTIVITY">Activity</option>
                                                </select>
                                            </div>
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
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Browse files</label>
                                <input class="form-control" type="file" id="formFile" name="file">
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




<script src="/js/modal.js" type="text/javascript"></script>
