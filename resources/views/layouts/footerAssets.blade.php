@php $assetVersion=getAssetVersion(); @endphp
<script src="{{ asset('/assets/js/selectize.min.js') }}" ></script>
<!-- SheetJS (for XLSX) -->
<script src="{{ asset('/assets/js/xlsx.full.min.js') }}"></script>

<!-- jsPDF (for PDF) -->
<script src="{{ asset('/assets/js/jspdf.umd.min.js') }}"></script>
<script src="{{ asset('/assets/js/jspdf.plugin.autotable.min.js') }}"></script>

<script src="{{ asset('/assets/js/toastr.min.js') }}"></script>
<script src="{{ asset('/assets/js/theme-switcher.js') }}"></script>
<script src="{{ asset("/assets/js/scripts.js?v={$assetVersion}") }}"></script>

<script src="{{ asset('/assets/js/dataTables/dataTables.js') }}"></script>
<script src="{{ asset('/assets/js/lodash.min.js') }}"></script>
<script src="{{ asset('/assets/js/apex-charts.min.js') }}"></script>
<script src="{{ asset('/assets/js/preline-helper.js') }}"></script>
<script src="{{asset('assets/js/list.min.js')}}"></script>

<script src="{{ asset('/assets/js/full-calender/index.global.js') }}"></script>
{{--<script src="{{ asset('/assets/js/core-full-calendar.min.js') }}"></script>
<script src="{{ asset('/assets/js/daygrid-full-calendar.min.js') }}"></script>--}}
<script src="{{ asset('/assets/js/select2.min.js') }}"></script>
<script>

    //new DataTable('#example-trash')

    @if (Session::has('success'))
    toastr.success("{{ Session::get('success') }}");
    @endif

    @if (Session::has('info'))
    toastr.info("{{ Session::get('info') }}");
    @endif

    @if (Session::has('warning'))
    toastr.warning("{{ Session::get('warning') }}");
    @endif

    @if (Session::has('error'))
    toastr.error("{{ Session::get('error') }}");
    @endif

    $('#syncBtn').click(function () {
        $('#syncBtn').html($('#spinner-small-white').html() + ' Syncing');
    });

    $('.select2').select2();

</script>

