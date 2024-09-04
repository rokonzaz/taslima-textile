@extends('layouts.app')
@section('title', 'Reports')
@section('pageTitle', 'Reports')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title'=>'Reports', 'url'=>'']]" class=""></x-breadcumbs.breadcumb>
@endsection
@section('additionalButton')

@endsection

@section('content')
    <x-containers.container-box>
        <div class="flex justify-between items-end gap-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end shrink">
                {{--<div>
                    <label for="select-filter" class="inputLabel">Search</label>
                    <input type="text" class="inputField" id="searchKey" placeholder="Type to search...">
                </div>--}}
                <div class="">
                    <label for="select-filter" class="inputLabel">Organization</label>

                    <select id="organization"  name="select-filter" id="organization" class="inputField">
                        <option value="">All</option>
                        @foreach($organizations as $key=>$item)
                            <option value="{{$item->id}}" {{--{{$key==0 ? 'selected' : ''}}--}}>{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label  class="inputLabel">Department</label>

                    <select id="department"  class="inputField">
                        <option value="">All</option>
                        @foreach($departments as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="select-filter" class="inputLabel">Designation</label>

                    <select id="designation"  name="designation" class="inputField">
                        <option value="">All</option>
                        @foreach($designations as $item)
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="inline-flex items-end gap-3">
                    <div>
                        <label for="select-filter" class="inputLabel">Date</label>
                        <input type="date" id="date"  name="date" value="{{date('Y-m-d')}}" class="inputField">
                    </div>
                    <div class="inline-block tooltip mb-2.5"  data-tip="Find">
                        <button type="button" class="btn-red" id="submit" onclick="getAttendanceReport()"><i class="fa-solid fa-magnifying-glass text-base p-1.5 text-center"></i></button>
                    </div>
                    <div class="inline-block tooltip mb-2.5"  data-tip="Reset All Filters">
                        <button type="reset" class="btn-red" id="reset"><i class="fas fa-undo text-base p-1.5 text-center"></i></button>
                    </div>
                </div>

            </div>
        </div>
    </x-containers.container-box>
    <div id="reportsData"></div>
@endsection
@include('department.create')
@section('scripts')

    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>
    <script>
        function getAttendanceReport() {
            $('#reportsData').html($('#spinner-large').html())
            let organization=$('#organization').val();
            let department=$('#department').val();
            let designation=$('#designation').val();
            let date=$('#date').val();
            $.ajax({
                url:`${baseUrl}/reports/get-reports?a=attendance-reports`,
                data:{
                    organization:organization,
                    department:department,
                    designation:designation,
                    date:date,
                }
            }).then(function (res) {
                if(res.status===1){
                    $("#reportsData").html(res.html)
                    let dataTable = new DataTable('#dataTable',{
                        responsive: true,
                        "pageLength": 50,
                        layout: {

                            topStart:{
                                search:true
                            },
                            topMiddle: {

                            },
                            topEnd:{
                                buttons: [
                                    {
                                        titleAttr: 'csv',
                                        text: '<i class="ti ti-file-type-csv"></i> CSV',
                                        extend: 'csvHtml5',
                                    },
                                    {
                                        titleAttr: 'excel',
                                        text: '<i class="ti ti-file-spreadsheet"></i> Excel',
                                        extend: 'excelHtml5'
                                    },
                                    {
                                        titleAttr: 'pdf',
                                        text: '<i class="ti ti-file-type-pdf"></i> PDF',
                                        extend: 'pdfHtml5',
                                    },
                                    /*{
                                        titleAttr: 'print',
                                        text: '<i class="ti ti-file-type-csv"></i> Print',
                                        extend: 'printHtml5',
                                    },*/

                                ]
                            },
                            /*['csv', 'excel', 'pdf', 'print']*/
                            bottomStart: {
                                pageLength: true,
                                info: true
                            }
                        },
                        "createdRow": function( row, data, dataIndex){
                            if( data[7] ===  'Present'){
                                $(row).addClass('bg-red-50');
                            }
                        },
                        columnDefs: [{
                            "targets": '_all',
                            "createdCell": function (td, cellData, rowData, row, col) {
                                $(td).css('padding', '8px 8px')
                            }
                        }]
                    });
                }else{
                    toastr.error(res.msg)
                }
            })
        }
        getAttendanceReport();

        $('#reset').click(function (){
            $('#department').val('')
            $('#organization').val('');
            $('#designation').val('');
            var currentDate = new Date();
            var formattedDate = currentDate.toISOString().slice(0, 10);
            $('#date').val(formattedDate);
            let department = '';
            let organization = '';
            let designation = '';
            let date = '';
            $('#reportsData').html($(''))
            //$('#searchKey').val('');
            //dataTable.ajax.url(`{{ route('attendance.index') }}?department=${department}&organization=${organization}&designation=${designation}&date=${date}&searchKey=`).load();
        });
        $(document).ready(function() {
            //$('#organization').select2();
        });
    </script>

@endsection



