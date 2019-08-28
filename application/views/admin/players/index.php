<?php view('layout/header', [ 'title' => 'Admin' ]) ?>

<?php view('admin/navbar') ?>

<h2>Players</h2>
<table>
    <tr>
        <th>#</th>
        <th>First name</th>
        <th>Last name</th>
        <th>Username</th>
        <th>Email</th>
        <th>Money</th>
        <th>Income</th>
        <th>Attack</th>
        <th>Defence</th>
        <th>Win</th>
        <th>Lost</th>
        <th></th>
    </tr>

    <?php foreach ($players as $player): ?>
        <tr>
            <td><?= $player->id ?></td>
            <td><?= $player->first_name ?></td>
            <td><?= $player->last_name ?></td>
            <td><?= $player->username ?></td>
            <td><?= cut($player->email, 15) ?></td>
            <td><span class="money">$ <?= number_format($player->money) ?></span></td>
            <td><span class="money">$ <?= number_format($player->income) ?> / s</span></td>
            <td><span class="attack"><?= number_format($player->attack) ?></span></td>
            <td><span class="defence"><?= number_format($player->defence) ?></span></td>
            <td><?= $player->won ?></td>
            <td><?= $player->lost ?></td>
            <td>
                <a href="/admin/players/<?= $player->id ?>/edit">Edit</a> &nbsp;
                <a href="/admin/players/<?= $player->id ?>/delete">Delete</a>
            </td>
        </tr>
    <?php endforeach ?>
</table>

<?php view('layout/footer') ?>
