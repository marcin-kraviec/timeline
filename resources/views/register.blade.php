<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/lux/bootstrap.min.css">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <!-- Include Bootstrap JavaScript and its dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <style>
        /* Flexbox layout for sticky footer */
        body, html {
            height: 100%;
            display: flex;
            flex-direction: column;
            margin: 0;
        }
        .content {
            flex: 1;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            background-color: white;
            padding: 1rem 0;
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
                        <a class="dropdown-item" disabled><strong style="font-size: 17px">Zalogowany</strong></a>
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
    <div class="container content" style="max-width: 500px;">
        @if(session('status'))
        <div class="alert alert-dismissible alert-danger">
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
        <form action="/register" method="post">
            @csrf
            <div class="form-group">
                <label class="form-label mt-4">Imię</label>
                <input type="text" name='name' class="form-control" id="name" placeholder="Wprowadź imię" minlength="3" maxlength="20" required>
            </div>
            <div class="form-group">
                <label class="form-label mt-4">Email</label>
                <input type="email" name='email' class="form-control" id="email" placeholder="Wprowadź email" required>
            </div>
            <div class="form-group">
                <label class="form-label mt-4">Hasło</label>
                <input type="password" name='password' class="form-control" id="password" placeholder="Wprowadź hasło" minlength="12" maxlength="100" required>
            </div>
            <div class="form-group">
                <label class="form-label mt-4">Potwierdź hasło</label>
                <input type="password" name='password_confirmation' class="form-control" id="confirm_password" placeholder="Potwierdź hasło" minlength="12" maxlength="100" required>
            </div>
            <br/>
            <button type="submit" class="btn btn-primary" style="width: 100%">Zarejestruj się</button>
            <br/><br/>
            <div style="text-align: center;">
                <a href="/login">Logowanie do istniejącego konta</a>
            </div>
        </form>
    </div>
    <!-- MAIN CONTENT -->

    <!-- FOOTER -->
    <div class="container-fluid">
    <hr class="my-4">
    <footer class="footer">
        <span class="text-muted">2024 | Marcin Krawiec</span>
        <br></br>
    </footer>
    </div>
    <!-- FOOTER -->
</body>
</html>
