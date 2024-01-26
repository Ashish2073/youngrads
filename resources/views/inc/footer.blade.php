<footer class="footer">
    <div class="container">
        <div class="row w-100">
            <div class="col-12 col-md-6 px-0 text-left">
                <p>Copyright ©{{ date('Y') }} <a href="#">{{ config('app.name') }}</a></p>
            </div>
            <div class="col-12 col-md-6 px-0 text-md-right text-left">
                <a class="ml-0" href="{{ route('terms') }}">Terms of use</a>
                <a  href="{{ route('privacy_policy') }}">Privacy Policy</a>
            </div>
        </div>
    </div>
</footer>