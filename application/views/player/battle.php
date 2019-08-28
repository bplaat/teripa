<?php view('layout/header', [ 'title' => 'Battle' ]) ?>

<h1>Battle</h1>
<table>
    <tr>
        <th>Name</th>
        <th>Won</th>
        <th>Lost</th>
        <th></th>
    </tr>
    <?php foreach ($players as $player): ?>
        <tr>
            <td><?= $player->username ?></td>
            <td><?= number_format($player->won) ?></td>
            <td><?= number_format($player->lost) ?></td>
            <td>
                <?php if ($player->id != Auth::player()->id): ?>
                    <a href="/attack?id=<?= $player->id ?>">Attack</a>
                <?php endif ?>
            </td>
        </tr>
    <?php endforeach ?>
</table>

<?php view('layout/footer') ?>
