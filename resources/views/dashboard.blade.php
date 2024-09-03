<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .selected {
            background-color: lightgray !important;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-2">
        <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Dashboard</a>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <main role="main" class="col-md-12 ml-sm-auto col-lg-12 pt-3 px-4">
                <form method="POST" action="{{ route('filterData') }}" id="filterForm">
                    @csrf
                    @method('POST')
                    <input type="string" name="page" id="page" value="{{ request()->page }}" class="d-none">
                    <input type="string" name="user_ids" id="user_ids" value="" class="d-none">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="input-group mb-3 flex-nowrap">
                                <span class="input-group-text" id="dateFrom">От: </span>
                                <input type="date" class="form-control" name="date_from"
                                    value="{{ request()->date_from }}">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="input-group mb-3 flex-nowrap">
                                <span class="input-group-text" id="dateTo">До: </span>
                                <input type="date" class="form-control" name="date_to"
                                    value="{{ request()->date_to }}">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <select class="form-select" aria-label="Select Domain" name="domain">
                                <option value="">Домейн</option>
                                <option value="asiandomain.com"
                                    {{ request()->domain == 'asiandomain.com' ? 'selected' : '' }}>asiandomain.com
                                </option>
                                <option value="bulgariandomain.com"
                                    {{ request()->domain == 'bulgariandomain.com' ? 'selected' : '' }}>
                                    bulgariandomain.com
                                </option>
                                <option value="turkishdomain.com"
                                    {{ request()->domain == 'turkishdomain.com' ? 'selected' : '' }}>turkishdomain.com
                                </option>
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <select class="form-select" aria-label="Select Domain" name="count">
                                <option value="50" selected> 50 бр.</option>
                                <option value="100">100 бр.</option>
                                <option value="250">250 бр.</option>
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-check pt-2">
                                <input class="form-check-input" type="checkbox" id="checkRBL"
                                    {{ request()->checkRBL == 'on' ? 'checked' : '' }} name="checkRBL">
                                <label class="form-check-label" for="checkRBL">
                                    покажи RBL
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="row">
                                <div class="col-sm-9" style="padding-right: 0;">
                                    <input type="text" name="ip" value=" {{ request()->ip }}"
                                        style="border-top-right-radius: 0; border-bottom-right-radius: 0"
                                        aria-label="First name" class="form-control border border-end-0">
                                </div>
                                <div class="col-sm-3 p-0">
                                    <select class="form-select" aria-label="Select Domain"
                                        style=" border-top-left-radius: 0; border-bottom-left-radius: 0"
                                        class="col-sm-1  border border-start-0 rounded-0" name="mask">
                                        <option selected></option>
                                        <option {{ request()->mask == '/24' ? 'selected' : '' }} value="/24">/24
                                        </option>
                                        <option {{ request()->mask == '/22' ? 'selected' : '' }} value="/22">/22
                                        </option>
                                        <option {{ request()->mask == '/20' ? 'selected' : '' }} value="/20">/20
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1">
                            <div class="form-check pt-2">
                                <input class="form-check-input" type="checkbox" {{ request()->hasBlocked == 'on' ? 'checked' : '' }} name="hasBlocked"
                                    id="blocked">
                                <label class="form-check-label" for="blocked">
                                    блокирани
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="range" class="form-range" id="vowels" min="0"
                                            max="100" value="40">
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="vowels" class="form-check-label">
                                            <<span id="vowelsPlaceholder">/</span> гласни
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <input type="range" class="form-range" id="length" min="6"
                                            max="30" value="10">
                                    </div>
                                    <div class="col-sm-4">
                                        <label for="length" class="form-check-label">
                                            <<span id="lengthPlaceholder">/</span> символа
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-primary">Филтрирай</button>
                        </div>
                    </div>
                </form>
                <div class="table-responsive mt-5">
                    @if (!request()->hasBlocked)
                        <a href="" id="block_action">Блокирай избрани</a>
                    @endif
                    <table class="table table-bordered" id="data">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>ip </th>
                                <th>username</th>
                                <th>recovery</th>
                                <th>created</th>
                                <th>last_login</th>
                                <th>diff</th>
                                @if (request()->checkRBL == 'on')
                                    <th>rbl</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <th><input type="checkbox" class="user_checkbox" value="{{ $user->id }}"></th>
                                    <td class="d-flex justify-content-between">
                                        <span>{{ $user->preferences->ip }}</span>
                                    </td>
                                    <td class="user_username">{{ $user->email }}</td>
                                    <td>{{ $user->preferences->email ?? $user->preferences->phone }}</td>
                                    <td>{{ $user->created_at }}</td>
                                    <td>{{ $user->last_login }}</td>
                                    <td>{{ $user->getLoginDiff() }}</td>
                                    @if (request()->checkRBL == 'on')
                                        <td>{{ $user->checkIp() }} </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $users->links('pagination') }}
            </main>
        </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script>
        window.jQuery || document.write('<script src="../../assets/js/vendor/jquery-slim.min.js"><\/script>')
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
    <script>
        $('#selectAll').on('click', function(event) {
            $('.user_checkbox').each(function(index, item) {
                item.checked = event.currentTarget.checked;
            })
        })

        $('#vowels').on('input', function(event) {
            const percentage_treshold = event.currentTarget.value;
            const Vowels = "aAeEiIoOuU";
            $('#vowelsPlaceholder').text(percentage_treshold + '%');
            $('.user_username').each(function(index, item) {
                const username = item.innerText.split('@')[0];
                let vowelsCount = 0;
                for (let i = 0; i < username.length; i++) {
                    if (Vowels.indexOf(username[i]) !== -1) {
                        vowelsCount += 1;
                    }
                }
                const percentage_username = (vowelsCount / username.length) * 100;
                if (percentage_username < percentage_treshold) {
                    $(item).parent().children().each(function(index) {
                        if (index == 0) {
                            $(this).children()[0].checked = !$(this).children()[0].checked;
                        }
                        $(this).toggleClass('selected');
                    });
                }
            })
        });

        $('#length').on('input', function(event) {
            const length_treshold = event.currentTarget.value;
            $('#lengthPlaceholder').text(length_treshold);
            $('.user_username').each(function(index, item) {
                const username = item.innerText.split('@')[0];
                if (username.length < length_treshold) {
                    $(item).parent().children().each(function(index) {
                        if (index == 0) {
                            $(this).children()[0].checked = !$(this).children()[0].checked;
                        }
                        $(this).toggleClass('selected');
                    });
                }
            })
        });

        $('#block_action').on('click', function (event) {
            event.preventDefault();
            let ids = [];
            $('.user_checkbox').each(function () {
                if($(this).is(':checked')){
                    ids.push($(this).val());
                }
            })
            console.log(ids);
            if(ids.length){
                $('#user_ids').val(ids);
                $('#filterForm').submit();
            }
        });

        $('#data td').on('click', function() {
            $(this).parent().children().each(function(index) {
                if (index == 0) {
                    $(this).children()[0].checked = !$(this).children()[0].checked;
                }
                $(this).toggleClass('selected');
            });
        });

        $('a[data-page]').on('click', function(event) {
            event.preventDefault();
            $page = $(this).data('page');
            $('#page').val($page);
            $('#filterForm').submit();
        })
    </script>
    <!-- Icons -->
</body>

</html>
