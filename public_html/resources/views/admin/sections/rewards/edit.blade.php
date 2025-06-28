{{-- resources/views/admin/sections/rewards/edit.blade.php --}}
@extends('admin.layouts.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('public/backend/css/fontawesome-iconpicker.min.css') }}">
    <style>
        .fileholder {
            min-height: 150px !important;
        }

        .form-container {
            margin-top: 1rem;
        }

        .form-container h2 {
            margin-top: 0;
            padding-top: 0;
        }

        .form-group {
            margin-top: 1.5rem;
        }

        .form-control {
            border-radius: 8px;
        }
        
        h2 {
            color: white;
        }
    </style>
@endpush

@section('content')
    <div class="container form-container">
        <h2>{{ __('Update Reward') }}</h2>
        <form action="{{ route('admin.rewards.update', $reward->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Using PUT method for updates -->

            <!-- Reward Title -->
            <div class="form-group">
                <label for="title">{{ __('Title') }}</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $reward->title) }}" required>
            </div>

            <!-- Reward Description -->
            <div class="form-group mt-3">
                <label for="description">{{ __('Description') }}</label>
                <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description', $reward->description) }}</textarea>
            </div>

            <!-- Minimum Donation Amount -->
            <div class="form-group mt-3">
                <label for="min_donation">{{ __('Minimum Donation Amount') }}</label>
                <input type="number" name="min_donation" id="min_donation" class="form-control" value="{{ old('min_donation', $reward->min_donation) }}" min="0" step="0.01" required>
            </div>

            <!-- Discount Amount -->
            <div class="form-group mt-3">
                <label for="discount_per_thousand">{{ __('Discount Per Thousand Peso') }}</label>
                <div class="input-group">
                    <input type="number" name="discount_per_thousand" id="discount_per_thousand" class="form-control" value="{{ old('discount_per_thousand', $reward->discount_per_thousand) }}" min="0" step="0.01" required>
                    <span class="input-group-text">PHP</span>
                </div>
                <small class="form-text text-muted">
                    {{ __('Set the discount amount (e.g., PHP 20) for every PHP 1,000 donation.') }}
                </small>
            </div>

            <!-- Claimable Status -->
            <div class="form-group mt-3">
                <label for="is_claimable">{{ __('Claimable Status') }}</label>
                <div class="form-check">
                    <input type="checkbox" name="is_claimable" id="is_claimable" class="form-check-input" {{ old('is_claimable', $reward->is_claimable) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_claimable">{{ __('Allow this reward to be claimed by users') }}</label>
                </div>
            </div>

            <!-- Update Button -->
            <button type="submit" class="btn btn-primary mt-4">{{ __('Update Reward') }}</button>
        </form>
    </div>
@endsection
