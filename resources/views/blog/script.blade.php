<script>

    show('form#filterForm');

    $('form#createForm').on('submit', function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let data = new FormData();
        let title       = document.getElementById("inputCreateTitle").value;
        let description = document.getElementById("inputCreateDescription").value;
        let image       = document.getElementById("inputCreateImage").files[0];

        data.append('title', title);
        data.append('description', description);
        data.append('image', image);

        loaderBtn(true, '#createSubmitBtn');
        axios.post(url, data)
            .then(function (response) {
                loaderBtn(false, '#createSubmitBtn');

                if (response.data.success == true) {
                    toastr.success(response.data.message)
                    show('form#filterForm');
                    $('#create').modal('hide');
                    $('#createForm')[0].reset();
                } else {
                    toastr.error(response.data.message)
                }
            })
            .catch(function (error) {
                toastr.error(error)

            });
    });

    function show(data) {
        let form = $('#filterForm');
        let url = form.attr('action');

        //let url = '{{route('blog.show')}}'
        let method = form.attr('method');
        let theData = $(data).serialize();

        axios.post(url, theData)
            .then(function (response) {
                console.log(response)
                let htmlCode = '';
                let queryData = response.data.data;
                let filterAppliedData = response.data.filterApplied;

                let recordsFound = response.data.totalRecords;
                let onPage = $('#filterPage').val();



                if (recordsFound || filterAppliedData) {
                    htmlCode += '<div class="filter-form mb-3">';
                   if (recordsFound == 1) {
                        htmlCode += '<span class="mx-1 badge-dark filter-message shadow-2">' + recordsFound + ' result found</span>';
                    } else if (recordsFound > 1) {
                        htmlCode += '<span class="mx-1 badge-dark filter-message shadow-2">' + recordsFound + ' results found</span>';
                    }

                    if (onPage > 1) {
                        htmlCode += '<span class="mx-1 badge-dark filter-message  shadow-2"> on page ' + onPage + '</span>';
                    }

                    for (const property in filterAppliedData) {
                        htmlCode += '<span class="mx-1 badge-dark filter-message shadow-2">' + filterAppliedData[property] + '';
                        htmlCode += '<span class="mdi mdi-window-close arrow-down   align-middle cursor-pointer thatTooltip pl-3" onclick="filterCancel(\'' + property + '\')"></span>';
                        htmlCode += '</span>';
                    }
                    htmlCode += '</div>';
                }



                if (queryData) {
                    queryData.forEach(function (row) {
                        htmlCode += '<div class="card shadow-on-hover my-2">';

                        htmlCode += '<div class="card-header  ">';
                        htmlCode += '<div class="row justify-content-between no-gutters align-items-center">';
                        htmlCode += '<div class="col cursor-pointer" data-toggle="collapse" data-target="#theShowCollapse' + row.id + '">';
                        htmlCode += '<span class="cursor-pointer" > <img src="' + row.image + '" style="height:50px"></span> ';
                        htmlCode += '<span class="cursor-pointer ml-2" >' + row.title + '</span> ';
                        htmlCode += '</div>';
                        htmlCode += '<div class=" text-right">';
                        htmlCode += '<button class="btn btn-info mr-2" onclick="edit(' + row.id + ')">Edit</button>';
                        htmlCode += '<button class="btn btn-warning mr-2" onclick="deletee(' + row.id + ')">Delete</button>';
                        htmlCode += '<button class="btn btn-success" data-toggle="collapse" data-target="#theShowCollapse' + row.id + '"  >View</button>';
                        htmlCode += '</div>';
                        htmlCode += '</div>';
                        htmlCode += '</div>';

                        htmlCode += '<div class="card-body collapse" id="theShowCollapse' + row.id + '">'
                        htmlCode += '<label>Slug</label>';
                        htmlCode += '<p>';
                        htmlCode += '' + row.slug + '';
                        htmlCode += '</p>';
                        htmlCode += '<label>Description</label>';
                        htmlCode += '<p>';
                        htmlCode += '' + row.description + '';
                        htmlCode += '</p>';
                        htmlCode += '<div>';
                        htmlCode += '<span>';
                        htmlCode += '' + row.created_at + '';
                        htmlCode += '</span>';
                        htmlCode += '</div>';
                        htmlCode += '</div>';
                        htmlCode += '</div>'
                    });
                    htmlCode += '<div id="filterFormPagination">' + response.data.webPagination + '</div>';
                } else {
                    htmlCode += '<div class="alert alert-secondary text-center" role="alert">';
                    htmlCode += 'No data found';
                    htmlCode += '</div>';
                }
                $('#show').html(htmlCode);
                $('.thatTooltip').tooltip();
            })
            .catch(function (error) {
                toastr.error(error)

            });
    }

    function edit(id) {
        let url = '{{route('blog.edit')}}';
        let data = {
            id: id,
        };
        axios.post(url, data)
            .then(function (response) {
                if (response.data.success == true) {
                    console.log(response.data.data)
                    let data    = response.data.data[0];
                    let oldImg  = ''+data.image+'';

                    $('#edit').modal('show');
                    $('#inputEditId').val(data.id);
                    $('#inputEditTitle').val(data.title);
                    $('#inputEditDescription').html(data.description);
                    $('#oldImage').attr("src",oldImg);
                } else {
                    toastr.error(response.data.message)
                }
            }).catch(function (error) {
            toastr.error(error)
        });
    }

    function deletee(id) {
        let url = '{{route('blog.edit')}}';
        let deleteId = id;
        $('#delete').modal('show');
        $('#inputDeleteId').val(deleteId);
    }

    $('form#updateForm').on('submit', function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let data = new FormData();
        let id          = document.getElementById("inputEditId").value;
        let title       = document.getElementById("inputEditTitle").value;
        let description = document.getElementById("inputEditDescription").value;
        let image       = document.getElementById("inputEditImage").files[0];

        data.append('id', id);
        data.append('title', title);
        data.append('description', description);
        data.append('image', image);
        loaderBtn(true, '#updateSubmitBtn');
        axios.post(url, data)
            .then(function (response) {
                loaderBtn(false, '#updateSubmitBtn');

                if (response.data.success == true) {
                    toastr.success(response.data.message)
                    show('form#filterForm');
                    $('#edit').modal('hide');
                    $('#updateForm')[0].reset();
                } else {
                    toastr.error(response.data.message)
                }
            })
            .catch(function (error) {
                toastr.error(error)

            });
    });
    $('form#deleteForm').on('submit', function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let data = $(this).serialize();
        loaderBtn(true, '#deleteSubmitBtn');
        axios.post(url, data)
            .then(function (response) {
                loaderBtn(false, '#deleteSubmitBtn');

                if (response.data.success == true) {
                    toastr.success(response.data.message)
                    show('form#filterForm');
                    $('#delete').modal('hide');
                    $('#deleteForm')[0].reset();
                } else {
                    toastr.error(response.data.message)
                }
            })
            .catch(function (error) {
                toastr.error(error)

            });
    });
</script>
