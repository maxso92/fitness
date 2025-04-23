@section('title')
    <title>{{$title}}</title>
@endsection

@section('title')
    <title>{{$title}} {{$actions}} </title>
@endsection


<div class="toolbar px-3 px-lg-6 py-3">
    <div class="position-relative container-fluid px-0">
        <div class="row align-items-center position-relative">
            <div class="col-md-8">

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"> <a href="{{route($route)}}"><i class="bi-arrow-left"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{route($route)}}">{{$title}}</a></li>

                     </ol>
                </nav>
            </div>
        </div>
    </div>
</div>




