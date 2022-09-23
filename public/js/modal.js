//---- Handle Modal
$(document).on("click", ".detail-button", function (param) {
    var id = $(this).attr("data-id");
    axios
        .post("/detail", {
            id: id,
        })
        .then(function (response) {
            const field = [
                "image",
                "full_name",
                "description",
                "progress",
                "priority",
                "estimation_cost",
                "actual_cost",
                "estimation_revenue",
                "actual_revenue",
                "estimation_cpus",
                "actual_cpus",
                "project_id",
                "project",
                "due_date",
            ];
            const data = response.data.detail;
            const me = response.data.me;
            $("#type").val(
                data["project_type"] ? data["project_type"] : "PROJECT"
            );

            const comment = response.data.comment;
            const files = response.data.files;

            set_comment(comment, me, "comment-areas");
            set_files(files, "files-areas");

            field.forEach((element) => {
                $(`#${element}`).val(data[element]);
            });
            $("#image-profile-thumbnail").attr("src", `/img/${me.image}`);
            $(document).on("click", ".delete-button", function (param) {
                var id = $("#project_id").val();
                Swal.fire({
                    title: "Yakin hapus data ini?",
                    icon: "warning",
                    text: "data akan dihapus permanen",
                    showCancelButton: true,
                    confirmButtonText: "Delete",
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire("Data berhasil di hapus!", "", "success");
                        axios
                            .delete(`/project/delete/${id}`)
                            .then((response) => {
                                location.href = "/project";
                            });
                    }
                });
            });

            $("#detailModal").modal("show");
        })
        .catch(function (err) {
            console.log(err);
        });
});

//---- Handle Add Modal
$(document).on("click", ".add-button", function (param) {
    $("#addModal").modal("show");
});

//---- Handle comment section
$("#send-comment").click(function (e) {
    e.preventDefault();
    var comment = $("#comment").val();
    var id_project = $("#project_id").val();

    $(
        this
    ).html(`<div class="spinner-grow" role="status" style="height:15px; width:15px">
                        <span class="visually-hidden">Loading...</span>
                      </div>`);

    if (comment) {
        send_comment(comment, id_project, $(this), $("#comment"));
    } else {
        alert("pesan masih kosong");
    }
});

//---- JQuery DOM
const set_comment = (comment, me, commentElement) => {
    let html = "";
    comment.forEach((element) => {
        html += `<div class="mb-3 w-100 d-flex ${
            element.id_user == me.id ? "flex-row" : "flex-row-reverse"
        } gap-2">`;
        html +=
            '        <div class="bg-white w-100 border-0 d-flex flex-column py-2 align-items-center justify-content-center ">';
        html += `       <p class="m-0 w-100 text-wrap px-2" style="word-wrap: break-word;">${element.comment}</p>`;
        html += `       <p class="m-0 fs-6 align-self-end mx-3 font-bold">- ${element.username}</p>`;
        html += "   </div>";
        html += " </div>";
    });

    $(`#${commentElement}`).html(html);
};

const set_files = (files, filesElement) => {
    let html = "";
    files.forEach((element) => {
        html += `<a class="btn btn-secondary" href="${
            "https://project-v2.akastra.id/files/" + element.file_name
        }" download="${
            "https://project-v2.akastra.id/files/" + element.file_name
        }">${element.file_name}</a>`;
    });
    $(`#${filesElement}`).html(html);
};

//---- send comment using asynchronous way using axios
const send_comment = async (comment, id_project, button, commentElement) => {
    try {
        const resp = await axios
            .post("/project/comment", {
                comment: comment,
                id_project: id_project,
            })
            .then(function (response) {
                let html = `<div class="mb-3 w-100 d-flex flex-row gap-2">`;
                html +=
                    '<div class="bg-white w-100 border-0 d-flex flex-column py-2 align-items-center justify-content-center">';
                html += `       <p class="m-0">${comment}</p>`;
                html += `       <p class="m-0 fs-6 font-bold align-self-end px-2">- ${response.data.user.username}</p>`;
                html += "   </div>";
                html += " </div>";

                $("#comment-areas").append(html);

                commentElement.val("");
                button.html("Send");
            })
            .catch(function (error) {
                button.html("Send");
                console.log(error);
            });
    } catch (err) {
        console.error(err);
    }
};
