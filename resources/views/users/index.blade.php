@extends('layouts.app')
@section('title', 'Users Profile')
@section('pageTitle', 'Users List')
@section('breadcumb')
    <x-breadcumbs.breadcumb :data="[['title'=>'Users List', 'url'=>'']]" class=""></x-breadcumbs.breadcumb>
@endsection
@section('additionalButton')

@endsection

@section('content')
<x-containers.container-box>
    <div class="flex justify-between items-end gap-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center">
            {{-- <div>
                <input type="text" class="inputFieldCompact" id="searchKey" placeholder="Type to search...">
            </div> --}}

            <div>
                <select id="role"  name="select-filter" class="inputField">
                    <option value="">Role (All)</option>
                    @foreach($roles as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="">
                <div class="inline-block tooltip"  data-tip="Reset Filter">
                    <button type="button" class="btn-red w-9 aspect-square flex items-center justify-center" id="reset">
                        <i class="fas fa-undo text-center text-sm"></i>
                        <span
                            class="absolute z-10 invisible inline-block px-2 py-1 text-white transition-opacity bg-[#831b94] rounded-lg shadow-md opacity-0 hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible"
                            role="tooltip">
                        Reset
                    </span>
                    </button>
                </div>
            </div>
        </div>
        <div class="self-center h-full flex flex-col items-end justify-end">
            <div class="flex items-center align-middle gap-x-2">
                <div>
                    <h5 class="text-sm text-gray-800 dark:text-white">Action: </h5>
                </div>
                <div class="inline-block tooltip tooltip-left"  data-tip="Add User">
                    <button type="button" onclick="createUserModal.showModal()" class="actionBtn bg-[#831b94]"><i class="fa-solid fa-circle-plus"></i></button>
                </div>
            </div>
        </div>
    </div>
</x-containers.container-box>
<x-containers.container-box data="!p-0">
    <table id="dataTable" class="w-full display text-[13px] cell-border dark:text-white divide-neutral-200 dark:divide-neutral-700">
        <thead class="">
        <tr class="dark:hover:bg-neutral-800">
            <th scope="col"
                class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start truncate">
                SL
            </th>
            <th scope="col"
                class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start truncate">
                Emp Id
            </th>
            <th scope="col"
                class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                NAME
            </th>
            <th scope="col"
                class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                Role
            </th>
            <th scope="col"
                class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                Status
            </th>

            <th scope="col"
                class="px-6 py-3 text-xs font-medium text-gray-500 uppercase text-start">
                Action
            </th>
        </tr>
        </thead>
        <tbody id="tableData">

        </tbody>
    </table>
</x-containers.container-box>
@endsection
@include('users.create')
@section('scripts')
    <script>
        let dataTable = new DataTable('#dataTable', {
            responsive: true,
            serverSide: true,
            processing:true,
            ajax:{
                url: `{{ route('users.index') }}`
            },
            layout: tableDef.layout,
            columnDefs: [
                tableDef.columnDefs,
            ],
            order: [[1, 'asc']],
            columns: [
                { name: 'sl',  data: 'sl', sortable:false, render: function(data, type, row) {
                        let html=`<p class="text-center">${row.sl}</p>`;
                        return html;
                    }
                },
                { name: 'employeeID',  data: 'emp_id', render: function(data, type, row) {
                        let html=`<a href="${row.emp_id ? `${baseUrl}/employees/view/${row.emp_id}` : '#'}" class="emp-id-list-btn">${ row.emp_id?data : 'N/A'}</a>`;
                        return html;
                    },
                    className: 'dt-center',
                    width:'40px',
                    "createdCell": function (td, cellData, rowData, row, col) {
                        $(td).css('padding', '8px 12px')
                    },
                },
                { name: 'name',  data: 'full_name', searchable:false,
                    render: function(data, type, row) {
                        let html=`
                            <a href="${row.emp_id ? `${baseUrl}/employees/view/${row.emp_id}` : '#'}" class="hover:text-[#831b94] duration-200 leading-none">
                                <div class="flex items-center gap-3">
                                    <div class="">
                                        <figure class="w-8 aspect-square rounded-full overflow-hidden">
                                            <img class="w-full h-full object-cover" src="${row.profile_img}" onerror="this.onerror=null;this.src='${row.profile_img_default}';" alt="${row.full_name ? row.full_name : row.user_name}"/>
                                        </figure>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-sm">${row.full_name ? row.full_name : row.user_name}</span>
                                        <span>${row.email ?? (row.personal_email ?? '')}</span>
                                        <span class="text-xs">${row.phone ?? ''} ${row.organization ? `<span class="font-medium">(${row.organization})</span>`:``}</span>
                                    </div>
                                </div>
                            </a>
                        `
                        return html;
                    }
                },
                /*{ name: 'details',  data: 'organization', sortable:false,
                    render: function(data, type, row) {
                        return html=`<div class="flex flex-col">
                                        <p>${row.company}</p>
                                        <p>${row.department}</p>
                                        <p>${row.designation}</p>
                                    </div>`
                    }
                },*/
                { name: 'role',  data: 'role',
                    render: function(data, type, row) {
                        let bgCLass='bg-[#831b94]';
                        if(row.role==='Super Admin') bgCLass='bg-[#C80036]';
                        if(row.role==='HR') bgCLass='bg-[#01204E]';
                        if(row.role==='Department Head') bgCLass='bg-[#513c6b]';
                        if(row.role==='Employee') bgCLass='bg-[#1679AB]';
                        return html=`<span class="${bgCLass} text-white px-4 py-2 font-medium rounded-full text-xs">${row.role}</span>`
                    }
                },
                { name: 'status',  data: 'is_active', className: 'dt-center',
                    render: function(data, type, row) {
                        let bgCLass='bg-[#831b94]';
                        if(row.is_active===0) bgCLass='bg-[#C80036]';
                        if(row.is_active===1) bgCLass='bg-[#0D9276]';
                        return html=`<span class="${bgCLass} text-white px-2.5 pb-1.5 pt-1 font-medium rounded-full text-xs">${row.is_active==1? 'Active' : 'Inactive'}</span>`
                    }
                },
                { name: 'action',  data: 'id', sortable:false,
                    render: function(id, type, row) {

                        let editPermission=row.editPermission;
                        let deletePermission=row.deletePermission;
                        let html=`
                                <div class="flex items-center justify-center">
                                    <div class="dropdown dropdown-end">
                                        <div tabindex="0" role="button" class="hs-dropdown-toggle py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </div>
                                        <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-40 space-y-2">
                                            <li>
                                                ${editPermission
                                                    ?`<button  onclick="editUserModel(${row.id})" class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-gray-800 focus:outline-none focus:bg-[#831b94] active:bg-red-800 dark:text-neutral-400 hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white dark:focus:bg-red-800">
                                                        <i class="ti ti-edit"></i>
                                                        Edit
                                                    </button>`
                                                    : ``
                                                }
                                            </li>
                                            <li>
                                                ${deletePermission
                                                    ?`<button onclick="deletePopup('Delete User', '${row.full_name ? row.full_name : row.user_name}', '${baseUrl}/users/delete/${row.id}')" class="flex items-center gap-x-3.5 py-2 px-3 rounded-lg text-sm text-[#831b94] hover:bg-gray-100 focus:outline-none focus:bg-[#831b94] hover:bg-[#831b94] hover:text-white dark:text-gray-400 dark:hover:bg-red-700 dark:hover:text-white dark:focus:bg-red-800">
                                                        <i class="fa-regular fa-trash-can"></i>
                                                        Delete
                                                    </button>`
                                                    : ``
                                                }
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            `;
                        return editPermission || deletePermission ? html : '';
                    }
                },
            ]
        });
        $('#searchKey').on('input', debounce(function() {
            let searchText = $('#searchKey').val().trim();
            dataTable.search(searchText).draw();
        }, 500));
        $('#role, #organization, #designation').change(function (){
            let role = $('#role').val();
            dataTable.ajax.url(`{{ route('users.index') }}?role=${role}`).load();
        });
        $('#reset').click(function (){
            $('#role').val('')
            dataTable.ajax.url(`{{ route('users.index') }}`).load();
        });







    </script>
@endsection



