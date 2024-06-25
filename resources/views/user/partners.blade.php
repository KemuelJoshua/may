@extends('layouts.app')
@section('content')
<section class="page-section">
    <div class="page-cover">
        <img src="{{ asset($about->cover_path) }}" alt="">
        <div class="container-fluid">
            <div class="title">
                <h5><span>Our </span> Partners</h5>
            </div>
        </div>
        <div class="overlay"></div>
    </div>
</section>

<section class="item-section my-5">
    <div class="container-fluid">
        @if ($partners->isEmpty())
            <div class="alert alert-info my-4" role="alert">
                No solutions available at the moment.
            </div>
        @else
            @foreach ($partners as $item)
            <div class="row">
                <div class="col-md-4 col-sm-6 logo-item">
                    <img src="{{ $item->thumbnail }}" alt="partner" class="logo-thumbnail">
                </div>
            </div>
            @endforeach
        @endif
    </div>
</section>
@endsection
