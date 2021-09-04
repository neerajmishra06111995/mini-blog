<script>

    show('form#filterForm');

    $('form#createForm').on('submit', function (e) {
        e.preventDefault();
        let form = $(this);
        let url = form.attr('action');
        let data = $(this).serialize();
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
        //let form = $('#filterForm');
        //let url = form.attr('action');

        let url = '{{route('blog.show-array-sort')}}'
        //let method = form.attr('method');
        //let theData = $(data).serialize();

        axios.get(url)
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


                    htmlCode += '</div>';
                }



                if (queryData) {
                    queryData.forEach(function (row) {
                        htmlCode += '<div class="card shadow-on-hover my-2">';

                        htmlCode += '<div class="card-header  ">';
                        htmlCode += '<div class="row justify-content-between no-gutters align-items-center">';
                        htmlCode += '<div class="col cursor-pointer" data-toggle="collapse" data-target="#theShowCollapse' + row.id + '">';
                        htmlCode += '<span class="cursor-pointer" >' + row.unsorted_array + '</span> ';
                        htmlCode += '</div>';
                        htmlCode += '</div>';
                        htmlCode += '</div>';

                        htmlCode += '<div class="card-body collapse" id="theShowCollapse' + row.id + '">'

                        htmlCode += '<label>Sorted Array</label>';
                        htmlCode += '<p>';
                        htmlCode += '' + row.sorted_array + '';
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

</script>
