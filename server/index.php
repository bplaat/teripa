<?php

/*
 * Copyright (c) 2017-2025 Bastiaan van der Plaat
 *
 * SPDX-License-Identifier: MIT
 */

ini_set('display_errors', '0');

$db = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME']);

$db->query("CREATE TABLE IF NOT EXISTS bot (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(20) NOT NULL,
  password CHAR(32) NOT NULL,
  session CHAR(32) NOT NULL,
  food INT NOT NULL DEFAULT 2000,
  wood INT NOT NULL DEFAULT 2000,
  gold INT NOT NULL DEFAULT 2000,
  won INT NOT NULL DEFAULT 0,
  lost INT NOT NULL DEFAULT 0,
  last_pay DATETIME NOT NULL,
  map TEXT NOT NULL,
  units TEXT NOT NULL)");

function refresh ($get = "/") {
  header("Location: " . $get); exit;
}

function get ($key) {
  return isset($_GET[$key]) ? $_GET[$key] : null;
}

$p = "";

$e = 50;
$tile_info = ["00" => ["Grass"],  "01" => ["Forest"],  "02" => ["Mountain"], "20" => ["Castle - battle zone"],
"10" => ["Farm", "Increase your food income with ".$e, 0, $e*5, $e*5], "11" => ["Woodcutter", "Increase your wood income with ".$e, $e*5, 0, $e*5],
"12" => ["Gold Mine", "Increase your gold income with ".$e, $e*5, $e*5, 0], "21" => ["Baracks", "Recruit an army to attack and defend", $e*10, $e*10, $e*10],
"22" => ["Church", "Increase your defence with ".$e*10, $e*10, $e*10, $e*10]];
$units_info = [["Swordsman", $e/5, $e/5, $e, 0, $e], ["Archer", $e/2, $e/5, $e, $e, 0], ["Spearman", $e/5, $e/2, $e, $e, 0], ["Knight", $e, $e, $e*2, 0, $e*2]];
$units_len = count($units_info);

$player = $db->query("SELECT * FROM bot WHERE session='" . (isset($_COOKIE["bot_session"]) ? $_COOKIE["bot_session"] : "") . "'");

