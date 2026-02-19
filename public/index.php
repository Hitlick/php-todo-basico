<?php
declare(strict_types=1);

require __DIR__ . '/../src/helpers.php';
require __DIR__ . '/../src/TaskRepository.php';

$repo = new TaskRepository(__DIR__ . '/../storage/data.json');

$action = $_POST['action'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Proteção mínima contra “recarregar e reenviar”
    // (não é segurança completa, mas já ensina um padrão)
    if ($action === 'add') {
        $title = trim((string)($_POST['title'] ?? ''));
        if ($title === '') {
            flash('error', 'Título não pode ficar vazio.');
        } else {
            $repo->add($title);
            flash('success', 'Tarefa criada!');
        }
        redirect('/');

    } elseif ($action === 'toggle') {
        $id = (int)($_POST['id'] ?? 0);
        $repo->toggle($id);
        redirect('/');

    } elseif ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        $repo->delete($id);
        flash('success', 'Tarefa removida.');
        redirect('/');
    }
}

$tasks = $repo->all();
[$flashType, $flashMsg] = get_flash();
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PHP TODO Básico</title>
  <link rel="stylesheet" href="/style.css">
</head>
<body>
  <main class="container">
    <h1>PHP TODO (básico do básico)</h1>
    <p class="muted">Sem framework, sem banco. Só PHP + JSON. Ideal pra aprender.</p>

    <?php if ($flashMsg): ?>
      <div class="flash <?= e($flashType) ?>"><?= e($flashMsg) ?></div>
    <?php endif; ?>

    <section class="card">
      <h2>Nova tarefa</h2>
      <form method="POST">
        <input type="hidden" name="action" value="add">
        <input name="title" placeholder="Ex: Estudar arrays em PHP" autocomplete="off" />
        <button type="submit">Adicionar</button>
      </form>
    </section>

    <section class="card">
      <h2>Minhas tarefas</h2>

      <?php if (count($tasks) === 0): ?>
        <p class="muted">Nenhuma tarefa ainda. Crie a primeira ali em cima.</p>
      <?php else: ?>
        <ul class="list">
          <?php foreach ($tasks as $t): ?>
            <li class="item <?= $t['done'] ? 'done' : '' ?>">
              <div class="title">
                <?= e($t['title']) ?>
                <small class="muted">#<?= (int)$t['id'] ?></small>
              </div>

              <div class="actions">
                <form method="POST" class="inline">
                  <input type="hidden" name="action" value="toggle">
                  <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
                  <button type="submit"><?= $t['done'] ? 'Desfazer' : 'Concluir' ?></button>
                </form>

                <form method="POST" class="inline" onsubmit="return confirm('Excluir tarefa?');">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="id" value="<?= (int)$t['id'] ?>">
                  <button type="submit" class="danger">Excluir</button>
                </form>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </section>

    <footer class="muted">
      Dica: depois a gente evolui isso pra SQLite, rotas bonitas e MVC.
    </footer>
  </main>
</body>
</html>
