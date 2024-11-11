<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/lux/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('assets/css/print.css')}}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Timeline</title>
    <!-- Include Bootstrap JavaScript and its dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <style>
        /* Flexbox layout for sticky footer */
        body, html {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .content {
            flex: 1;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            background-color: white;
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container">
            <a class="navbar-brand" href="/">TIMELINE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarColor01">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                </ul>
                <div class="nav-item dropdown">
                    <button type="button" class="btn btn-light nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img src="https://www.ordinatrix.com/wp-content/uploads/2018/06/user.png" style="max-height: 30px; max-width: 30px;">
                    </button>
                    @auth
                    <div class="dropdown-menu">
                        <a class="dropdown-item" disabled><strong style="font-size: 17px">Cześć {{$user}}!</strong></a>
                        <a class="dropdown-item" href="/profile">Zmiana hasła</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-muted" href="/logout">Wylogowanie</a>
                    </div>
                    @else
                    <div class="dropdown-menu">
                        <a class="dropdown-item" disabled><strong style="font-size: 17px">Niezalogowany</strong></a>
                        <a class="dropdown-item" href="/login">Logowanie</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-muted" href="/register">Uwtórz Konto</a>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    <!-- NAVBAR -->

    <!-- MAIN CONTENT -->
    <div class="container">
        @if(session('status'))
        <div class="alert alert-dismissible alert-info">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            {{ session('status') }}
        </div>
        @endif

        @if($errors->any())
            <div class="alert alert-dismissible alert-danger">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="container">
            <br></br>
            <h4>Dystrybucja wydarzeń<h4>
            <div class="progress">
                @foreach($categories as $category)
                    <div class="progress-bar progress-bar-striped progress-bar-animated {{$color_map[$category->color]}}" role="progressbar" style="width: {{$totalEventsCount > 0 ? ($category->events_count / $totalEventsCount) * 100 : 0}}%;" aria-valuemin="0" aria-valuemax="100"></div>
                @endforeach
            </div>
        </div>
        <br></br>
        <div class="d-flex justify-content-between align-items-center">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <button type="button" class="btn btn-primary">Rok</button>
                <div class="btn-group" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                    <a class="dropdown-item" href="/">Wszystkie</a>
                    @foreach($years as $year)
                    <a class="dropdown-item" href="/?year={{$year}}">{{$year}}</a>
                    @endforeach
                  </div>
                </div>
            </div>
            @auth
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createEventModal" style="width: 200px;">
                Nowe Wydarzenie
            </button>
            @endauth
        </div>
        <br></br>
        <div class="d-flex justify-content-between align-items-center">
            <div container>
            @foreach($categories as $category)
                <span class="badge rounded-pill {{$color_map[$category->color]}}">{{$category->name}}</span>
                <span class="badge rounded-pill {{$color_map[$category->color]}}">{{$category->events_count}}</span>
            @endforeach
            </div>
            @auth
            <div class="d-flex justify-content-between align-items-right">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal" style="width: 200px;">
                    Nowa Kategoria
                </button>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal" style="width: 200px;">
                    Edytuj Kategorie
                </button>
            </div>
            @endauth
        </div>
        <br></br>
        <!-- Create Event Modal Structure -->
        <div class="modal fade" id="createEventModal" tabindex="-1" aria-labelledby="createEventModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createEventModalLabel">Nowe Wydarzenie</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="/event" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="eventName" class="form-label">Nazwa</label>
                                <input type="text" class="form-control" id="eventName" name="name" minlength="3" maxlength="30" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleSelect1" class="form-label mt-4">Kategoria</label>
                                <select class="form-select" id="eventCategory" name="category_id" required>
                                    @if($categories->isNotEmpty())
                                    @foreach($categories as $category)
                                    <option value="{{ $category['id'] }}">{{$category['name']}}</option>
                                    @endforeach
                                    @else
                                    <option disabled selected>Brak dostępnych kategorii</option>
                                    @endif
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="eventStartDate" class="form-label">Rozpoczęcie</label>
                                <input type="date" class="form-control" id="eventStartDate" name="start_date" min="2000-01-01" max="2040-12-31" required>
                            </div>
                            <div class="mb-3">
                                <label for="eventEndDate" class="form-label">Zakończenie</label>
                                <input type="date" class="form-control" id="eventEndDate" name="end_date" min="2000-01-01" max="2040-12-31" required>
                            </div>
                            <div class="mb-3">
                                <label for="eventDescription" class="form-label">Opis</label>
                                <textarea class="form-control" id="eventDescription" name="description" rows="3" minlength="3" maxlength="200"></textarea>
                            </div>
                            <div>
                                <label for="formFile" class="form-label mt-4">Grafika</label>
                                <input class="form-control" type="file" id="formFile" name="image">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Zapisz</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Create Category Modal Structure -->
        <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createEventModalLabel">Nowa Kategoria</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="/category" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="categoryName" class="form-label">Nazwa</label>
                                <input type="text" class="form-control" id="categoryName" name="name" minlength="3" maxlength="20" required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleSelect1" class="form-label mt-4">Kolor</label>
                                <select class="form-select" id="categoryColor" name="color" required>
                                    @foreach($availableColors as $color)
                                    <option>{{$color}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Zapisz</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Category Modal Structure -->
        <div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="categoryModalLabel">Edytuj Kategorie</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="exampleSelect1" class="form-label mt-4">Kategoria</label>
                                <select id="categorySelect" class="form-select" name="category" required>
                                    @if($categories->isNotEmpty())
                                        @foreach($categories as $category)
                                        <option value="{{ $category['id'] }}">{{$category->name}}</option>
                                        @endforeach
                                    @else
                                        <option disabled selected>Brak dostępnych kategorii</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            @if($categories->isNotEmpty())
                                <button id="editCategoryBtn" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category['id'] }}">Edytuj</button>
                                <button id="deleteCategoryBtn" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteCategoryModal{{ $category['id'] }}">Usuń</button>
                            @endif
                        </div>
                </div>
            </div>
        </div>
        @if($categories->isNotEmpty())
        @foreach($categories as $category)
        <!-- Edit Category Modal Structure -->
        <div class="modal fade" id="editCategoryModal{{ $category['id'] }}" tabindex="-1" aria-labelledby="editModalLabel{{ $category['id'] }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="/category/{{$category->id}}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editCategoryModalLabel{{ $category['id'] }}">Edytuj kategorię</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nazwa</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ $category['name'] }}" minlength="3" maxlength="20" required>
                            </div>
                            <label for="exampleSelect1" class="form-label mt-4">Kolor</label>
                                <select class="form-select" id="color" name="color" required>
                                    <option>{{$category->color}}</option>
                                    @foreach($availableColors as $color)
                                    <option>{{$color}}</option>
                                    @endforeach
                                </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Zapisz</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Delete Category Modal Structure -->
        <div class="modal fade" id="deleteCategoryModal{{ $category['id'] }}" tabindex="-1" aria-labelledby="deleteCategoryModalLabel{{ $category['id'] }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="/category/{{$category->id}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteCategoryModalLabel{{ $category['id'] }}">Usuń kategorię</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Czy na pewno chcesz usunąć kategorię <strong>{{ $category['name'] }}</strong>?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Nie</button>
                            <button type="submit" class="btn btn-danger">Tak</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
        @endif
        <div class="container">
            <div class="row">
                @foreach($events as $event)
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="card-title">{{$event['name']}}</h5>
                            <span class="badge rounded-pill {{ isset($event->category) && isset($color_map[$event->category->color]) ? $color_map[$event->category->color] : 'bg-secondary' }}">
                                {{ isset($event->category) ? $event->category->name : 'Brak kategorii' }}
                            </span>
                        </div>
                        <img src="{{ asset('storage/' . $event->image) }}" alt="Event Image" class="card-img-top">
                        <div class="card-body">
                            <p class="card-text">{{$event['description']}}</p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Rozpoczęcie: {{$event['start_date']}}</strong></li>
                            <li class="list-group-item"><strong>Zakończenie: {{$event['end_date']}}</strong></li>
                            @auth
                            <li class="list-group-item"><small>Utworzono {{$event->created_at}} przez {{$event->parentUser->email}}</small></li>
                            <li class="list-group-item"><small>Ostatnia modyfikacja {{$event->created_at}} przez {{$event->contributionUser->email}}</small></li>
                            @endauth
                        </ul>
                        <div class="card-footer text-muted">
                            @auth
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $event['id'] }}">Edytuj</button>
                            <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $event['id'] }}">Usuń</button>
                            @endauth
                        </div>
                    </div>
                </div>
                <!-- Edit Event Modal Structure -->
                <div class="modal fade" id="editModal{{ $event['id'] }}" tabindex="-1" aria-labelledby="editModalLabel{{ $event['id'] }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="/event/{{$event->id}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editModalLabel{{ $event['id'] }}">Edytuj Wydarzenie</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nazwa</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ $event['name'] }}" minlength="3" maxlength="30" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="exampleSelect1" class="form-label mt-4">Kategoria</label>
                                        <select class="form-select" id="eventCategory" name="category_id" required>
                                            @foreach($categories as $category)
                                            <option value="{{ $category['id'] }}" {{ $category->id == $event->category_id ? 'selected' : '' }}>{{$category['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="eventStartDate" class="form-label">Rozpoczęcie</label>
                                        <input type="date" class="form-control" id="eventStartDate" name="start_date" value="{{ $event['start_date'] }}" min="2000-01-01" max="2040-12-31" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="eventEndDate" class="form-label">Zakończenie</label>
                                        <input type="date" class="form-control" id="eventEndDate" name="end_date" value="{{ $event['end_date'] }}" min="2000-01-01" max="2040-12-31" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="eventDescription" class="form-label">Opis</label>
                                        <textarea class="form-control" id="eventDescription" name="description" rows="3" minlength="3" maxlength="200" required>{{ $event['description'] }}</textarea>
                                    </div>
                                    <div>
                                        <label class="form-label mt-4">Aktualna Grafika</label>
                                        <img src="{{ asset('storage/' . $event->image) }}" alt="Event Image" class="card-img-top">
                                    </div>
                                    <div>
                                        <label for="formFile" class="form-label mt-4">Opcjonalnie załaduj inny obraz</label>
                                        <input class="form-control" type="file" id="formFile" name="image">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Zapisz</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Delete Event Modal Structure -->
                <div class="modal fade" id="deleteModal{{ $event['id'] }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $event['id'] }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="/event/{{$event->id}}" method="POST">
                                @csrf
                                @method('DELETE')
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteModalLabel{{ $event['id'] }}">Usuń wydarzenie</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Czy na pewno chcesz usunąć wydarzenie <strong>{{ $event['name'] }}</strong>?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Nie</button>
                                    <button type="submit" class="btn btn-danger">Tak</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- MAIN CONTENT -->

    <!-- FOOTER -->
    <div class="container-fluid">
        <hr class="my-4">
        <footer class="footer py-3">
            <span class="text-muted">2024 | Marcin Krawiec</span>
        </footer>
    </div>
    <!-- FOOTER -->
    <script src="{{asset('assets/js/modal.js')}}"></script>
</body>
</html>
