@extends('layouts.app')
@section('content')
<section class="carousel-section">
    <div class="cover">
        <img src="{{ asset($about->cover_path) }}" alt="cover-photo" class="">
    </div>
    <div id="contentCarousel" class="carousel slide" data-bs-ride="carousel">
        <div data-aos="fade-up" class="carousel-inner">
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
        <div data-aos="fade-up" class="header-title">
            <h5><span>About </span> Us</h5>
        </div>
        <div class="row align-items-center pt-2 pb-5">
            <div class="col-md-3 col-sm-6 col-12 mb-3 mb-md-0 d-flex justify-content-center align-items-center">
                <img data-aos="fade-up" src="{{ asset($about->thumbnail) }}" alt="ceo" class=" image-thumbnail">
            </div>
            <div class="col-md-9 col-sm-6 col-12">
                <div data-aos="fade-up" class="details pt-4 pt-md-0">
                    <h3>{{ $about->name }}</h3>
                    <h5>{{ $about->position }}</h5>
                    <p>{{ $about->description }}</p>
                </div>
            </div>
        </div> 
        
        <div class="container-fluid">
            <div data-aos="fade-up" class="header-title">
                <h5><span>Organizational </span>Chart</h5>
            </div>
            <div data-aos="fade-up" class="text-center">
                <button class="btn btn-primary mb-3" onclick="chart.fit()">Fit to the screen</button>
            </div>
            <div data-aos="fade-up" class="chart-container bg-secondary border-r bg-opacity-25"></div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://d3js.org/d3.v7.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/d3-org-chart@3.0.1"></script>
<script src="https://cdn.jsdelivr.net/npm/d3-flextree@2.1.2/build/d3-flextree.js"></script>

<script>
    var chart = null;

        const data = {!! json_encode($members) !!};
        chart = new d3.OrgChart()
        .nodeHeight((d) => 85 + 25)
        .nodeWidth((d) => 220 + 2)
        .childrenMargin((d) => 50)
        .compactMarginBetween((d) => 35)
        .compactMarginPair((d) => 30)
        .neighbourMargin((a, b) => 20)
        .nodeContent(function (d, i, arr, state) {
            const color = '#FFFFFF';
            const imageDiffVert = 25 + 2;
            return `
            <div style='width:${d.width}px;height:${d.height}px;padding-top:${imageDiffVert - 2}px;padding-left:1px;padding-right:1px'>
                <div style="font-family: 'Inter', sans-serif;background-color:${color};  margin-left:-1px;width:${d.width - 2}px;height:${d.height - imageDiffVert}px;border-radius:10px;border: 1px solid #E4E2E9">
                <div style="display:flex;justify-content:flex-end;margin-top:5px;margin-right:8px">#${i + 1}</div>
                <div style="background-color:${color};margin-top:${-imageDiffVert - 20}px;margin-left:${15}px;border-radius:100px;width:50px;height:50px;" ></div>
                <div style="margin-top:${-imageDiffVert - 20}px;">
                    <img src="${d.data.image}" style="margin-left:${20}px;border-radius:100px;width:40px;height:40px;" />
                </div>
                <div style="font-size:15px;color:#08011E;margin-left:20px;margin-top:10px">  ${d.data.name} </div>
                <div style="color:#716E7B;margin-left:20px;margin-top:3px;font-size:10px;"> ${d.data.position} </div>
                </div>
            </div>
            `;
        })
        .container('.chart-container')
        .data(data)
        .render()
        .fit();
  </script>
@endsection
