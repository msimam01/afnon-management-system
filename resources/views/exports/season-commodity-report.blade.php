<table>
    <thead>
        <tr>
            <th>Commodity</th>
            <th>Category</th>
            <th>Unit</th>
            <th>Allocated</th>
            <th>Distributed</th>
            <th>Remaining</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($commodities as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->category }}</td>
                <td>{{ $item->unit }}</td>
                <td>{{ $item->allocated }}</td>
                <td>{{ $item->distributed }}</td>
                <td>{{ $item->remaining }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
