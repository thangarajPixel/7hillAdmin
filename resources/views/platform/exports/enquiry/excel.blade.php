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
            <th>Added Date</th>
            <th>Name</th>
            <th>Product</th>
            <th>Email</th>
            <th>Mobile Number</th>
            <th>Company Name</th>
            <th>city </th>
            
        </tr>
    </thead>
    <tbody>
        @if( isset( $list ) && !empty($list))
            @foreach ($list as $item)
            <tr>
                <td>{{ $item->created_at }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->mobile }}</td>
                <td>{{ $item->company_name }}</td>
                <td>{{ $item->city }}</td>
                
            </tr>
            @endforeach
        @endif
    </tbody>
</table>