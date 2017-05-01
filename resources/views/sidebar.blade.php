
<div id="sidebar" class="col-xs-12 col-sm-12 col-lg-2 col-md-6">

    <li>
        <a href="/" @if(Request::is('/'))class="active"@endif> <i class="fa fa-tumblr-square" aria-hidden="true"></i> Tumblr Content</a>
    </li>

    <li >
        <a href="/facebook" @if(Request::is('facebook'))class="active"@endif> <i class="fa fa-facebook" aria-hidden="true"></i> Facebook Content</a>
    </li>

    <li >
        <a href="/pinterest" @if(Request::is('pinterest'))class="active"@endif> <i class="fa fa-pinterest-p" aria-hidden="true"></i> Pinterest Content</a>
    </li>

    <li >
        <a href="/tumblr" @if(Request::is('tumblr'))class="active"@endif> <i class="fa fa-tumblr-square" aria-hidden="true"></i> Tumblr Feed</a>
    </li>
</div>