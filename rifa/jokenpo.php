<?php
// ===== LÓGICA DO JOGO =====
$opcoes = [1 => 'Pedra', 2 => 'Papel', 3 => 'Tesoura'];
$emojis = [1 => '🪨', 2 => '📄', 3 => '✂️'];

$jogou      = false;
$escolhaJog = 0;
$escolhaCom = 0;
$resultado  = '';
$placar_j   = isset($_SESSION['pj']) ? $_SESSION['pj'] : 0;
$placar_c   = isset($_SESSION['pc']) ? $_SESSION['pc'] : 0;
$placar_e   = isset($_SESSION['pe']) ? $_SESSION['pe'] : 0;

// Inicia sessão para placar
session_start();
$placar_j = $_SESSION['pj'] ?? 0;
$placar_c = $_SESSION['pc'] ?? 0;
$placar_e = $_SESSION['pe'] ?? 0;

function compararJogadas(int $j, int $c): string {
    if ($j === $c) return 'empate';
    if (
        ($j === 1 && $c === 3) ||
        ($j === 2 && $c === 1) ||
        ($j === 3 && $c === 2)
    ) return 'vitoria';
    return 'derrota';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jogada'])) {
    $jogou      = true;
    $escolhaJog = (int)$_POST['jogada'];
    $escolhaCom = rand(1, 3);
    $resultado  = compararJogadas($escolhaJog, $escolhaCom);

    if ($resultado === 'vitoria')  { $_SESSION['pj'] = ++$placar_j; }
    elseif ($resultado === 'derrota') { $_SESSION['pc'] = ++$placar_c; }
    else                          { $_SESSION['pe'] = ++$placar_e; }
}

