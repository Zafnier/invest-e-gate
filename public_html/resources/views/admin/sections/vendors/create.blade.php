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
    </style>
@endpush

@section('page-title')
    @include('admin.components.page-title', ['title' => __("Add Vendor")])
@endsection

@section('breadcrumb')
    @include('admin.components.breadcrumb', [
        'breadcrumbs' => [
            [
                'name' => __("Dashboard"),
                'url' => route("admin.dashboard")
            ],
            [
                'name' => __("Vendor Management"),
                'url' => route("admin.vendors.index")
            ]
        ],
        'active' => __("Add Vendor")
    ])
@endsection

@section('content')
    <form action="{{ route('admin.vendors.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ __("Vendor Registration Form") }}</h5>
                
                <!-- Vendor Name -->
                <div class="form-group mb-3">
                    <label for="name">{{ __("Vendor Name") }}</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Business Name -->
                <div class="form-group mb-3">
                    <label for="business_name">{{ __("Business Name") }}</label>
                    <input type="text" class="form-control" id="business_name" name="business_name" value="{{ old('business_name') }}" required>
                    @error('business_name')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Contact Number -->
                <div class="form-group mb-3">
                    <label for="contact_number">{{ __("Contact Number") }}</label>
                    <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" required>
                    @error('contact_number')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- Address -->
                <div class="form-group mb-3">
                    <label for="address">{{ __("Address") }}</label>
                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" required>
                    @error('address')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                
                <!-- SEC Registration -->
                <div class="form-group mb-3">
                    <label for="sec_registration">{{ __("SEC Registration") }}</label>
                    <input type="file" class="form-control" id="sec_registration" name="sec_registration" required>
                    @error('sec_registration')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- BIR Registration -->
                <div class="form-group mb-3">
                    <label for="bir_registration">{{ __("BIR Registration") }}</label>
                    <input type="file" class="form-control" id="bir_registration" name="bir_registration" required>
                    @error('bir_registration')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- DTI Registration -->
                <div class="form-group mb-3">
                    <label for="dti_registration">{{ __("DTI Registration") }}</label>
                    <input type="file" class="form-control" id="dti_registration" name="dti_registration" required>
                    @error('dti_registration')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">{{ __("Save Vendor") }}</button>
            </div>
        </div>
    </form>
@endsection
