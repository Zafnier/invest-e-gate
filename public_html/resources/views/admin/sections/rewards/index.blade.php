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
        
        /* General Table Button Styling */
        .table .btn-sm {
            padding: 0.3rem 0.6rem; /* Slightly increased padding for better clickability */
            font-size: 0.8rem;
            border-radius: 0.25rem;
        }
        
        /* Table Edit Button */
        .table .btn-warning {
            background-color: #9ba1c4; /* Edit Button */
            border: 1px;
            color: #fff;
            margin: 2px 0; /* Adds spacing between buttons */
        }
        
        .table .btn-warning:hover {
            background-color: #484060;
            border-color: #484060;
        }
        
        /* Table Delete Button */
        .table .btn-danger {
            background-color: #ea5455; /* Delete Button */
            border: 1px;
            color: #fff;
            margin: 2px 0; /* Adds spacing between buttons */
        }
        
        .table .btn-danger:hover {
            background-color: #ecc5c6;
            border-color: #ea5455;
        }
        
        /* Modal Header Styling */
        .modal-header {
            background-color: #dc3545;
            color: #fff;
            padding: 1rem;
            border-bottom: 1px solid #bd2130;
        }
        
        /* Modal Footer Button Styling */
        .modal-footer .btn-sm {
            padding: 0.3rem 0.6rem; /* Smaller size for buttons in the modal footer */
            font-size: 0.8rem;
            border-radius: 0.2rem;
        }
        
        /* Modal Cancel Button */
        .modal-footer .btn-secondary {
            background-color: #6c757d;
            border: 1px solid #6c757d;
            color: #fff;
            padding: 0.4rem 0.8rem; /* Matches the padding of the delete button */
            text-align: center;
        }
        
        .modal-footer .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
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
        
        .modal-footer .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

    </style>
@endpush

@section('page-title')
    @include('admin.components.page-title', ['title' => __("Rewards Management")])
@endsection

@section('breadcrumb')
    @include('admin.components.breadcrumb', [
        'breadcrumbs' => [
            [
                'name' => __("Dashboard"),
                'url' => route("admin.dashboard")
            ]
        ],
        'active' => __("Rewards Management")
    ])
@endsection

@section('content')
    <div class="table-area">
        <div class="table-wrapper">
            <div class="table-header d-flex justify-content-between align-items-center">
                <h5 class="title">{{ __("All Rewards") }}</h5>
                <div class="table-btn-area d-flex">
                    @include('admin.components.link.add-default', [
                        'href' => route('admin.rewards.create'),
                        'class' => "modal-btn ms-2",
                        'text' => __("Add Reward"),
                        'permission' => "admin.rewards.store"
                    ])
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __("Title") }}</th>
                            <th>{{ __("Description") }}</th>
                            <th>{{ __("Minimum Donation (PHP)") }}</th>
                            <th>{{ __("Discount (PHP)") }}</th>
                            <th>{{ __("Voucher Code") }}</th>
                            <th>{{ __("No. of Claims") }}</th>
                            <th>{{ __("Actions") }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rewards as $reward)
                            <tr data-item="{{ json_encode($reward) }}">
                                <td>{{ $reward->title }}</td>
                                <td>{{ $reward->description }}</td>
                                <td>{{ number_format($reward->min_donation, 2) }}</td>
                                <td>{{ number_format($reward->discount_per_thousand, 2) }}</td>
                                <td>{{ $reward->voucher_code ?? 'N/A' }}</td>
                                <td>
                                    {{ $reward->claimed_count ?? 0 }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.rewards.edit', $reward->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <button type="button" class="btn btn-danger btn-sm delete-modal-button" data-id="{{ $reward->id }}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{ $rewards->links() }}
    </div>

    {{-- Delete Reward Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">{{ __("Delete Reward") }}</h5>
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
            var rewardData = JSON.parse($(this).closest("tr").attr("data-item"));
            var actionRoute = "{{ route('admin.rewards.destroy', ':id') }}".replace(':id', rewardData.id);
            var message = `Are you sure you want to delete <strong>${rewardData.title}</strong>?`;

            $("#deleteModalMessage").html(message);
            $("#deleteForm").attr("action", actionRoute);
            $("#deleteModal").modal("show");
        });

        // Close modal on cancel
        $(document).on("click", ".btn-close, .btn-secondary", function () {
            $("#deleteModal").modal("hide");
        });
    </script>
@endpush
