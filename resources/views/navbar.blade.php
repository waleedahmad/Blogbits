<nav class="navbar navbar-fixed-top">
    <div class="container">

        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapsable" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">BlogBits</a>
        </div>

        <div class="collapse navbar-collapse" id="collapsable">

            <ul class="nav navbar-nav navbar-right">
            @if(!Auth::check())
                    <li><a href="/login">Login</a></li>
                    <li><a href="/register">Register</a></li>
            @else

                <li>
                    <a href="#" id="sync-data"> <i class="fa fa-tumblr-square" aria-hidden="true"></i> Blog Sync</a>
                </li>

                <li>
                    <a href="#" id="facebook-sync"> <i class="fa fa-facebook" aria-hidden="true"></i> Facebook Sync</a>
                </li>

                    <li>
                        <a href="#" id="pinterest-sync"> <i class="fa fa-pinterest" aria-hidden="true"></i> Pinterest Sync</a>
                    </li>

                <li class="dropdown">
                    <a href="#"
                       class="dropdown-toggle"
                       data-toggle="dropdown"
                       role="button"
                       aria-haspopup="true"
                       aria-expanded="false">
                        {{Auth::user()->name}}
                        <i class="fa fa-user" aria-hidden="true"> </i> </span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#" id="delete-sync" data-name="Tumblr" data-service="tumblr"> <i class="fa fa-tumblr-square" aria-hidden="true"></i> Delete Blog Content</a>
                        </li>

                        <li>
                            <a href="#" id="pinterest-delete-sync" data-name="Pinterest" data-service="pinterest"> <i class="fa fa-pinterest" aria-hidden="true"></i> Delete Pinterest Content</a>
                        </li>

                        <li>
                            <a href="#" id="facebook-delete-sync" data-name="Facebook" data-service="facebook"> <i class="fa fa-facebook" aria-hidden="true"></i> Delete Facebook Content</a>
                        </li>

                        <li class="nav-divider"></li>

                        <li><a href="/config">Settings</a></li>
                        <li><a href="/logout">Logout</a></li>
                    </ul>
                </li>
            @endif
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>