@extends('admin.layouts.master')

@push('css')
    <style>
        .fileholder {
            min-height: 194px !important;
        }

        .fileholder-files-view-wrp.accept-single-file .fileholder-single-file-view,
        .fileholder-files-view-wrp.fileholder-perview-single .fileholder-single-file-view {
            height: 150px !important;
        }

        .select2-results__option.select2-results__option--selectable.select2-results__option--selected {
            display: none;
        }

        /* Table Button Styling */
        .table .btn-sm {
            padding: 0.3rem 0.6rem;
            font-size: 0.8rem;
            border-radius: 0.25rem;
        }

        .table .btn-warning {
            background-color: #9ba1c4;
            border: 1px;
            color: #fff;
            margin: 2px 0;
        }

        .table .btn-danger {
            background-color: #ea5455;
            border: 1px;
            color: #fff;
            margin: 2px 0;
        }
        /* Modal Delete Button */
        .modal-footer .btn-danger {
            background-color: #dc3545; /* Modal Delete Button */
            border: 1px solid #dc3545;
            color: #fff;
            padding: 0.4rem 0.8rem; /* Adjusts padding for better emphasis */
            min-width: 120px; /* Ensures the button has a consistent, longer background */
            text-align: center; /* Centers the text within the button */
        }
        /* Modal Header Styling */
        .modal-header {
            background-color: #dc3545;
            color: #fff;
            padding: 1rem;
            border-bottom: 1px solid #bd2130;
        }
    </style>
@endpush

@section('page-title')
    @include('admin.components.page-title', ['title' => __("Vendor Management")])
@endsection

@section('breadcrumb')
    @include('admin.components.breadcrumb', [
        'breadcrumbs' => [
            [
                'name' => __("Dashboard"),
                'url' => route("admin.dashboard")
            ]
        ],
        'active' => __("Vendor Management")
    ])
@endsection

@section('content')
    <div class="table-area">
        <div class="table-wrapper">
            <div class="table-header d-flex justify-content-between align-items-center">
                <h5 class="title">{{ __("All Vendors") }}</h5>
                <div class="table-btn-area d-flex">
                    @include('admin.components.link.add-default', [
                        'href' => route('admin.vendors.create'),
                        'class' => "modal-btn ms-2",
                        'text' => __("Add Vendor"),
                        'permission' => "admin.vendors.store"
                    ])
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __("Vendor Name") }}</th>
                            <th>{{ __("Business Name") }}</th>
                            <th>{{ __("Contact Number") }}</th>
                            <th>{{ __("Address") }}</th>
                            <th>{{ __("Documents") }}</th>
                            <th>{{ __("Actions") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vendors as $vendor)
                            <tr data-item="{{ json_encode($vendor) }}">
                                <td>{{ $vendor->name }}</td>
                                <td>{{ $vendor->business_name }}</td>
                                <td>{{ $vendor->contact_number }}</td>
                                <td>{{ $vendor->address }}</td>
                                <td>
                                    @if($vendor->sec_registration)
                                        <a href="{{ url('public/' . $vendor->sec_registration) }}" target="_blank" class="btn btn-info btn-sm">{{ __("View SEC Registration") }}</a>
                                    @else
                                        <span class="text-muted">{{ __("No SEC file") }}</span>
                                    @endif
                                    <br>
                                    <br>
                                    @if($vendor->bir_registration)
                                        <a href="{{ url('public/' . $vendor->bir_registration) }}" target="_blank" class="btn btn-info btn-sm">{{ __("View BIR Registration") }}</a>
                                    @else
                                        <span class="text-muted">{{ __("No BIR file") }}</span>
                                    @endif
                                    <br>
                                    <br>
                                    @if($vendor->dti_registration)
                                        <a href="{{ url('public/' . $vendor->dti_registration) }}" target="_blank" class="btn btn-info btn-sm">{{ __("View DTI Registration") }}</a>
                                    @else
                                        <span class="text-muted">{{ __("No DTI file") }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.vendors.edit', $vendor->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <button type="button" class="btn btn-danger btn-sm delete-modal-button" data-id="{{ $vendor->id }}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{ $vendors->links() }}
    </div>

    {{-- Delete Vendor Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">{{ __("Delete Vendor") }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="deleteModalMessage"></p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm cancel-button" data-bs-dismiss="modal">{{ __("Cancel") }}</button>
                    <form id="deleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm delete-button">{{ __("Delete") }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        $(document).on("click", ".delete-modal-button", function () {
            var vendorData = JSON.parse($(this).closest("tr").attr("data-item"));
            var actionRoute = "{{ route('admin.vendors.destroy', ':id') }}".replace(':id', vendorData.id);
            var message = `Are you sure you want to delete <strong>${vendorData.name}</strong>?`;

            $("#deleteModalMessage").html(message);
            $("#deleteForm").attr("action", actionRoute);
            $("#deleteModal").modal("show");
        });

        $(document).on("click", ".btn-close, .btn-secondary", function () {
            $("#deleteModal").modal("hide");
        });
    </script>
@endpush
