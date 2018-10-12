<h1 class="company">Abz.agensy test</h1>
<div class="buttons">
    @guest
        <button class="btn login">Login</button>
    @else
        <button class="btn logout">Logout</button>
        <button class="btn crud"><a href="crud.html">CRUD</a></button>
    @endguest
</div>
<form action="#" method="post" class="login_window">
    <input type="text" placeholder="Login">
    <input type="password" placeholder="Pass">
    <button type="submit" class="btn">Login</button>
</form>