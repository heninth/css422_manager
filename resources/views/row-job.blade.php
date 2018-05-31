<tr>
    <th scope="row">{{$job_id}}</th>
    <td>{{$algorithm}}</td>
    <td>{{$crack}}</td>
    <td>{{$submit}}</td>
    <td>{{$finished}}</td>
    <td>{{$status}}</td>
    <td><button type="button" class="btn btn-primary" onclick="window.location ='{{route('result-job',['listResult' => $job_id])}}'">View Result</button></td>
    <td><button type="button" class="btn btn-danger" onclick="window.location ='{{route('delete-job',['listResult' => $job_id])}}'">Delete</button></td>
</tr>