<?php
$campanha   = isset($_POST['campanha'])   ? htmlspecialchars(trim($_POST['campanha']))   : '';
$premio     = isset($_POST['premio'])     ? htmlspecialchars(trim($_POST['premio']))     : '';
$valor      = isset($_POST['valor'])      ? htmlspecialchars(trim($_POST['valor']))      : '';
$quantidade = isset($_POST['quantidade']) ? (int)$_POST['quantidade']                   : 0;
$gerado     = ($campanha && $premio && $valor && $quantidade > 0 && $quantidade <= 500);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gerador de Rifas</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root {
    --gold:   #D4A017;
    --gold2:  #F0C84A;
    --dark:   #1A1208;
    --card:   #231A09;
    --border: #3D2E10;
    --text:   #F5E9C8;
    --muted:  #A08050;
    --red:    #C0392B;
    --green:  #1E8449;
  }

  * { margin: 0; padding: 0; box-sizing: border-box; }

  body {
    font-family: 'DM Sans', sans-serif;
    background: var(--dark);
    color: var(--text);
    min-height: 100vh;
    background-image:
      radial-gradient(ellipse at 20% 20%, rgba(212,160,23,0.08) 0%, transparent 50%),
      radial-gradient(ellipse at 80% 80%, rgba(212,160,23,0.05) 0%, transparent 50%);
  }

  /* ===== HEADER ===== */
  header {
    text-align: center;
    padding: 52px 20px 36px;
    border-bottom: 1px solid var(--border);
    position: relative;
  }
  header::after {
    content: '';
    display: block;
    width: 80px; height: 3px;
    background: linear-gradient(90deg, transparent, var(--gold), transparent);
    margin: 18px auto 0;
  }
  .logo-label {
    font-size: 11px;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 10px;
  }
  header h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2rem, 5vw, 3.2rem);
    font-weight: 900;
    color: var(--gold2);
    text-shadow: 0 2px 20px rgba(212,160,23,0.3);
    line-height: 1.1;
  }
  header p {
    color: var(--muted);
    margin-top: 10px;
    font-size: 15px;
  }

  /* ===== LAYOUT ===== */
  .container { max-width: 960px; margin: 0 auto; padding: 40px 20px 80px; }

  /* ===== FORM ===== */
  .form-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 40px;
    margin-bottom: 48px;
    position: relative;
    overflow: hidden;
  }
  .form-card::before {
    content: '🎟️';
    font-size: 120px;
    position: absolute;
    right: -10px; top: -10px;
    opacity: 0.05;
    line-height: 1;
  }
  .form-card h2 {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    color: var(--gold2);
    margin-bottom: 28px;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .form-card h2::before { content: ''; display: block; width: 4px; height: 24px; background: var(--gold); border-radius: 2px; }

  .form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
  }
  .form-grid .full { grid-column: 1 / -1; }

  .field label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: var(--gold);
    margin-bottom: 8px;
  }
  .field input {
    width: 100%;
    background: rgba(255,255,255,0.04);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 12px 16px;
    color: var(--text);
    font-family: 'DM Sans', sans-serif;
    font-size: 15px;
    transition: border-color 0.2s, box-shadow 0.2s;
    outline: none;
  }
  .field input:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(212,160,23,0.15);
  }
  .field input::placeholder { color: var(--muted); }

  .btn-gerar {
    margin-top: 28px;
    width: 100%;
    background: linear-gradient(135deg, var(--gold) 0%, var(--gold2) 100%);
    color: var(--dark);
    border: none;
    border-radius: 10px;
    padding: 16px 32px;
    font-family: 'DM Sans', sans-serif;
    font-size: 16px;
    font-weight: 700;
    letter-spacing: 1px;
    cursor: pointer;
    transition: transform 0.15s, box-shadow 0.15s;
  }
  .btn-gerar:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(212,160,23,0.35);
  }
  .btn-gerar:active { transform: translateY(0); }

  /* ===== RESULTADO ===== */
  .resultado-header {
    text-align: center;
    margin-bottom: 36px;
  }
  .resultado-header h2 {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    color: var(--gold2);
  }
  .info-badges {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 12px;
    margin-top: 16px;
  }
  .badge {
    background: rgba(212,160,23,0.12);
    border: 1px solid rgba(212,160,23,0.3);
    border-radius: 100px;
    padding: 6px 18px;
    font-size: 13px;
    color: var(--gold2);
    display: flex;
    align-items: center;
    gap: 6px;
  }

  /* ===== BILHETES ===== */
  .bilhetes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 14px;
    margin-top: 32px;
  }

  .bilhete {
    background: var(--card);
    border: 1px dashed var(--border);
    border-radius: 10px;
    padding: 14px 16px;
    display: flex;
    align-items: center;
    gap: 14px;
    position: relative;
    overflow: hidden;
    transition: border-color 0.2s;
  }
  .bilhete:hover { border-color: var(--gold); }
  .bilhete::after {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 4px;
    background: linear-gradient(180deg, var(--gold), var(--gold2));
    border-radius: 10px 0 0 10px;
  }

  .bilhete-num {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gold2);
    min-width: 54px;
    text-align: right;
  }

  .bilhete-info { flex: 1; overflow: hidden; }
  .bilhete-campanha {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.5px;
    color: var(--muted);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .bilhete-valor {
    font-size: 13px;
    font-weight: 600;
    color: var(--gold);
    margin-top: 2px;
  }

  /* separador perfurado */
  .bilhete-sep {
    width: 1px;
    height: 36px;
    background: repeating-linear-gradient(180deg, var(--border) 0 4px, transparent 4px 8px);
  }

  /* ===== BOTÃO IMPRIMIR ===== */
  .print-bar {
    text-align: center;
    margin-top: 40px;
  }
  .btn-print {
    background: transparent;
    border: 2px solid var(--gold);
    color: var(--gold2);
    border-radius: 10px;
    padding: 14px 40px;
    font-family: 'DM Sans', sans-serif;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    transition: background 0.2s, color 0.2s;
  }
  .btn-print:hover { background: var(--gold); color: var(--dark); }

  /* ===== IMPRESSÃO ===== */
  @media print {
    body { background: #fff !important; color: #000 !important; }
    header, .form-card, .print-bar { display: none !important; }
    .bilhete {
      border: 1px dashed #999 !important;
      break-inside: avoid;
      page-break-inside: avoid;
    }
    .bilhete-num { color: #333 !important; }
    .bilhete-campanha { color: #666 !important; }
    .bilhete-valor { color: #000 !important; }
    .bilhete::after { background: #333 !important; }
    .bilhetes-grid { grid-template-columns: repeat(3, 1fr) !important; }
    .resultado-header h2, .badge { color: #000 !important; background: #eee !important; }
  }

  @media (max-width: 600px) {
    .form-grid { grid-template-columns: 1fr; }
    .form-grid .full { grid-column: 1; }
    .form-card { padding: 24px 20px; }
  }
</style>
</head>
<body>

<header>
  <p class="logo-label">Sistema de Rifas</p>
  <h1>Gerador de Bilhetes</h1>
  <p>Preencha os dados da campanha e gere seus bilhetes numerados</p>
</header>

<div class="container">

  <!-- FORMULÁRIO -->
  <div class="form-card">
    <h2>Dados da Rifa</h2>
    <form method="POST" action="">
      <div class="form-grid">
        <div class="field full">
          <label>Nome da Campanha / Título da Rifa</label>
          <input type="text" name="campanha" placeholder="Ex: Rifa Beneficente Escola Municipal"
                 value="<?= $campanha ?>" required maxlength="80">
        </div>
        <div class="field full">
          <label>Prêmio(s) a ser Rifado</label>
          <input type="text" name="premio" placeholder="Ex: Smart TV 55&quot; + Bicicleta + Vale-presente R$500"
                 value="<?= $premio ?>" required maxlength="120">
        </div>
        <div class="field">
          <label>Valor do Bilhete (R$)</label>
          <input type="text" name="valor" placeholder="Ex: 10,00"
                 value="<?= $valor ?>" required maxlength="20">
        </div>
        <div class="field">
          <label>Quantidade de Bilhetes (máx. 500)</label>
          <input type="number" name="quantidade" placeholder="Ex: 100"
                 value="<?= $quantidade ?: '' ?>" min="1" max="500" required>
        </div>
      </div>
      <button type="submit" class="btn-gerar">🎟️ &nbsp; Gerar Bilhetes</button>
    </form>
  </div>

  <!-- RESULTADO -->
  <?php if ($gerado): ?>
  <div class="resultado-header">
    <h2><?= $campanha ?></h2>
    <div class="info-badges">
      <span class="badge">🏆 <?= $premio ?></span>
      <span class="badge">💰 R$ <?= $valor ?> por bilhete</span>
      <span class="badge">🎟️ <?= $quantidade ?> bilhete<?= $quantidade > 1 ? 's' : '' ?></span>
    </div>
  </div>

  <div class="bilhetes-grid" id="area-bilhetes">
    <?php for ($i = 1; $i <= $quantidade; $i++):
      $num = str_pad($i, 3, "0", STR_PAD_LEFT);
    ?>
    <div class="bilhete">
      <span class="bilhete-num"><?= $num ?></span>
      <div class="bilhete-sep"></div>
      <div class="bilhete-info">
        <div class="bilhete-campanha"><?= $campanha ?></div>
        <div class="bilhete-valor">R$ <?= $valor ?></div>
      </div>
    </div>
    <?php endfor; ?>
  </div>

  <div class="print-bar">
    <button class="btn-print" onclick="window.print()">
      🖨️ Imprimir Bilhetes
    </button>
  </div>
  <?php endif; ?>

  <?php if (isset($_POST['quantidade']) && !$gerado && !empty($_POST)): ?>
    <p style="text-align:center; color:#e74c3c; margin-top:20px;">
      ⚠️ Verifique os dados. A quantidade deve ser entre 1 e 500.
    </p>
  <?php endif; ?>

</div>
</body>
</html>