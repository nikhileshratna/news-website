<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Table</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .article-image img {
            width: 40%;
            height: auto;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <a href="/">
                    <img src="{{ $channel['image']['url'] }}" alt="">
                </a>
            </div>
        </div>
        <div class="column">
            <div class="col-12 text-center">
                <h1 class="mb-3">News Table</h1>
                <button onclick="window.location.href='/'" class="btn btn-primary mb-3">Go To Homepage</button>

            </div>
        </div>
        <form action="/search" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="query" class="form-control" placeholder="Search News">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <div class="row">
            
            <div class="col-12">
                <div>
                    <h1>{!! $channel['title'] !!}</h1>
                    <div>
                        <div class="d-flex flex-column w-25 mx-auto gap-2">
                            <button onclick="window.location.href='/sort?column=title&direction=asc'" class="btn btn-primary">Sort by Title (ASC)</button>
                        </div>
                        
                        @foreach ($data as $index => $article)
                            <div class="d-flex flex-column">
                                <div class="article-image">
                                    <h5>{{ $index + 1 }})</h5>
                                    @if($article['description'] !== "")
                                        <img src="{{ $article['enclosure']['@url'] }}" alt="{{ $article['title'] }}">
                                    @else
                                        <h3>No Image</h3>
                                    @endif
                                </div>
                                <div>{{ $article['title'] }}</div>
                                <div><a href="{{ $article['link'] }}">Read More...</a></div>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('searchInput');
            const newsTable = new simpleDatatables.DataTable('#newsTable');

            searchInput.addEventListener('input', _.debounce(() => {
                const searchText = searchInput.value.toLowerCase();
                newsTable.search(searchText).draw();
            }, 300));
        });
    </script>
</body>
</html>
