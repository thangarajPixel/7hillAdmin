
<select name="category_id" id="category_id" aria-label="Select a Category" data-control="select2" data-placeholder="Select a Category..." class="form-select mb-2 required">
   
    <option value=""></option>
    @isset($industrialCategory)
        @foreach ($industrialCategory as $item)
            <option value="{{ $item->id }}" 
            @if( (isset( $category_id ) && $category_id == $item->id ) || ( isset($info->category_id) && $info->category_id == $item->id ) ) selected @endif>{{ $item->name }} - {{ $item->categoryParentData->title }} </option>
        @endforeach
    @endisset
</select>
<script>
    var cat_id = '{{ $info->category_id ?? "" }}';
    setTimeout(() => {
        $('#category_id').select2();
        if( cat_id ) {
            $('#category_id').val(cat_id);
        }
    });
</script>