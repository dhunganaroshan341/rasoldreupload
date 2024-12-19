{{-- client info --}}


<div class="table-responsive">
    <table class="table bg-white shadow rounded border-0">
        <tbody>
            <tr>
                <th class="txt-dark">Name</th>
                <td>{{ $client->name }}</td>
            </tr>
            <tr>
                <th class="txt-dark">Client Type</th>
                <td>{{ $client->client_type }}</td>
            </tr>
            <tr>
                <th class="txt-dark">Address</th>
                <td>{{ $client->address }}</td>
            </tr>
            <tr>
                <th class="txt-dark">Email</th>
                <td>{{ $client->email }}</td>
            </tr>
            <tr>
                <th class="txt-dark">Phone</th>
                <td>{{ $client->phone }}</td>
            </tr>
            <tr>
                <th class="txt-dark">PAN Number</th>
                <td>{{ $client->pan_no }}</td>
            </tr>
            <tr>
                <th class="txt-dark">Hosting Service</th>
                <td>{{ $client->hosting_service }}</td>
            </tr>
            <tr>
                <th class="txt-dark">Email Service</th>
                <td>{{ $client->email_service }}</td>
            </tr>
        </tbody>
    </table>
</div>
