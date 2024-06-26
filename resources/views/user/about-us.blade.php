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
        
        <div id="chart-container" style="width: 100%; height: 100vh;">
            <svg></svg>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://d3js.org/d3.v6.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const data = [
        { id: 1, name: "CEO", position: "Chief Executive Officer", image: "path/to/ceo_image.png", parent: null },
        { id: 2, name: "CTO", position: "Chief Technology Officer", image: "path/to/cto_image.png", parent: 1 },
        { id: 3, name: "Dev Manager", position: "Development Manager", image: "path/to/dev_manager_image.png", parent: 2 },
        { id: 4, name: "QA Manager", position: "QA Manager", image: "path/to/qa_manager_image.png", parent: 2 },
        { id: 5, name: "CFO", position: "Chief Financial Officer", image: "path/to/cfo_image.png", parent: 1 },
        { id: 6, name: "Accountant", position: "Accountant", image: "path/to/accountant_image.png", parent: 5 },
        { id: 7, name: "Financial Analyst", position: "Financial Analyst", image: "path/to/financial_analyst_image.png", parent: 5 },
        { id: 8, name: "COO", position: "Chief Operating Officer", image: "path/to/coo_image.png", parent: 1 },
        { id: 9, name: "Operations Manager", position: "Operations Manager", image: "path/to/operations_manager_image.png", parent: 8 },
        { id: 10, name: "Logistics Manager", position: "Logistics Manager", image: "path/to/logistics_manager_image.png", parent: 8 }
    ];

    const stratify = d3.stratify()
        .id(d => d.id)
        .parentId(d => d.parent);

    const root = stratify(data)
        .sort((a, b) => (a.height - b.height) || a.id - b.id);

    const chartContainer = document.getElementById('chart-container');
    const svg = d3.select('svg');

    const render = () => {
        const width = chartContainer.clientWidth;
        const height = chartContainer.clientHeight;
        const margin = { top: 40, right: 120, bottom: 40, left: 120 };
        const innerWidth = width - margin.left - margin.right;
        const innerHeight = height - margin.top - margin.bottom;

        svg.attr('width', width).attr('height', height);
        svg.selectAll('*').remove(); // Clear previous content

        const g = svg.append('g')
            .attr('transform', `translate(${margin.left},${margin.top})`);

        const treemap = d3.tree().size([innerWidth, innerHeight]);

        const nodes = treemap(root);

        const link = g.selectAll('.link')
            .data(nodes.links())
            .enter().append('path')
            .attr('class', 'link')
            .attr('d', d3.linkVertical()
                .x(d => d.x)
                .y(d => d.y)
            );

        const node = g.selectAll('.node')
            .data(nodes.descendants())
            .enter().append('g')
            .attr('class', d => 'node' + (d.children ? ' node--internal' : ' node--leaf'))
            .attr('transform', d => `translate(${d.x},${d.y})`);

        node.append('rect')
            .attr('width', 140)
            .attr('height', 80)
            .attr('x', -70)
            .attr('y', -40)
            .attr('rx', 10)
            .attr('ry', 10);

        node.append('image')
            .attr('xlink:href', d => d.data.data.image)
            .attr('x', -30)
            .attr('y', -30)
            .attr('width', 60)
            .attr('height', 60);

        node.append('text')
            .attr('dy', -40)
            .attr('y', 15)
            .attr('text-anchor', 'middle')
            .text(d => d.data.data.name);

        node.append('text')
            .attr('dy', 20)
            .attr('y', 35)
            .attr('text-anchor', 'middle')
            .text(d => d.data.data.position);
    };

    render();

    window.addEventListener('resize', render);
});


</script>
@endsection
