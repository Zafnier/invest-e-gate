@extends('user.layouts.master')

@push('css')
<link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">
<style>
    .pagination {
        margin-top: 20px !important;
    }

    .note {
        background-color: rgba(173, 216, 230, 0.7); /* Light blue with low opacity */
        color: #041f7a; /* Text color */
        font-size: 0.9em;
        border-radius: 8px; /* Rounded corners */
        padding: 10px; /* Padding inside the note */
        margin-bottom: 10px; /* Reduced space below the note */
    }

    .btn-claim {
        background-color: #28a745; /* Green */
        color: #fff;
        padding: 5px;
        font-size: 12px;
        border-radius: 15px;
        width: 80px;
    }

    .btn-secondary {
        background-color: #ccc;
        color: #666;
        padding: 5px;
        font-size: 12px;
        border-radius: 15px;
        width: 80px;
    }

    .btn-claim:disabled {
        background-color: #96889d; /* Grey when disabled */
        color: #666;
        padding: 5px;
        font-size: 12px;
        border-radius: 15px;
        width: 80px;
    }

    .table-wrapper {
        overflow-x: auto;
        overflow-y: auto;
        max-height: 600px;
    }

    .table-area {
        margin-top: 0; /* Reduced space between the note and table */
    }

    .table {
        border-collapse: separate;
        border-spacing: 0 20px; /* Space between rows */
        width: 100%;
    }

    .table th {
        background-color: #4a8fca; /* Blue background */
        color: white;
        font-weight: bold;
        padding: 15px;
        text-align: center; /* Center header content */
        font-size: 14px; /* Smaller font size for header */
        border: none;
    }

    .table thead tr:first-child th:first-child {
        border-top-left-radius: 10px;
    }

    .table thead tr:first-child th:last-child {
        border-top-right-radius: 10px;
    }

    .table td {
        background-color: white; /* White background for rows */
        padding: 5px; /* Space inside cells */
        text-align: center; /* Center content */
        font-size: 12px; /* Smaller font size for content */
        color: #333; /* Darker text for readability */
        border: none; /* Remove borders */
        font-weight: bold;
    }

    .table tbody tr {
        background-color: white;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); /* Shadow only around the row */
        border-radius: 10px; /* Rounded corners for the row */
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out; /* Smooth animation */
    }

    .table tbody tr:hover {
        transform: scale(1.02); /* Slightly enlarge on hover */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Darker shadow on hover */
    }

    .table tbody tr td:first-child {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }

    .table tbody tr td:last-child {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }

    .text-truncate {
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
@endpush

@section('breadcrumb')
    @include('user.components.breadcrumb', [
        'breadcrumbs' => [
            [
                'name' => __('Dashboard'),
                'url'  => route('user.dashboard'),
            ],
        ],
        'active' => __('My Rewards'),
    ])
@endsection

@section('content')
<div class="table-wrapper pt-60">
    <div class="row justify-content-center">
        <div class="col-xl-12">
            <div class="table-area table-responsive">
                <h4 class="title">{{ __('My Rewards') }}</h4>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-area">
                    <div class="note">
                        {{ __('Thank you for your generosity! Below are the details regarding the rewards you’ve earned:') }}
                        <ul style="list-style-type: disc; color: #000; padding: 10px;">
                            <li>{{ __('Rewards are valid only at the business to which your donations were made.') }}</li>
                            <li>{{ __('Please present your donation history page as proof on the day you claim your reward.') }}</li>
                            <li>{{ __('A minimum spend must be met to claim the reward.') }}</li>
                            <li>{{ __('Each reward is claimable only once.') }}</li>
                        </ul>
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ __('Date Earned') }}</th>
                                <th>{{ __('Reward Title') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Voucher Code') }}</th>
                                <th>{{ __('Discount Amount') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Date Claimed') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rewards as $reward)
                                <tr>
                                    <td>{{ $reward->created_at ? $reward->created_at->format('Y-m-d') : __('N/A') }}</td>
                                    <td>{{ $reward->reward->title ?? __('No Title Available') }}</td>
                                    <td class="text-truncate">{{ $reward->reward->description ?? __('No Description Available') }}</td>
                                    <td>{{ $reward->voucher_code ?? __('No Voucher Code') }}</td>
                                    <td>
                                        {{ isset($reward->reward->discount_per_thousand)
                                            ? number_format($reward->reward->discount_per_thousand, 2) . ' PHP'
                                            : __('N/A') }}
                                    </td>
                                    <td>
                                        @if($reward->is_claimed)
                                            <span class="badge bg-success">{{ __('Claimed') }}</span>
                                        @else
                                            <span class="badge bg-warning">{{ __('Unclaimed') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($reward->is_claimed)
                                            {{ $reward->claimed_at ? $reward->claimed_at->format('Y-m-d') : __('N/A') }}
                                        @else
                                            <span class="text-muted">{{ __('Not Claimed Yet') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($reward->is_claimed)
                                            <button class="btn btn-secondary btn-sm" disabled>{{ __('Already Claimed') }}</button>
                                        @else
                                            <!-- Updated Button with data-description -->
                                            <button 
                                                class="btn btn-claim btn-sm" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#claimModal" 
                                                data-id="{{ $reward->id }}"
                                                data-user-name="{{ $reward->user->firstname ?? __('N/A') }} {{ $reward->user->lastname ?? '' }}"
                                                data-date-earned="{{ $reward->created_at ? $reward->created_at->format('Y-m-d') : __('N/A') }}"
                                                data-title="{{ $reward->reward->title ?? __('No Title') }}"
                                                data-description="{{ $reward->reward->description ?? __('No Description Available') }}"
                                                data-voucher="{{ $reward->voucher_code ?? __('N/A') }}"
                                                data-discount="{{ isset($reward->reward->discount_per_thousand)
                                                    ? number_format($reward->reward->discount_per_thousand, 2) . ' PHP'
                                                    : __('N/A') }}"
                                                data-date-claimed="{{ now()->format('Y-m-d') }}">
                                                {{ __('Claim') }}
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">{{ __('No Rewards Available') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="pagination-wrapper">
                        {{ $rewards->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Claim Reward Modal --}}
<div class="modal fade" id="claimModal" tabindex="-1" aria-labelledby="claimModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="claimForm" method="POST">
                @csrf
                <input type="hidden" name="voucher_code" id="modal-voucher-input">
                <div class="modal-header">
                    <h5 class="modal-title" id="claimModalLabel">{{ __('Claim Reward') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>{{ __('Full Name:') }}</strong> <span id="modal-user-name"></span></p>
                    <p><strong>{{ __('Date Earned:') }}</strong> <span id="modal-date-earned"></span></p>
                    <p><strong>{{ __('Reward Title:') }}</strong> <span id="modal-title"></span></p>
                    <!-- Added Description in Modal -->
                    <p><strong>{{ __('Description:') }}</strong> <span id="modal-description"></span></p>
                    <p><strong>{{ __('Voucher Code:') }}</strong> <span id="modal-voucher"></span></p>
                    <p><strong>{{ __('Discount Amount:') }}</strong> <span id="modal-discount"></span></p>
                    <p><strong>{{ __('Date Claimed:') }}</strong> <span id="modal-date-claimed"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-claim">{{ __('Claim') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const claimModal = document.getElementById('claimModal');

        claimModal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget;

            const rewardId = button.getAttribute('data-id');
            const userName = button.getAttribute('data-user-name');
            const dateEarned = button.getAttribute('data-date-earned');
            const title = button.getAttribute('data-title');
            const description = button.getAttribute('data-description'); // Get description
            const voucher = button.getAttribute('data-voucher');
            const discount = button.getAttribute('data-discount');
            const dateClaimed = button.getAttribute('data-date-claimed');

            document.getElementById('modal-user-name').textContent = userName;
            document.getElementById('modal-date-earned').textContent = dateEarned;
            document.getElementById('modal-title').textContent = title;
            document.getElementById('modal-description').textContent = description; // Set description
            document.getElementById('modal-voucher').textContent = voucher;
            document.getElementById('modal-discount').textContent = discount;
            document.getElementById('modal-date-claimed').textContent = dateClaimed;
            document.getElementById('modal-voucher-input').value = voucher;

            document.getElementById('claimForm').setAttribute('action', `{{ url('/user/rewards/claim/') }}/${rewardId}`);
        });
    });
</script>
@endpush
