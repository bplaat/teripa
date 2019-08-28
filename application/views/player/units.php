<?php view('layout/header', [ 'title' => findById($unit_groups, $unit_group_id)->name . ' units' ]) ?>

<h1><?= findById($unit_groups, $unit_group_id)->name ?>  units</h1>
<p>
    <?php foreach ($unit_groups as $unit_group): ?>
        <a <?= $unit_group->id == $unit_group_id ? 'class="active"' : '' ?> href="/units/<?= slug($unit_group->name) ?>"><?= $unit_group->name ?></a> &nbsp;
    <?php endforeach ?>
</p>
<table>
    <?php foreach ($units as $unit): ?>
        <tr>
            <td style="width: 0;">
                <img width="150" src="/images/units/<?= $unit->id ?>.jpg">
            </td>
            <td>
                <h3><?= $unit->name ?></h3>
                <p>Attack: <span class="attack"><?= number_format($unit->attack) ?></span> - Defence: <span class="defence"><?= number_format($unit->defence) ?></span></p>
            </td>
            <td>Price: <span class="money">$ <?= number_format($unit->price) ?></span>
            <td><?= $unit->amount ?></td>
            <td>
                <a href="/buy_units?id=<?= $unit->id ?>&amount=1">Buy</a> &nbsp;
                <a href="/sell_units?id=<?= $unit->id ?>&amount=1">Sell</a>
            </td>
        </tr>
    <?php endforeach ?>
</table>

<?php view('layout/footer') ?>
