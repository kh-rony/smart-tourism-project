<footer class="footer">
    <div class="container">
        <nav>
            <ul>
                <li>
                    <a href="{{ route('home') }}"> Travel Mate</a>
                </li>
                <li>
                    <a href="#">
                        About Us
                    </a>
                </li>
                <li>
                    <a href="#">
                        Blog
                    </a>
                </li>
            </ul>
        </nav>
        <div class="copyright">
            &copy;
            <script>
                document.write(new Date().getFullYear())
            </script>
            <a href="{{ route('home') }}">{{ config('app.name') }}</a>.
        </div>
    </div>
</footer>