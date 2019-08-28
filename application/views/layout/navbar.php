<?php if (Auth::check()): ?>
    <table>
        <tr>
            <td style="width: 240px;">Money: <span id="money_timer" class="money">$ <?= number_format(Auth::player()->money) ?></span></td>
            <td>Income: <span class="money">$ <?= number_format(Auth::player()->income) ?> / s</span></td>
            <td>Attack: <span class="attack"><?= number_format(Auth::player()->attack) ?></span></td>
            <td>Defence: <span class="defence"><?= number_format(Auth::player()->defence) ?></span></td>
        </tr>
    </table>
<?php endif ?>

<table>
    <tr>
        <td>
            <a <?= Router::path() == '/' ? 'class="active"' : '' ?> href="/">Home</a> &nbsp;
            <?php if (Auth::check()): ?>
                <a <?= Router::path() == '/battle' ? 'class="active"' : '' ?> href="/battle">Battle</a> &nbsp;
                <a <?= startsWith(Router::path(), '/units') ? 'class="active"' : '' ?> href="/units">Units</a> &nbsp;
                <a <?= startsWith(Router::path(), '/buildings') ? 'class="active"' : '' ?> href="/buildings">Buildings</a>
            <?php else: ?>
                <a <?= Router::path() == '/about' ? 'class="active"' : '' ?> href="/about">About</a>
            <?php endif ?>
        </td>
        <td style="width: 275px; border-left: 1px solid #fff">
            <?php if (Auth::check()): ?>
                <img class="avatar" style="float: left; margin-right: 16px;" src="https://www.gravatar.com/avatar/<?= md5(Auth::player()->email) ?>?s=48">
                <div style="flex-direction: column;">
                    <div><?= Auth::player()->first_name ?> <?= Auth::player()->last_name ?></div>
                    <div>
                        <a <?= Router::path() == '/settings' ? 'class="active"' : '' ?> href="/settings">Settings</a>
                        <?php if (Auth::player()->role == ROLE_ADMIN): ?>
                            <a <?= startsWith(Router::path(), '/admin') ? 'class="active"' : '' ?> href="/admin">Admin</a>
                        <?php endif ?>
                       <a href="/signout">Sign out</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="/signin">Sign in</a> &nbsp;
                <a href="/signup">Sign up</a>
            <?php endif ?>
        </td>
    </tr>
</table>
