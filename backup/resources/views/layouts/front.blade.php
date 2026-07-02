<!DOCTYPE html>
<html lang="en">

{{-- Include Head --}}
@include('common.fronthead')

<body id="page-top"  class="tt-magic-cursor">

    <div class="page-wrapper">


        @include('common.frontheader')


        @yield('content')

        @include('common.frontfooter')



        @include('common.frontfooterjs')

        @yield('scripts')
    </div>
</body>

</html>