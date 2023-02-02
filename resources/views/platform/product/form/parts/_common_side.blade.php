<div class="card card-flush" id="product-thumbnail">
    @include('platform.product.form.parts._thumbnail')
</div>
<div class="card card-flush" >
    <div class="card-header">
        <div class="card-title w-100">
            <h2 class="w-100 required">
                Industrial
                <span class="float-end">
                    <a href="javascript:void(0)" onclick="return openForm('industrial-module', '', 'product')" class="btn btn-light-primary btn-sm">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
                                <rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </a>
                </span>
    
            </h2>
        </div>
    </div>
    <div id="product-category">
        @include('platform.product.form.parts._industrial')
    </div>
</div>
<div class="card card-flush" >
    <div class="card-header">
        <div class="card-title w-100">
            <h2 class="w-100 required">
                Categories
                <span class="float-end">
                    <a href="javascript:void(0)" onclick="return openForm('product-category', '', 'product')" class="btn btn-light-primary btn-sm">
                        <span class="svg-icon svg-icon-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <rect opacity="0.5" x="11" y="18" width="12" height="2" rx="1" transform="rotate(-90 11 18)" fill="currentColor" />
                                <rect x="6" y="11" width="12" height="2" rx="1" fill="currentColor" />
                            </svg>
                        </span>
                        <!--end::Svg Icon-->
                    </a>
                </span>
    
            </h2>
        </div>
    </div>
    
    <div id="product-category">
        @include('platform.product.form.parts._category')
    </div>
</div>

<div class="card card-flush" id="product-status">
    @include('platform.product.form.parts._status')
</div>

{{-- <div class="card card-flush" id="product-brochure">
    @include('platform.product.form.parts._brochure')
</div> --}}