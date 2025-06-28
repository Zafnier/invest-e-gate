@php
    $type = App\Constants\GlobalConst::SETUP_PAGE;
    $menues = DB::table('setup_pages')
            ->where('status', 1)
            ->where('type', Str::slug($type))
            ->get();
@endphp
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Header
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<header class="header-section">
    <div class="header">
        <div class="header-bottom-area">
            <div class="container">
                <div class="header-menu-content">
                    <nav class="navbar navbar-expand-lg p-0">
                        <a class="site-logo site-title" href="{{ setRoute('index') }}">
                            <img src="{{ get_logo($basic_settings) }}"  data-white_img="{{ get_logo($basic_settings,'white') }}"
                            data-dark_img="{{ get_logo($basic_settings,'dark') }}"
                                alt="site-logo">
                        </a>
                        <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="fas fa-bars"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav main-menu ms-auto me-auto">
                                @php
                                    $current_url = URL::current();
                                @endphp
                                @foreach ($menues as $item)
                                    @php
                                        $title = json_decode($item->title);
                                    @endphp
                                    <li><a href="{{ url($item->url) }}" class="@if ($current_url == url($item->url)) active @endif">{{ __($title->title) }}</a></li>
                                @endforeach


                            </ul>
                            <select name="lang_switch" id="language-select" class="language-select nice-select">
                                <option value="en" @if (app()->currentLocale() == language_const()::NOT_REMOVABLE) selected @endif>English</option>
                                @foreach ($__languages->where("code","!=",language_const()::NOT_REMOVABLE) as $key => $item)
                                    <option value="{{ $item->code }}" @if (app()->currentLocale() == $item->code) selected @endif>{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @auth
                            <div class="header-action">
                                <a href="{{ setRoute('user.dashboard') }}" class="btn--base">{{ __('Dashboard') }}</a>
                            </div>
                            @else
                            <div class="header-action">
                                <a href="{{setRoute('user.login') }}" class="btn--base">{{ __('Join the event') }}</a>
                            </div>
                            @endauth

                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</header>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Header
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
