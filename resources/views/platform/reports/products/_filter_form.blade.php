<div class="card-toolbar w-100">
    <div>
        <h4> Filter Products </h4>
    </div>
    <form id="search-form">
        <div class="row w-100">
            {{-- <div class="col-sm-12 col-md-4 col-lg-4">
                <div class="form-group">
                    <label class="text-muted"> Date Added </label>
                    <input class="form-control form-control-solid w-100" name="date_range" placeholder="Pick date range" id="kt_ecommerce_report_views_daterangepicker" />
                </div>
            </div> --}}
            
            <div class="col-sm-6 col-md-4 col-lg-3">
                <div class="form-group">
                    <label class="text-muted">Category</label>
                    <select name="filter_product_category" id="filter_product_category" class="form-control product-select2">
                        <option value="">All</option>
                        @isset($productCategory)
                            @foreach ($productCategory as $item)
                                <option value="{{ $item->id }}" 
                                >{{ $item->name }} - {{ $item->parent->name ?? 'Parent' }} </option>
                            @endforeach
                        @endisset
                    </select>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group">
                    <label class="text-muted">Product Name / Sku  </label>
                    <input type="text" name="filter_product_name" id="filter_product_name" class="form-control">
                </div>
            </div>         
            <div class="col-sm-6 col-md-4 col-lg-2">
                @php
                    $stock_status_array = array('in_stock', 'out_of_stock', 'coming_soon', 'notify');
                @endphp
                <div class="form-group">
                    <label class="text-muted">Stock Status</label>
                    <select name="filter_stock_status" id="filter_stock_status" class="form-control product-select2">
                        <option value="">All</option>
                        @if( isset( $stock_status_array ) && !empty( $stock_status_array )) 
                            @foreach ($stock_status_array as $item)
                                <option value="{{ $item }}" >{{ ucwords( str_replace('_', ' ', $item ) ) }}</option>    
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            
            <div class="col-sm-6 col-md-4 col-lg-2">
                @php
                    $status_array = array('published', 'unpublished');
                @endphp
                <div class="form-group">
                    <label class="text-muted">Status</label>
                    <select name="filter_product_status" id="filter_product_status" class="form-control product-select2">
                        <option value="">All</option>
                        @if( isset( $status_array ) && !empty( $status_array )) 
                            @foreach ($status_array as $item)
                                <option value="{{ $item }}" >{{ ucfirst($item) }}</option>    
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>

            
            
            <div class="col-sm-6 col-md-4 col-lg-2">
                <div class="form-group mt-8 text-end">
                    <button type="reset" class="btn btn-sm btn-warning" > Clear </button>
                    <button type="submit" class="btn btn-sm btn-primary" > Submit </button>
                </div>
            </div>
        </div>
    </form>
</div>

