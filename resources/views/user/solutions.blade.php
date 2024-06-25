@extends('layouts.app')
@section('content')
<section class="page-section">
    <div class="page-cover">
        <img src="{{ asset($about->cover_path) }}" alt="">
        <div class="container-fluid">
            <div class="title">
                <h5><span>Our </span> Solutions</h5>
            </div>
        </div>
        <div class="overlay"></div>
    </div>
</section>

<section class="item-section">
    <div class="container-fluid">
        @if ($solutions->isEmpty())
            <div class="alert alert-info my-4" role="alert">
                No services available at the moment.
            </div>
        @else
            @foreach ($solutions as $index => $solution)
                <div class="row border-bottom py-4 px-2 align-items-center justify-content-center">
                    @if ($index % 2 == 0)
                        <div class="col-md-4 item-image">
                            <img src="{{ $solution->thumbnail }}" alt="">
                        </div>
                        <div class="col-md-8 item-text">
                            <h3>{{ $solution->title }}</h3>
                            <p>{{ $solution->description }}</p>
                        </div>
                    @else
                        <div class="col-md-8 order-md-1 item-text">
                            <h3 style="text-align: right">{{ $solution->title }}</h3>
                            <p style="text-align: right">{{ $solution->description }}</p>
                        </div>
                        <div class="col-md-4 order-md-2 item-image">
                            <img src="{{ $solution->thumbnail }}" alt="">
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</section>
@endsection

@section('scripts')

@endsection