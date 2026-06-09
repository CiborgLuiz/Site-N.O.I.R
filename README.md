# 👤 N.O.I.R — Núcleo de Operações e Investigações de Rupturas

> **“A realidade não quebrou sozinha. Nós estamos observando.”**

O **N.O.I.R** é um site narrativo e interativo desenvolvido em **Laravel**, criado para servir como a **interface oficial de uma organização fictícia** responsável por monitorar, conter e investigar **anomalias, rupturas temporais e entidades fora do padrão da realidade**.

Este projeto faz parte do ecossistema do **N.O.I.R SMP**, misturando **lore, ARG, horror analógico e sistemas interativos**, simulando um **sistema operacional antigo** acessível via navegador.

---

## 🧠 Conceito

O site foi projetado como se fosse:
- Um **portal institucional real**
- Um **arquivo confidencial**
- Um **sistema interno acessível apenas por credenciais**

## 🧩 Funcionalidades Principais

### 🌐 Site Institucional
- Página inicial com fundo interativo
- Seção **A Organização**
- Protocolos e comunicados oficiais
- Arquivos classificados protegidos por senha

---

## 🛠️ Tecnologias Utilizadas

- **Laravel 12**
- **PHP 8.5**
- **PostgreSQL/MySQL remoto**
- **Vercel Serverless Functions**
- **Storage S3/R2 para uploads**
- Blade Templates
- JavaScript puro (vanilla)
- HTML5 + CSS3
- Canvas API (fundo interativo)
- Cloudflare Tunnel (exposição pública local)

---

## 🚀 Deploy na Vercel

O projeto já inclui `vercel.json`, `api/index.php` e `.vercelignore`. A Vercel vai compilar os assets com Vite e enviar todas as rotas para o Laravel via `vercel-php`.

### 1. Banco de dados

Use um banco remoto. O caminho mais simples é PostgreSQL em Neon, Supabase ou outro provedor compatível.

Variáveis:

```env
DB_CONNECTION=pgsql
DATABASE_URL=postgres://usuario:senha@host:5432/database?sslmode=require
DB_SSLMODE=require
```

`DB_URL` ou `POSTGRES_URL` também funcionam se o provedor injetar esses nomes.

### 2. Storage para uploads

Uploads feitos pelo painel admin não devem ir para o disco da Vercel. Configure um bucket S3 compatível, como Cloudflare R2.

Exemplo para R2:

```env
UPLOADS_DISK=s3
UPLOADS_PATH_PREFIX=uploads
ADMIN_UPLOAD_MAX_KB=3072
AWS_ACCESS_KEY_ID=sua_access_key
AWS_SECRET_ACCESS_KEY=sua_secret_key
AWS_DEFAULT_REGION=auto
AWS_BUCKET=noir-uploads
AWS_ENDPOINT=https://SEU_ACCOUNT_ID.r2.cloudflarestorage.com
AWS_URL=https://seu-dominio-publico-do-bucket
AWS_USE_PATH_STYLE_ENDPOINT=true
```

O bucket precisa ter leitura pública pelo domínio informado em `AWS_URL`.

### 3. Variáveis obrigatórias na Vercel

Gere a chave do Laravel localmente:

```bash
php artisan key:generate --show
```

Cadastre no painel da Vercel:

```env
APP_KEY=base64:...
APP_URL=https://seu-projeto.vercel.app
APP_ENV=production
APP_DEBUG=false
ARCHIVE_ACCESS_PASSWORD=sua_senha_dos_arquivos
DB_CONNECTION=pgsql
DATABASE_URL=...
DB_SSLMODE=require
UPLOADS_DISK=s3
AWS_ACCESS_KEY_ID=...
AWS_SECRET_ACCESS_KEY=...
AWS_DEFAULT_REGION=auto
AWS_BUCKET=...
AWS_ENDPOINT=...
AWS_URL=...
AWS_USE_PATH_STYLE_ENDPOINT=true
```

Sessão, cache, fila, log e caminhos temporários já estão definidos em `vercel.json`.

### 4. Deploy

Pelo GitHub, importe o repositório na Vercel e mantenha as configurações do projeto como estão. Pelo terminal:

```bash
npm i -g vercel
vercel login
vercel
vercel --prod
```

### 5. Migração e seed do banco

Depois de configurar as variáveis, rode as migrations contra o banco remoto. Uma forma prática é puxar as envs da Vercel e exportar no terminal:

```bash
vercel env pull .env.vercel
set -a
source .env.vercel
set +a
php artisan migrate --force
php artisan db:seed --force
```

O seed cria/atualiza a senha de acesso da área de arquivos usando `ARCHIVE_ACCESS_PASSWORD`. A conta dona do admin é criada no primeiro acesso em `/admin`.

### 6. Limites importantes

A Vercel limita payloads de funções serverless. Por isso o painel limita uploads novos a aproximadamente 3 MB por arquivo. Arquivos grandes que já estão versionados em `public/` continuam sendo servidos como assets estáticos.

---


## 🔐 Sistema de Acesso aos Arquivos

- Página **Arquivos** protegida por senha
- Senha armazenada com **hash no banco de dados**
- Acesso correto libera a listagem completa de arquivos classificados

---

## 🧩 Comunidade Oficial (Discord)

A investigação continua fora do site.

📡 **Acesse o servidor oficial da N.O.I.R no Discord:**
👉 [Discord](https://discord.gg/cXde9v6X64)

Lá você encontrará:

* Atualizações da lore
* Comunicados internos
* Eventos narrativos
* Discussões e teorias
* Acesso antecipado a conteúdos classificados

---

## ⚠️ Aviso Narrativo

Este projeto é **inteiramente fictício**.
Qualquer semelhança com organizações reais, eventos históricos ou entidades existentes é **mera coincidência narrativa**.

Você **não deveria estar aqui**.
Mas agora que está… **a N.O.I.R está observando**.

---

## 🧬 Autor

* **Site criado por:** CiborgLuiz
* **Projeto criado por:** CiborgLuiz, Daniilouw, Vr0zt
* **Projeto:** N.O.I.R SMP
* **Status:** Em desenvolvimento contínuo

---

## 📜 Licença

Este projeto é de uso **criativo, narrativo e experimental**.
A licença poderá ser ajustada conforme o uso futuro.


---
