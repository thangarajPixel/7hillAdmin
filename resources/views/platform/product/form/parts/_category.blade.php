
<div class="card-body pt-0 fv-row" id="category_append">
    
     <select name="category_id" id="category_id" aria-label="Select a Category" data-control="select2" data-placeholder="Select a Category..." class="form-select mb-2 required">
        <option value=""></option> 
        @isset($industrialCategory)
            @foreach ($industrialCategory as $item)
                <option value="{{ $item->id }}" @if( (isset( $category_id ) && $category_id == $item->id ) || ( isset($info->category_id) && $info->category_id == $item->id ) ) selected @endif>{{ $item->name }} </option>
            @endforeach
        @endisset
    </select> 
</div>