if ($player->num_rows == 1) {
  if ((!empty($_GET["x"]) && (!is_numeric(get("x")) || get("x") > 4)) || (!empty($_GET["y"]) && (!is_numeric(get("y")) || get("y") > 4))) refresh();
  $player = $player->fetch_object(); $player->map = json_decode($player->map); $player->units = json_decode($player->units);
  if (isset($_GET["p"])) {
    $other = $db->query("SELECT * FROM bot WHERE id=" . get("p"));
    if ($other->num_rows == 1) {
      $other = $other->fetch_object(); $other->map = json_decode($other->map); $other->units = json_decode($other->units);
      for ($i = $other->nc = 0; $i < 5; $i++) for ($j = 0; $j < 5; $j++) if ($other->map[$i][$j] == "22") $other->nc++;
    } else refresh();
  }
  for ($i = $player->nw = $player->nf = $player->ng = $player->nb = $player->nc = 0; $i < 5; $i++) for ($j = 0; $j < 5; $j++) {
    if ($player->map[$i][$j] == "10") $player->nf++; if ($player->map[$i][$j] == "11") $player->nw++; if ($player->map[$i][$j] == "12") $player->ng++;
    if ($player->map[$i][$j] == "21") $player->nb++; if ($player->map[$i][$j] == "22") $player->nc++;
  }
  $player->pay_time = time() - strtotime($player->last_pay);
  if ($player->pay_time > 3600) {
    $player->food += $player->nf * $e * floor($player->pay_time / 3600); $player->wood += $player->nw * $e * floor($player->pay_time / 3600); $player->gold += $player->ng * $e * floor($player->pay_time / 3600);
    $db->query("UPDATE bot SET food=" . $player->food  . ", wood=" . $player->wood . ", gold=" . $player->gold . ", last_pay='" . date("Y-m-d H:i:s", time() - $player->pay_time % 3600) . "' WHERE id=" . $player->id);
  }
  if (get("page") == "battle") {
    $player->attack = $player->units[0] * $units_info[0][1] + $player->units[1] * $units_info[1][1] + $player->units[2] * $units_info[2][1];
    $other->defend = $other->units[0] * $units_info[0][2] + $other->units[1] * $units_info[1][2] + $other->units[2] * $units_info[2][2] + $other->nc * $e*10;
    if ($player->attack > $other->defend) {
      for ($i = 0; $i < $units_len; $i++) {
        $unit_lost = rand(0, floor(($player->attack - $other->defend) / ($e*10)));
        $other->units[$i] -= $other->units[$i] > $unit_lost ? $unit_lost : $other->units[$i];
      }
      $other->defend = $other->units[0] * $units_info[0][2] + $other->units[1] * $units_info[1][2] + $other->units[2] * $units_info[2][2] + $other->nc * $e*10;
      $player->battle_food = rand(0, $other->food / 2); $player->battle_wood = rand(0, $other->wood / 2); $player->battle_gold = rand(0, $other->gold / 2);
      $player->food += $player->battle_food; $player->wood += $player->battle_wood;  $player->gold += $player->battle_gold;
      $db->query("UPDATE bot SET food=" . ($other->food - $player->battle_food) . ", wood=" . ($other->wood - $player->battle_wood) . ", gold=" . ($other->gold - $player->battle_gold) . ", lost=" . ($other->lost + 1) . ", units='" . json_encode($other->units) . "' WHERE id=" . $other->id);
      $db->query("UPDATE bot SET food=" . $player->food . ", wood=" . $player->wood . ", gold=" . $player->gold . ", won=" . ($player->won + 1) . " WHERE id=" . $player->id);
    } else {
      for ($i = 0; $i < $units_len; $i++) {
        $unit_lost = rand(0, floor(($other->defend - $player->attack) / ($e*10)));
        $player->units[$i] -= $player->units[$i] > $unit_lost ? $unit_lost : $player->units[$i];
      }
      $player->attack = $player->units[0] * $units_info[0][1] + $player->units[1] * $units_info[1][1] + $player->units[2] * $units_info[2][1];
      $db->query("UPDATE bot SET won=" . ($other->won + 1) . " WHERE id=" . $other->id);
      $db->query("UPDATE bot SET lost=" . ($player->lost + 1) . ", units='" . json_encode($player->units) . "' WHERE id=" . $player->id);
    }
  }
  $p.= get("page") == "visit" ? "<div class=\"bar\">City of " . $other->username . "<a class=\"right\" href=\"?page=map&x=2&y=2\">Back to your castle</a></div>" :
  "<div class=\"bar\">Food: " . $player->food . " (+" . $player->nf*$e . "/h) Wood: " . $player->wood . " (+" . $player->nw*$e . "/h) Gold: " . $player->gold . " (+" . $player->ng*$e .
  "/h)<span class=\"right\">" . date("i:s", 3599 - $player->pay_time) . " <a href=\"?page=settings\">Settings</a> <a href=\"?page=logout\">Logout</a></span></div>";
  switch (get("page")) {
    case "map": if (!isset($_GET["x"]) || !isset($_GET["y"])) refresh(); $m = $player->map[get("x")][get("y")];
      $p.= "<div class=\"page\"><div class=\"title\">" . $tile_info[$m][0] . "<span class=\"right\">";
      if ($m == "20") $p.= "<form class=\"inline\"><input type=\"hidden\" name=\"page\" value=\"map\"><input type=\"hidden\" name=\"x\" value=\"2\"><input type=\"hidden\" name=\"y\" value=\"2\">" .
      "<input type=\"hidden\" name=\"sort\" value=\"name\"><input type=\"text\" name=\"q\" value=\"" . get("q") . "\" placeholder=\"Search name\"> <input type=\"submit\" value=\"Search\"></form>";
      $p.= " <a class=\"close\" href=\"/\">&times;</a></span></div>";
      function buy ($m) { global $p, $tile_info, $player;
        $p.= "<table><tr><td width=\"144\"><div class=\"tile\" style=\"background-position:-" . $m[0] * 128  . "px -" . $m[1] * 92 . "px\"></div></td><td width=\"279\">" .
        "<b>" . $tile_info[$m][0] . "</b><br>" . $tile_info[$m][1] . "<br>Cost: " . ($tile_info[$m][2] > 0 ? $tile_info[$m][2] . " food" : "") .
        ($tile_info[$m][3] > 0 ? ($tile_info[$m][2] > 0 ? ", " : "") . $tile_info[$m][3] . " wood" : "") . ($tile_info[$m][4] > 0 ? ", " . $tile_info[$m][4] . " gold" : "") .
        "</td><td>" . ($player->food >= $tile_info[$m][2] && $player->wood >= $tile_info[$m][3] && $player->gold >= $tile_info[$m][4] ?
        "<a href=\"?page=build&t=" . $m . "&x=" . get("x") . "&y=" . get("y") . "\">Build</a>" : "Not enough") . "</td></tr></table>";
      }
      if ($m == "00") { buy("10"); buy("21"); buy("22"); } if ($m == "01") buy("11"); if ($m == "02") buy("12");
      if ($m == "10") $p.= "<p>All your farms produce " . $player->nf * $e . " food per hour</p>";
      if ($m == "11") $p.= "<p>All your woodcutters produce " . $player->nw * $e . " wood per hour</p>";
      if ($m == "12") $p.= "<p>All your gold mines produce " . $player->ng * $e . " gold per hour</p>";
      if ($m == "20") { $players = $db->query("SELECT id, username, won, lost FROM bot " . (get("q") ? "WHERE username LIKE '%" . get("q") . "%' " : "") . "ORDER BY " .
        (get("sort") == "name" ? "username" : (get("sort") == "lost" ? "lost" : "won") . " DESC"));
        $p.= "<table><th><a href=\"?page=map&x=2&y=2&sort=name\">Name</a>" . (get("sort") == "name" ? " &#8595;" : "") ."</th><th><a href=\"?page=map&x=2&y=2\">Won</a>" . (get("sort") != "name" && get("sort") != "lost" ? " &#8595;" : "") .
        "</th><th><a href=\"?page=map&x=2&y=2&sort=lost\">Lost</a>" . (get("sort") == "lost" ? " &#8595;" : "") ."</th><th>Actions</th></tr>"; if ($players->num_rows > 0) { while ($row = $players->fetch_object())
        $p.= "<tr><td><a href=\"?page=visit&p=" . $row->id . "\">" . $row->username . "</a></td><td>" . $row->won . "</td><td>" . $row->lost . "</td><td>" . ($row->username == $player->username ?
        "You" : "<a href=\"?page=battle&p=" . $row->id . "\">Attack</a>") . "</td></tr>"; } else { $p.= "<tr><td colspan=\"5\">No search results</td></tr>"; } $p.= "</table>";
      }
      if ($m == "21") for ($i = 0; $i < $units_len; $i++) {
        $p.= "<table><tr><td width=\"48\"><div class=\"unit\" style=\"background-position:-384px -" . $i * 32 . "px\"></div></td>" .
        "<td width=\"300\"><b>" . $units_info[$i][0] . "</b><br>Atack " . $units_info[$i][1] . " Defend " . $units_info[$i][2] . "<br>Cost: " . ($units_info[$i][3] > 0 ? $units_info[$i][3] . " food" : "") .
        ($units_info[$i][4] > 0 ? ", " . $units_info[$i][4] . " wood" : "") . ($units_info[$i][5] > 0 ? ", " . $units_info[$i][5] . " gold" : "") .
        "</td><td width=\"75\">" . $player->units[$i] . " / " . $player->nb*$e/5 . "</td><td>" . ($player->units[$i] < $player->nb*$e/5 ? ($player->food >= $units_info[$i][3] && $player->wood >= $units_info[$i][4] && $player->gold >= $units_info[$i][5] ?
        "<a href=\"?page=recruit&t=" . $i . "&x=" . get("x") . "&y=" . get("y") . "\">Recruit</a>" : "Not enough") : "Not enough place") . "</td></tr></table>";
      } if ($m == "22") $p.= "<p>All your churches prays for extra " . $player->nc*$e*10 . " defence</p>";
      if ($m[0] != "0" && $m != "20") $p.= "<p><a href=\"?page=destory&x=" . get("x") . "&y=" . get("y") . "\">Destory" . ($m == "21" ? " extra soldiers will be removed" : "") . " (get 50% back)</a></p>";
      $p.= "</div>";
    break;
    case "battle":
      $p.= "<div class=\"page\"><div class=\"title\">You " . ($player->attack > $other->defend ? "won" : "lost") . " the battle<a class=\"right close\" href=\"?page=map&x=2&y=2\">&times;</a></div>" .
      "<p>" . $player->username . " (" . $player->attack ."):";
      for ($i = 0; $i < $units_len; $i++) $p.= " <div class=\"unit\" style=\"background-position:-384px -" . $i * 32 . "px\"></div> " . $player->units[$i];
      $p.= "</p><p>" . $other->username . " (" . $other->defend ."):";
      for ($i = 0; $i < $units_len; $i++) $p.= " <div class=\"unit\" style=\"background-position:-384px -" . $i * 32 . "px\"></div> " . $other->units[$i];
      $p.= " <div class=\"unit\" style=\"background-position:-384px -128px;\"></div> " . $other->nc . "</p>" .
      "<p>You have stolen: " . ($player->attack > $other->defend ? $player->battle_food . " food, " . $player->battle_wood . " wood, " . $player->battle_gold . " gold" : "nothing, because you lose") . "!</p>" .
      "<p><a href=\"?page=battle&p=" . $other->id . "\">Attack again</a></div>";
    break;
    case "recruit":
      if ($player->units[get("t")] < $player->nb*$e/5 && $player->food >= $units_info[get("t")][3] && $player->wood >= $units_info[get("t")][4] && $player->gold >= $units_info[get("t")][5]) {
        $player->food -= $units_info[get("t")][3]; $player->wood -= $units_info[get("t")][4]; $player->gold -= $units_info[get("t")][5]; $player->units[get("t")]++;
        $db->query("UPDATE bot SET units='" . json_encode($player->units) . "', food=" . $player->food . ", wood=" . $player->wood . ", gold=" . $player->gold . " WHERE id=" . $player->id);
      } refresh("?page=map&x=" . get("x") . "&y=" . get("y"));
    break;
    case "build":
      if ($player->food >= $tile_info[get("t")][2] && $player->wood >= $tile_info[get("t")][3] && $player->gold >= $tile_info[get("t")][4]) {
        $player->food -= $tile_info[get("t")][2]; $player->wood -= $tile_info[get("t")][3]; $player->gold -= $tile_info[get("t")][4]; $player->map[get("x")][get("y")] = get("t");
        $db->query("UPDATE bot SET map='" . json_encode($player->map) . "', food=" . $player->food . ", wood=" . $player->wood . ", gold=" . $player->gold . " WHERE id=" . $player->id);
      } refresh();
    break;
    case "destory":
      $m = $player->map[get("x")][get("y")]; $player->food += $tile_info[$m][2] / 2; $player->wood += $tile_info[$m][3] / 2; $player->gold += $tile_info[$m][4] / 2;
      if ($player->map[get("x")][get("y")] == "21") { $player->nb--; for ($i = 0; $i < $units_len; $i++) if ($player->units[$i] > $player->nb*10) $player->units[$i] = $player->nb*$e/5; }
      $player->map[get("x")][get("y")] = $m == "11" ? "01" : ($m == "12" ? "02" : "00");
      $db->query("UPDATE bot SET map='" . json_encode($player->map) . "', food=" . $player->food . ", wood=" . $player->wood . ", gold=" . $player->gold . ", units='" . json_encode($player->units) . "' WHERE id=" . $player->id);
      refresh();
    break;
    case "settings":
      $p.= "<div class=\"page\"><div class=\"title\">Settings<a class=\"right close\" href=\"/\">&times;</a></div>";
      if (get("msg") == "full") $p.= "<p><b>WARNING</b> - There is already a player with this username!</p>";
      if (get("msg") == "usize") $p.= "<p><b>WARNING</b> - Your username should be between 4 and 20 characters!</p>";
      if (get("msg") == "ascii") $p.= "<p><b>WARNING</b> - Your username can oly contain 'a-zA-Z0-9'!</p>";
      if (get("msg") == "psize") $p.= "<p><b>WARNING</b> - Your password should be 6 characters or longer!</p>";
      if (get("msg") == "updated") $p.= "<p><b>INFO</b> - Your account is updated!</p>";
      $p.= "<form><input type=\"hidden\" name=\"page\" value=\"apply\"><label>Your new username</label><input type=\"text\" name=\"username\">" .
      "<label>Your new password</label><input type=\"password\" name=\"password\"><input type=\"submit\" value=\"Save\"></form></p></div>";
    break;
    case "apply":
      $player2 = $db->query("SELECT id FROM bot WHERE username='" . get("username") .  "'");
      if ($player2->num_rows == 0) {
        if (strlen(get("username")) >= 4 && strlen(get("username")) <= 20) {
          if (preg_match("/^[a-zA-Z0-9]+$/", get("username")) == 1) {
            if (strlen(get("password")) >= 6) {
              $db->query("UPDATE bot SET username='" . get("username") . "', password='" . md5(get("password")) . "' WHERE id=" . $player->id);
              refresh("?page=settings&msg=updated");
            } else refresh("?page=settings&msg=psize");
          } else refresh("?page=settings&msg=ascii");
        } else refresh("?page=settings&msg=usize");
      } else refresh("?page=settings&msg=full");
    break;
    case "logout":
      setcookie("bot_session", "", time() - 60 * 60);
      refresh();
    break;
    case "visit":
      for ($i = 0; $i < 5; $i++) for ($j = 0; $j < 5; $j++)
        $p.= "<div class=\"tile\" style=\"background-position:-" . $other->map[$i][$j][0] * 128  . "px -" . $other->map[$i][$j][1] * 92 . "px\"></div>";
    break;
    default:
      for ($i = 0; $i < 5; $i++) for ($j = 0; $j < 5; $j++)
        $p.= "<a class=\"tile\" href=\"?page=map&x=" . $i . "&y=" . $j . "\" style=\"background-position:-" . $player->map[$i][$j][0] * 128  . "px -" . $player->map[$i][$j][1] * 92 . "px\"></a>";
  }
} else {
  $p.= "<div class=\"begin\"><div class=\"title-logo\">Battle of Teripa</div><i class=\"slogan\">The medieval ages</i>";
  switch (get("page")) {
    case "login":
      $player = $db->query("SELECT id FROM bot WHERE username='" . get("username") .  "' and password='" . md5(get("password")) . "'");
      if ($player->num_rows == 1) {
        $session = md5(microtime());
        setcookie("bot_session", $session, time() + 60 * 60 * 24 * 7);
        $db->query("UPDATE bot SET session='" . $session . "' WHERE id=" . $player->fetch_object()->id);
        refresh();
      } else refresh("?msg=wrong");
    break;
    case "register":
      $player = $db->query("SELECT id FROM bot WHERE username='" . get("username") .  "'");
      if ($player->num_rows == 0) {
        if (strlen(get("username")) >= 4 && strlen(get("username")) <= 20) {
          if (preg_match("/^[a-zA-Z0-9]+$/", get("username")) == 1) {
            if (strlen(get("password")) >= 6) {
              $session = md5(microtime());
              setcookie("bot_session", $session, time() + 60 * 60 * 24 * 7);
              $db->query("INSERT INTO bot (username, password, session, last_pay, map, units) VALUES ('" . get("username") . "', '" . md5(get("password")) . "', '" . $session . "', '" . date("Y-m-d H:i:s") .
              "', '[[\"01\",\"01\",\"01\",\"00\",\"00\"],[\"01\",\"01\",\"00\",\"00\",\"00\"],[\"01\",\"00\",\"20\",\"00\",\"02\"],[\"00\",\"00\",\"00\",\"02\",\"02\"],[\"00\",\"00\",\"02\",\"02\",\"02\"]]', '[0,0,0,0]')");
              refresh();
            } else refresh("?page=signup&msg=psize");
          } else refresh("?page=signup&msg=ascii");
        } else refresh("?page=signup&msg=usize");
      } else refresh("?page=signup&msg=full");
    break;
    case "signup":
      if (get("msg") == "full") $p.= "<p><b>WARNING</b> - There is already a player with this username!</p>";
      if (get("msg") == "usize") $p.= "<p><b>WARNING</b> - Your username should be between 4 and 20 characters!</p>";
      if (get("msg") == "ascii") $p.= "<p><b>WARNING</b> - Your username can oly contain 'a-zA-Z0-9'!</p>";
      if (get("msg") == "psize") $p.= "<p><b>WARNING</b> - Your password should be 6 characters or longer!</p>";
      $p.= "<p><b>Sign up an account</b></p><form><input type=\"hidden\" name=\"page\" value=\"register\">" .
      "<label>Username</label><input type=\"text\" name=\"username\"><label>Password</label><input type=\"password\" name=\"password\">" .
      "<input type=\"submit\" value=\"Sign up\"></form><p><a href=\"/\">Al ready have an account?</a></p>";
    break;
    default:
      if (get("msg") == "wrong") $p.= "<p><b>WARNING</b> - Wrong username or password!</p>";
      if (get("msg") == "deleted") $p.= "<p><b>INFO</b> - Your account is successful deleted!</p>";
      $p.= "<p><b>Login with your account</b></p><form><input type=\"hidden\" name=\"page\" value=\"login\">" .
      "<label>Username</label><input type=\"text\" name=\"username\"><label>Password</label><input type=\"password\" name=\"password\">" .
      "<input type=\"submit\" value=\"Login\"></form><p><a href=\"?page=signup\">Don't have an account?</a></p>";
  }
  $p.= "<p><b>INFO</b> - We save your login for a week in cookies!</p></div>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Battle of Teripa</title>
  <link rel="stylesheet" href="/style.css">
</head>
<body>
  <h1>Battle of Teripa</h1>
  <div class="main container">
    <?php echo $p ?>
  </div>
  <p>Made by <a href="https://bplaat.nl">Bastiaan van der Plaat</a></p>
</body>
</html>
