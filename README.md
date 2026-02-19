![Screenshot do app](assets/imagem_2026-02-19_152025559.png)

# PHP TODO (básico do básico)

Mini projeto para estudos e portfólio: uma lista de tarefas feita em **PHP puro (sem framework)**, com persistência em **JSON**.

## Funcionalidades
- Adicionar tarefa
- Concluir/Desfazer tarefa
- Excluir tarefa
- Mensagens de feedback (flash) via session

## Tecnologias
- PHP 8+
- HTML + CSS
- Armazenamento em `storage/data.json` (JSON)

## Como rodar localmente
1. Abra o terminal na pasta do projeto e rode:

    php -S localhost:8000 -t public

2. Acesse no navegador: http://localhost:8000


## Estrutura
- `public/index.php` — página e controle das ações (POST/GET)
- `src/TaskRepository.php` — leitura/escrita no JSON
- `src/helpers.php` — helpers (escape, redirect, flash)
- `storage/data.json` — “banco” local (ignorado pelo git)

## Próximos passos (ideias)
- Editar tarefa
- Filtrar (pendentes/concluídas)
- Persistência com SQLite
- Separar camadas (MVC)
