@extends('platform.layouts.template')
@section('toolbar')
<div class="toolbar" id="kt_toolbar">
    <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
        @include('platform.layouts.parts._breadcrum')
    </div>
</div>
@endsection

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-xxl">
                @csrf
                <div >
                    <div class="card" >
                        <div class="card-header">
                            <div class="card-title w-100">
                                <h2 class="w-100">
                                    Product Enquiry Information
                                </h2>
                            </div>
                        </div>
                        <div class="card-body border-top p-9">
                        
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6"> Name :</label>
                                <div class="col-lg-8 fv-row">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">{{ $info->name }}</label>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">
                                    <span class="">Product Name :</span>
                                </label>
                                <div class="col-lg-8 fv-row">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">{{ $info->productData->product_name }}</label>

                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">
                                    <span class="">Email :</span>
                                </label>
                                <div class="col-lg-8 fv-row">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">{{ $info->email }}</label>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">Mobile :</label>
                                <div class="col-lg-8 fv-row">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">{{ $info->mobile }}</label>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">Company Name :</label>
                                <div class="col-lg-8 fv-row">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">{{ $info->company_name }}</label>
                                </div>
                            </div>
                            <div class="row mb-6">
                                <label class="col-lg-4 col-form-label fw-bold fs-6">City :</label>
                                <div class="col-lg-8 fv-row">
                                    <label class="col-lg-4 col-form-label fw-bold fs-6">{{ $info->city }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div>
                                <a type="button" class="btn btn-sm btn-active-light-primary" href="{{ route('enquiry') }}">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection
