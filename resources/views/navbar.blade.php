<nav class="navbar navbar-default">
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

                @if(Request::is('/'))
                        <li>
                            <a href="#" id="post-batch"> <i class="fa fa-paper-plane" aria-hidden="true"></i> Post Batch ({{App\Models\Post::where('type','=','blog')->count()}})</a>
                        </li>

                        <li>
                            <a href="/social"> <i class="fa fa-image" aria-hidden="true"></i> Social Content</a>
                        </li>

                        <li>
                            <a href="/tumblr"> <i class="fa fa-image" aria-hidden="true"></i> Tumblr Feed</a>
                        </li>
                @endif

                @if(Request::is('social'))
                    <li>
                        <a href="/"> <i class="fa fa-image" aria-hidden="true"></i> Blog Content</a>
                    </li>

                    <li>
                        <a href="/tumblr"> <i class="fa fa-image" aria-hidden="true"></i> Tumblr Feed</a>
                    </li>
                @endif

                @if(Request::is('tumblr'))
                    <li>
                        <a href="/"> <i class="fa fa-image" aria-hidden="true"></i> Blog Content</a>
                    </li>

                    <li>
                        <a href="/social"> <i class="fa fa-image" aria-hidden="true"></i> Social Content</a>
                    </li>
                @endif



                <li>
                    <a href="#" id="sync-data"> <i class="fa fa-refresh" aria-hidden="true"></i> Blog Sync</a>
                </li>

                    <li>
                        <a href="#" id="social-sync"> <i class="fa fa-refresh" aria-hidden="true"></i> Social Sync</a>
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
                            <a href="#" id="delete-sync"> <i class="fa fa-trash-o" aria-hidden="true"></i> Delete Blog Content</a>
                        </li>

                        <li>
                            <a href="#" id="social-delete-sync"> <i class="fa fa-trash-o" aria-hidden="true"></i> Delete Social Content</a>
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