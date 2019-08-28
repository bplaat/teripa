<h1>Admin</h1>

<p>
    <a <?= startsWith(Router::path(), '/admin/players') ? 'class="active"' : '' ?> href="/admin/players">Players</a> &nbsp;
    <a <?= startsWith(Router::path(), '/admin/building_groups') ? 'class="active"' : '' ?> href="/admin/building_groups">Building Groups</a> &nbsp;
    <a <?= startsWith(Router::path(), '/admin/buildings') ? 'class="active"' : '' ?> href="/admin/buildings">Buildings</a> &nbsp;
    <a <?= startsWith(Router::path(), '/admin/unit_groups') ? 'class="active"' : '' ?> href="/admin/unit_groups">Unit Groups</a> &nbsp;
    <a <?= startsWith(Router::path(), '/admin/units') ? 'class="active"' : '' ?> href="/admin/units">Units</a>
    <a style="float:right" href="/admin/migrate">Migrate</a>
</p>
