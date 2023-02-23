<div class="row mb-7">
   
    <div class="col-md-8">
        <div class="fv-row mb-7">
            <label class="required fw-bold fs-6 mb-2">Title</label>
            <input type="text" name="title" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Title" value="{{ $info->title ?? '' }}" />
        </div>
        <div class="fv-row mb-7">
            <label class="fw-bold fs-6 mb-2"> Is Parent </label>
            <div class="form-check form-switch form-check-custom form-check-solid fw-bold fs-6 mb-2">
                <input class="form-check-input" type="checkbox"  name="is_parent" id="is_parent" value="1" @if( (isset( $info->parent_id ) && $info->parent_id == 0 ) || !isset($info->parent_id) ) checked @endif />
            </div>
            <div class="fv-row @if( (isset( $info->parent_id ) && $info->parent_id == 0 ) || !isset($info->parent_id) ) d-none @endif" id="parent-tab">
                <label class="required fw-bold fs-6 mb-2">Parent Industrial</label>
                <select name="parent_category" id="parent_category" aria-label="Select a Language" data-control="select2" data-placeholder="Select a Language..." class="form-select mb-2">

                    @isset($productCategory)
                        @foreach ($productCategory as $item)
                            <option value="{{ $item->id }}" @if( isset( $info->parent_id ) && $info->parent_id == $item->id ) selected @endif>{{ $item->title }}</option>
                        @endforeach
                    @endisset
                </select>
            </div>
        </div>
        <div class="fv-row mb-7">
            <label class="fw-bold fs-6 mb-2">Description</label>
            <textarea class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Description" name="description" id="description" cols="30" rows="5">{{ $info->description ?? '' }}</textarea>
        </div>
        <div class="mb-7 mt-10">
            <label class="fw-bold fs-6 mb-2"> Published </label>
            <div class="form-check form-switch form-check-custom form-check-solid fw-bold fs-6 mb-2">
                <input class="form-check-input" type="checkbox"  name="status" value="1"  @if( (isset( $info->status) && $info->status == 'published') || (!isset($info->status)))  checked @endif />
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class=" mb-7">
            <div class="fv-row">
                <label class="d-block fw-bold fs-6 mb-5">Banner <small>(Size 1600*350)</small></label>
                <div class="form-text">
                    Allowed file types: png, jpg,
                    jpeg.
                </div>
                
            </div>
            <input id="image_remove_banner" type="hidden" name="image_remove_banner" value="yes">
            <div class="image-input image-input-outline manual-image-banner" data-kt-image-input="true"
                style="background-image: url({{ asset('userImage/no_Image.jpg') }})">
                @if ($info->banner_image ?? '')
                @php
                    $url = Storage::url($info->banner_image);
                @endphp
                    <div class="image-input-wrapper w-125px h-125px manual-image-banner"
                        id="manual-image-banner"
                        style="background-image: url({{ asset($info->banner_image) }});">
                    </div>
                @else
                    <div class="image-input-wrapper w-125px h-125px manual-image-banner"
                        id="manual-image-banner"
                        style="background-image: url({{ asset('userImage/no_Image.jpg') }});">
                    </div>
                @endif
                <label
                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="change" data-bs-toggle="tooltip"
                    title="Change Icon">
                    <i class="bi bi-pencil-fill fs-7"></i>
                    <input type="file" name="banner_image" id="bannerUrl"
                        accept=".png, .jpg, .jpeg" />
                </label>

                <span
                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                    title="Cancel avatar">
                    <i class="bi bi-x fs-2"></i>
                </span>
                <span
                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                    title="Remove avatar1">
                    <i class="bi bi-x fs-2" id="banner_remove_logo"></i>
                </span>
            </div>
        </div>
        <div class=" mb-7">
            <div class="fv-row">
                <label class="d-block fw-bold fs-6 mb-5">Image</label>
                <div class="form-text">
                    Allowed file types: png, jpg,
                    jpeg.
                </div>
            </div>
            <input id="image_remove_image" type="hidden" name="image_remove_image" value="yes">
            <div class="image-input image-input-outline manual-image" data-kt-image-input="true"
                style="background-image: url({{ asset('userImage/no_Image.jpg') }})">
                @if ($info->image ?? '')
                @php

                @endphp
                    <div class="image-input-wrapper w-125px h-125px manual-image"
                        id="manual-image"
                        style="background-image: url({{ asset($info->image )}});">
                    </div>
                @else
                    <div class="image-input-wrapper w-125px h-125px manual-image"
                        id="manual-image"
                        style="background-image: url({{ asset('userImage/no_Image.jpg') }});">
                    </div>
                @endif
                <label
                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="change" data-bs-toggle="tooltip"
                    title="Change Icon">
                    <i class="bi bi-pencil-fill fs-7"></i>
                    <input type="file" name="avatar" id="readUrl"
                        accept=".png, .jpg, .jpeg" />
                </label>

                <span
                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                    title="Cancel avatar">
                    <i class="bi bi-x fs-2"></i>
                </span>
                <span
                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                    title="Remove avatar1">
                    <i class="bi bi-x fs-2" id="avatar_remove_logo"></i>
                </span>
            </div>
        </div>
        <div class=" mb-7">
            <div class="fv-row">
                <label class="d-block fw-bold fs-6 mb-5">Icon</label>
                <div class="form-text">
                    Allowed file types: png, jpg,
                    jpeg.
                </div>
            </div>
            <input id="icon_remove_image" type="hidden" name="icon_remove_image" value="yes">
            <div class="image-input image-input-outline manual-image-icon" data-kt-image-input="true"
                style="background-image: url({{ asset('userImage/no_Image.jpg') }})">
                @if ($info->icon ?? '')
                @php
                    $url = Storage::url($info->icon);
                @endphp
                    <div class="image-input-wrapper w-125px h-125px manual-image-icon"
                        id="manual-image-icon"
                        style="background-image: url({{ asset($info->icon) }});">
                    </div>
                @else
                    <div class="image-input-wrapper w-125px h-125px manual-image-icon"
                        id="manual-image-icon"
                        style="background-image: url({{ asset('userImage/no_Image.jpg') }});">
                    </div>
                @endif
                <label
                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="change" data-bs-toggle="tooltip"
                    title="Change Icon">
                    <i class="bi bi-pencil-fill fs-7"></i>
                    <input type="file" name="icon" id="readUrlicon"
                        accept=".png, .jpg, .jpeg" />
                </label>

                <span
                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                    title="Cancel icon">
                    <i class="bi bi-x fs-2"></i>
                </span>
                <span
                    class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                    data-kt-image-input-action="remove" data-bs-toggle="tooltip"
                    title="Remove avatar1">
                    <i class="bi bi-x fs-2" id="icon_remove_logo"></i>
                </span>
            </div>
        </div>
       
        <div class="mb-7 mt-4">
            <label class="fw-bold fs-6 mb-2">Sorting Order</label>
            <input type="text" name="sorting_order" class="form-control numberonly form-control-solid mb-3 mb-lg-0"
                placeholder="Sorting Order" value="{{ $info->sorting_order ?? '' }}" min="1" />
        </div>
        
    </div>
</div>