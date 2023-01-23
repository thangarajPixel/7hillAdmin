@extends('platform.layouts.template')
@section('toolbar')
<div class="toolbar" id="kt_toolbar">
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        @include('platform.layouts.parts._breadcrum')
    </div>
</div>
@endsection
@section('content')
    <div id="kt_content_container" class="container-xxl">
        <div class="card">
            <div class="card-header border-0 pt-6 w-100">
                <div class="card-toolbar w-100">
                    <div class="d-flex justify-content-end w-100" data-kt-industrial-table-toolbar="base">
                        @if( access()->hasAccess('industrial-module', 'filter') )
                            @include('platform.product_industrial._filter')
                        @endif

                        @include('platform.layouts.parts.common._export_button')

                        <button type="button" class="btn btn-primary" onclick="return openForm('industrial-module')">
                            <span class="svg-icon svg-icon-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none">
                                    <rect opacity="0.5" x="11.364" y="20.364" width="16" height="2"
                                        rx="1" transform="rotate(-90 11.364 20.364)" fill="currentColor" />
                                    <rect x="4.36396" y="11.364" width="16" height="2" rx="1"
                                        fill="currentColor" />
                                </svg>
                            </span>
                            Add Industrial
                        </button>
                    </div>
                </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body py-4">
                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-2 mb-0 dataTable no-footer" id="industrial-table">
                        <thead>
                            <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                                <th> Title  </th>
                                <th> Slug  </th>
                                <th> Parent id  </th>
                                <th> Order By </th>
                                <th> Status </th>
                                <th style="width: 75px;">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('add_on_script')
    <script src="{{ asset('assets/js/datatable.min.js') }}"></script>
    <script>
        var dtTable = $('#industrial-table').DataTable({

            processing: true,
            sort:true,
            serverSide: true,
            type: 'POST',
            ajax: {
                "url": "{{ route('industrial-module') }}",
                "data": function(d) {
                    d.status = $('select[name=filter_status]').val();
                    d.filter_tax = $('select[name=filter_tax]').val();
                }
            },
            columns: [
                {
                data: 'title',
                name: 'title'
            },
            {
                data: 'slug',
                name: 'slug'
            },
            {
                data: 'parent_name',
                name: 'parent_name'
            },
            {
                data: 'sorting_order',
                name: 'sorting_order'
            },
            {
                data: 'status',
                name: 'status'
            },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            language: {
                paginate: {
                    next: '<i class="fa fa-angle-right"></i>', // or '→'
                    previous: '<i class="fa fa-angle-left"></i>' // or '←' 
                }
            },
            "aaSorting": [],
            "pageLength": 25
        });
        $('.dataTables_wrapper').addClass('position-relative');
        $('.dataTables_info').addClass('position-absolute');
        $('.dataTables_filter label input').addClass('form-control form-control-solid w-250px ps-14');
        $('.dataTables_filter').addClass('position-absolute end-0 top-0');
        $('.dataTables_length label select').addClass('form-control form-control-solid');

        $('#search-form').on('submit', function(e) {
            dtTable.draw();
            e.preventDefault();
        });
        $('#search-form').on('reset', function(e) {
            $('select[name=filter_status]').val(0).change();
            $('select[name=filter_tax]').val(0).change();

            dtTable.draw();
            e.preventDefault();
        });
    </script>
@endsection
