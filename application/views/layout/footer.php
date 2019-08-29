    <?php if (!is_null(Auth::player())): ?>
        <script>
            var moneyTimer = document.getElementById('money_timer');
            var money = <?= Auth::player()->money ?>;
            var income = <?= Auth::player()->income ?>;
            var paid_at = <?= strtotime(Auth::player()->paid_at) ?>;
            function updateMoney () {
                moneyTimer.textContent = '$ ' + Math.floor(money + ((Date.now() / 1000) - paid_at) * income).toLocaleString('en');
                setTimeout(updateMoney, 75);
            }
            updateMoney();
        </script>
    <?php endif ?>
    <p><code><?= round((microtime(true) - $_SERVER['REQUEST_TIME_FLOAT']) * 1000, 1)  ?> ms - <?= Database::queryCount() ?> queries</code></p>
</body>
</html>
