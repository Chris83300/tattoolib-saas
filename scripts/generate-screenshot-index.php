<?php
/**
 * Génère une galerie HTML des screenshots Pest Browser.
 * Usage : php scripts/generate-screenshot-index.php
 */

$screenshotDir = __DIR__ . '/../tests/Browser/Screenshots';
$outputFile    = $screenshotDir . '/index.html';
$groups = ['guest', 'tattooer', 'pierceur', 'client', 'studio', 'admin', 'darkmode'];

$files = [];
foreach ($groups as $group) {
    $dir = $screenshotDir . '/' . $group;
    if (!is_dir($dir)) continue;
    $pngs = glob($dir . '/*.png');
    sort($pngs);
    foreach ($pngs as $path) { $files[$group][] = basename($path); }
}

$date  = date('Y-m-d H:i');
$total = array_sum(array_map('count', $files));

ob_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ink&amp;Pik — Galerie Screenshots</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body { font-family: system-ui, sans-serif; background: #111; color: #eee; }
header { background: #1a1a1a; padding: 1.5rem 2rem; border-bottom: 1px solid #333; display: flex; align-items: center; justify-content: space-between; }
header h1 { font-size: 1.4rem; }
header span { font-size: 0.85rem; color: #888; }
nav { display: flex; gap: 0.5rem; padding: 1rem 2rem; background: #161616; flex-wrap: wrap; border-bottom: 1px solid #333; }
nav a { padding: 0.4rem 1rem; background: #2a2a2a; border-radius: 4px; color: #ccc; text-decoration: none; font-size: 0.85rem; transition: background 0.2s; }
nav a:hover, nav a.active { background: #4a4a4a; color: #fff; }
.section { padding: 1.5rem 2rem; }
.section h2 { font-size: 1.1rem; margin-bottom: 1rem; color: #aaa; text-transform: uppercase; letter-spacing: 0.05em; }
.grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1rem; }
.card { background: #1e1e1e; border-radius: 8px; overflow: hidden; border: 1px solid #2a2a2a; transition: border-color 0.2s; }
.card:hover { border-color: #555; }
.card img { width: 100%; display: block; cursor: zoom-in; }
.card-label { padding: 0.5rem 0.75rem; font-size: 0.75rem; color: #888; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
#lb { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.92); z-index: 999; align-items: center; justify-content: center; cursor: zoom-out; }
#lb.open { display: flex; }
#lb img { max-width: 90vw; max-height: 90vh; border-radius: 6px; box-shadow: 0 0 40px rgba(0,0,0,0.8); }
</style>
</head>
<body>
<header>
  <h1>Ink&amp;Pik — Galerie Screenshots</h1>
  <span>Generee le <?= $date ?> &middot; <?= $total ?> captures</span>
</header>
<nav>
  <a href="#all" onclick="showAll()">Tout (<?= $total ?>)</a>
<?php foreach ($groups as $g): $cnt = count($files[$g] ?? []); ?>
  <a href="#<?= $g ?>" onclick="showGroup('<?= $g ?>')"><?= ucfirst($g) ?> (<?= $cnt ?>)</a>
<?php endforeach; ?>
</nav>

<?php foreach ($groups as $group):
    if (empty($files[$group])) continue; ?>
<div class="section group-section" data-group="<?= $group ?>">
  <h2><?= $group ?></h2>
  <div class="grid">
<?php foreach ($files[$group] as $filename):
    $label = pathinfo($filename, PATHINFO_FILENAME);
    $src   = "{$group}/{$filename}"; ?>
    <div class="card">
      <img src="<?= $src ?>" alt="<?= htmlspecialchars($label) ?>" loading="lazy" onclick="openLb(this.src)">
      <div class="card-label"><?= htmlspecialchars($label) ?></div>
    </div>
<?php endforeach; ?>
  </div>
</div>
<?php endforeach; ?>

<div id="lb" onclick="closeLb()"><img id="lb-img" src="" alt=""></div>
<script>
function openLb(src){document.getElementById('lb-img').src=src;document.getElementById('lb').classList.add('open');}
function closeLb(){document.getElementById('lb').classList.remove('open');}
document.addEventListener('keydown',e=>{if(e.key==='Escape')closeLb();});
function showAll(){document.querySelectorAll('.group-section').forEach(s=>s.style.display='');document.querySelectorAll('nav a').forEach(a=>a.classList.remove('active'));event.target.classList.add('active');}
function showGroup(g){document.querySelectorAll('.group-section').forEach(s=>s.style.display=s.dataset.group===g?'':'none');document.querySelectorAll('nav a').forEach(a=>a.classList.remove('active'));event.target.classList.add('active');}
</script>
</body>
</html>
<?php
file_put_contents($outputFile, ob_get_clean());
echo "Gallery generated: {$outputFile}\n";
echo "{$total} screenshots in " . count(array_filter($files)) . " groups.\n";
