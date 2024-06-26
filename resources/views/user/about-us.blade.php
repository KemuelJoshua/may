@extends('layouts.app')
@section('content')
<section class="carousel-section">
    <div class="cover">
        <img src="{{ asset($about->cover_path) }}" alt="cover-photo" class="">
    </div>
    <div id="contentCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach ($carousel as $index => $item)
                <div class="carousel-item h-100 {{ $index === 0 ? 'active' : '' }}">
                    <div class="h-100 d-flex justify-content-center align-items-center">
                        <div class="text-center" style="max-width: 600px">
                            <img src="{{ asset($item['thumbnail']) }}" alt="cover">
                            <h3 class="title pt-4">{{ $item['title'] }}</h3>
                            <p class="description">{{ $item['description'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#contentCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#contentCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

<section class="organization-chart bg-white">
    <div class="container-fluid">
        <div class="header-title">
            <h5><span>About </span> Us</h5>
        </div>
        <div class="row align-items-center pt-2 pb-5">
            <div class="col-md-3 col-sm-6 col-12 mb-3 mb-md-0 d-flex justify-content-center align-items-center">
                <img src="{{ $about->thumbnail }}" alt="ceo" class=" image-thumbnail">
            </div>
            <div class="col-md-9 col-sm-6 col-12">
                <div class="details pt-4 pt-md-0">
                    <h3>{{ $about->name }}</h3>
                    <h5>{{ $about->position }}</h5>
                    <p>{{ $about->description }}</p>
                </div>
            </div>
        </div> 
    </div>
</section>
@endsection

@section('scripts')
@endsection