if (isset($_POST['resetar'])) {
    $_SESSION['pj'] = $_SESSION['pc'] = $_SESSION['pe'] = 0;
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Jo-Ken-Pô</title>
<link href="https://fonts.googleapis.com/css2?family=Bangers&family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>
  :root {
    --bg:     #0D0D1A;
    --card:   #161625;
    --border: #2A2A45;
    --purple: #7C3AED;
    --pink:   #EC4899;
    --cyan:   #06B6D4;
    --yellow: #FBBF24;
    --green:  #10B981;
    --red:    #EF4444;
    --text:   #E2E8F0;
    --muted:  #64748B;
  }
  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'Nunito', sans-serif;
    background: var(--bg);
    color: var(--text);
    min-height: 100vh;
    overflow-x: hidden;
    background-image:
      radial-gradient(ellipse at 10% 10%, rgba(124,58,237,0.15) 0%, transparent 40%),
      radial-gradient(ellipse at 90% 90%, rgba(236,72,153,0.10) 0%, transparent 40%);
  }

  /* ===== HEADER ===== */
  header {
    text-align: center;
    padding: 48px 20px 32px;
  }
  .subtitle {
    font-size: 12px;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: var(--cyan);
    margin-bottom: 8px;
  }
  header h1 {
    font-family: 'Bangers', cursive;
    font-size: clamp(3rem, 8vw, 5rem);
    letter-spacing: 4px;
    background: linear-gradient(135deg, var(--purple), var(--pink), var(--cyan));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1;
  }
  header p { color: var(--muted); margin-top: 10px; font-size: 15px; }

  /* ===== PLACAR ===== */
  .placar-bar {
    display: flex;
    justify-content: center;
    gap: 0;
    max-width: 480px;
    margin: 0 auto 48px;
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
  }
  .placar-item {
    flex: 1;
    text-align: center;
    padding: 16px 8px;
    border-right: 1px solid var(--border);
  }
  .placar-item:last-child { border-right: none; }
  .placar-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    margin-bottom: 4px;
  }
  .placar-num {
    font-family: 'Bangers', cursive;
    font-size: 2.2rem;
    letter-spacing: 2px;
  }
  .placar-item.vitorias .placar-label { color: var(--green); }
  .placar-item.vitorias .placar-num   { color: var(--green); }
  .placar-item.derrotas .placar-label { color: var(--red); }
  .placar-item.derrotas .placar-num   { color: var(--red); }
  .placar-item.empates  .placar-label { color: var(--yellow); }
  .placar-item.empates  .placar-num   { color: var(--yellow); }

  /* ===== CONTAINER ===== */
  .container { max-width: 680px; margin: 0 auto; padding: 0 20px 80px; }

  /* ===== ARENA (resultado) ===== */
  .arena {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 36px 28px;
    margin-bottom: 36px;
    text-align: center;
    display: <?= $jogou ? 'block' : 'none' ?>;
    animation: fadeIn 0.4s ease;
  }
  @keyframes fadeIn { from { opacity:0; transform: translateY(12px); } to { opacity:1; transform: translateY(0); } }

  .versus-row {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 20px;
    margin-bottom: 28px;
  }
  .jogador-card {
    flex: 1;
    background: rgba(255,255,255,0.03);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 20px 12px;
  }
  .jogador-label {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 10px;
  }
  .jogador-gif {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid var(--border);
    display: block;
    margin: 0 auto 10px;
    background: var(--border);
  }
  .jogador-nome {
    font-weight: 800;
    font-size: 1rem;
    color: var(--text);
  }
  .vs-badge {
    font-family: 'Bangers', cursive;
    font-size: 2rem;
    color: var(--muted);
    letter-spacing: 2px;
    flex-shrink: 0;
  }

  /* Resultado */
  .resultado-box {
    border-radius: 12px;
    padding: 18px 24px;
    font-family: 'Bangers', cursive;
    font-size: 2rem;
    letter-spacing: 2px;
  }
  .resultado-box.vitoria  { background: rgba(16,185,129,0.15); color: var(--green);  border: 1px solid rgba(16,185,129,0.3); }
  .resultado-box.derrota  { background: rgba(239,68,68,0.15);  color: var(--red);    border: 1px solid rgba(239,68,68,0.3);  }
  .resultado-box.empate   { background: rgba(251,191,36,0.12); color: var(--yellow); border: 1px solid rgba(251,191,36,0.3); }

  /* ===== FORMULÁRIO DE ESCOLHA ===== */
  .escolha-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 36px 28px;
  }
  .escolha-card h2 {
    font-family: 'Bangers', cursive;
    font-size: 1.8rem;
    letter-spacing: 2px;
    color: var(--cyan);
    text-align: center;
    margin-bottom: 28px;
  }
  .opcoes-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 24px;
  }
  .opcao-btn {
    background: rgba(255,255,255,0.03);
    border: 2px solid var(--border);
    border-radius: 14px;
    padding: 18px 8px;
    cursor: pointer;
    transition: border-color 0.2s, transform 0.15s, background 0.2s;
    text-align: center;
    color: var(--text);
    font-family: 'Nunito', sans-serif;
  }
  .opcao-btn:hover {
    border-color: var(--purple);
    background: rgba(124,58,237,0.12);
    transform: translateY(-4px);
  }
  .opcao-btn .opcao-emoji { font-size: 3rem; display: block; margin-bottom: 8px; }
  .opcao-btn .opcao-texto { font-size: 13px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; }

  /* highlight selecionado */
  .opcao-btn.selecionado { border-color: var(--cyan); background: rgba(6,182,212,0.12); }

  /* botões de ação */
  .acoes { display: flex; gap: 12px; flex-direction: column; }
  .btn-jogar {
    background: linear-gradient(135deg, var(--purple), var(--pink));
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 16px;
    font-family: 'Nunito', sans-serif;
    font-size: 16px;
    font-weight: 800;
    cursor: pointer;
    letter-spacing: 1px;
    transition: opacity 0.2s, transform 0.15s;
  }
  .btn-jogar:hover { opacity: 0.9; transform: translateY(-2px); }
  .btn-jogar:disabled { opacity: 0.4; cursor: not-allowed; transform: none; }

  .btn-resetar {
    background: transparent;
    border: 1px solid var(--border);
    color: var(--muted);
    border-radius: 10px;
    padding: 10px;
    font-family: 'Nunito', sans-serif;
    font-size: 13px;
    cursor: pointer;
    transition: color 0.2s, border-color 0.2s;
  }
  .btn-resetar:hover { color: var(--red); border-color: var(--red); }

  @media (max-width: 480px) {
    .versus-row { flex-direction: column; gap: 12px; }
    .vs-badge { transform: rotate(90deg); }
  }
</style>
</head>
<body>

<header>
  <p class="subtitle">Jogo Clássico</p>
  <h1>Jo-Ken-Pô</h1>
  <p>Você consegue vencer o computador?</p>
</header>

