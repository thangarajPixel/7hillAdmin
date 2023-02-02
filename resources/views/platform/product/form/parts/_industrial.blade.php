
<div class="card-body pt-0 fv-row">
    
    <select name="industrial_id" id="industrial_id" aria-label="Select a Industrial" data-control="select2" data-placeholder="Select a Industrial..." class="form-select mb-2 required">
        <option value=""></option>

        @isset($productIndustrial)
            @foreach ($productIndustrial as $item)
                <option value="{{ $item->id }}" 
                @if( (isset( $industrial_id ) && $industrial_id == $item->id ) || ( isset($info->industrial_id) && $info->industrial_id == $item->id ) ) selected @endif>{{ $item->title }}</option>
            @endforeach
        @endisset
    </select>
</div>
<script>
    var cat_id = '{{ $info->industrial_id ?? "" }}';
    setTimeout(() => {
        $('#industrial_id').select2();
        if( cat_id ) {
            $('#industrial_id').val(cat_id);
        }
        
    });
</script>