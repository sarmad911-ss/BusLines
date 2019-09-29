@extends("pages.template")
@section("content")
    <div class="bus-form">
        <bus-form @if(!empty($bus->id)) :bus-id="{{$bus->id}}" @endif @onSave="onSave"></bus-form>
    </div>
@endsection
@section("js")
    <script src="{{asset("pages/bus/store.js")}}"></script>
@endsection
@section("css")
    <style>
        .bus-form {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            grid-column-gap: 25px;
        }
        .page-title{
            grid-column: span 12;
        }
    </style>
@endsection