<!-- PLACAR -->
<div class="placar-bar" style="max-width:480px; margin:0 auto 48px;">
  <div class="placar-item vitorias">
    <div class="placar-label">Suas vitórias</div>
    <div class="placar-num"><?= $placar_j ?></div>
  </div>
  <div class="placar-item empates">
    <div class="placar-label">Empates</div>
    <div class="placar-num"><?= $placar_e ?></div>
  </div>
  <div class="placar-item derrotas">
    <div class="placar-label">Derrotas</div>
    <div class="placar-num"><?= $placar_c ?></div>
  </div>
</div>

<div class="container">

  <!-- ARENA DE RESULTADO -->
  <?php if ($jogou): ?>
  <div class="arena" id="arena">
    <div class="versus-row">
      <!-- Jogador -->
      <div class="jogador-card">
        <div class="jogador-label">Você</div>
        <?php
          $gifsJog = [
            1 => 'https://media.giphy.com/media/3o7TKSjRrfIPjeiVyM/giphy.gif',
            2 => 'https://media.giphy.com/media/l0HlBO7eyXzSZkJri/giphy.gif',
            3 => 'https://media.giphy.com/media/26ufdipQqU2lhNA4g/giphy.gif',
          ];
        ?>
        <img class="jogador-gif"
             src="<?= $gifsJog[$escolhaJog] ?>"
             alt="<?= $opcoes[$escolhaJog] ?>"
             onerror="this.style.display='none'">
        <div class="jogador-nome"><?= $emojis[$escolhaJog] ?> <?= $opcoes[$escolhaJog] ?></div>
      </div>

      <div class="vs-badge">VS</div>

      <!-- Computador -->
      <div class="jogador-card">
        <div class="jogador-label">Computador</div>
        <?php
          $gifsCom = [
            1 => 'https://media.giphy.com/media/3o7TKSjRrfIPjeiVyM/giphy.gif',
            2 => 'https://media.giphy.com/media/l0HlBO7eyXzSZkJri/giphy.gif',
            3 => 'https://media.giphy.com/media/26ufdipQqU2lhNA4g/giphy.gif',
          ];
        ?>
        <img class="jogador-gif"
             src="<?= $gifsCom[$escolhaCom] ?>"
             alt="<?= $opcoes[$escolhaCom] ?>"
             onerror="this.style.display='none'">
        <div class="jogador-nome"><?= $emojis[$escolhaCom] ?> <?= $opcoes[$escolhaCom] ?></div>
      </div>
    </div>

    <!-- Resultado -->
    <?php if ($resultado === 'vitoria'): ?>
      <div class="resultado-box vitoria">🏆 Você Venceu!</div>
    <?php elseif ($resultado === 'derrota'): ?>
      <div class="resultado-box derrota">💀 Computador Venceu!</div>
    <?php else: ?>
      <div class="resultado-box empate">🤝 Empate!</div>
    <?php endif; ?>
  </div>
  <?php endif; ?>

  <!-- FORMULÁRIO DE ESCOLHA -->
  <div class="escolha-card">
    <h2>Faça sua jogada!</h2>

    <form method="POST" action="" id="form-jogo">
      <div class="opcoes-grid">
        <button type="submit" name="jogada" value="1" class="opcao-btn">
          <span class="opcao-emoji">🪨</span>
          <span class="opcao-texto">Pedra</span>
        </button>
        <button type="submit" name="jogada" value="2" class="opcao-btn">
          <span class="opcao-emoji">📄</span>
          <span class="opcao-texto">Papel</span>
        </button>
        <button type="submit" name="jogada" value="3" class="opcao-btn">
          <span class="opcao-emoji">✂️</span>
          <span class="opcao-texto">Tesoura</span>
        </button>
      </div>
    </form>

    <form method="POST" action="">
      <button type="submit" name="resetar" value="1" class="btn-resetar" style="width:100%">
        🔄 Resetar placar
      </button>
    </form>
  </div>

</div>

<script>
  // Destaca a opção do resultado anterior
  <?php if ($jogou): ?>
  const botoes = document.querySelectorAll('.opcao-btn');
  botoes.forEach(b => {
    if (b.value == <?= $escolhaJog ?>) b.classList.add('selecionado');
  });
  <?php endif; ?>

  // Scroll suave para o resultado
  <?php if ($jogou): ?>
  document.getElementById('arena')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
  <?php endif; ?>
</script>
</body>
</html>