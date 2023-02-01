<div class="d-flex flex-column gap-7 gap-lg-10">
    <div class="card card-flush py-4">
        <div class="card-body pt-2">
            <div class="mb-10 fv-row">
                <div>
                    <label class="form-label">Description</label>
                    <div id="kt_ecommerce_add_product_short_description" name="kt_ecommerce_add_product_short_description" class="min-h-200px mb-2">{!! $info->description ?? '' !!}</div>
                    <textarea name="product_description" id="product_description" class="d-none" cols="30" rows="10">{!! $info->description ?? '' !!}</textarea>
                </div>
                <br>
                <div>
                    <label class="form-label">Product Specification</label>
                    <div id="kt_ecommerce_add_product_specification" name="specification" class="min-h-200px mb-2">{!! $info->specification ?? '' !!}</div>
                    <textarea name="product_specification" class="d-none" id="product_specification" cols="30" rows="10">{!! $info->specification ?? '' !!}</textarea>
                </div>
                <div>
                    <label class="form-label">Prodct Model </label>
                    <div id="kt_ecommerce_add_product_model" name="kt_ecommerce_add_product_model" class="min-h-200px mb-2">{!! $info->product_model ?? '' !!}</div>
                    <textarea name="product_model" class="d-none" id="product_model" cols="30" rows="10">{!! $info->product_model ?? '' !!}</textarea>
                </div>
               
                <br>
            </div>
        </div>
    </div>  
</div>

<script>

</script>
