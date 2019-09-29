<form method="POST" action="{{route('storeTripFileAction')}}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" hidden name="trip_id" value="8">
    {{--<input type="hidden" hidden name="id" value="226">--}}
    <input type="file" name="file">
    <input type="submit" value="test">
</form>