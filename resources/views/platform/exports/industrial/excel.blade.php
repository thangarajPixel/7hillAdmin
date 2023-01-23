@if( isset( $from ) && $from == 'pdf')
<style>
    table{ border-spacing: 0;width:100%; }
    table th,td {
        border:1px solid;
    }
</style>
@endif
<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Slug</th>
            <th>Parent</th>
            <th>Description</th>
            <th>Meta Title</th>
            <th>Meta Keyword</th>
            <th>Meta Description</th>
            <th>Status</th>
            <th> Added By </th>
            <th> Added At </th>
        </tr>
    </thead>
    <tbody>
        @if( isset( $list ) && !empty($list))
            @foreach ($list as $item)
            <tr>
                <td>{{ $item->title }}</td>
                <td>{{ $item->slug }}</td>
                <td>{{ $item->parent->title ?? '' }}</td>
                <td>{{ $item->description ?? '' }}</td>
                <td>{{ $item->meta_title ?? '' }}</td>
                <td>{{ $item->meta_keyword ?? '' }}</td>
                <td>{{ $item->meta_description ?? '' }}</td>
                <td>{{ $item->status }}</td>
                <td>{{ $item->userInfo->name ?? '' }}</td>
                <td>{{ $item->created_at }}</td>
                
            </tr>
            @endforeach
        @endif
    </tbody>
</table>