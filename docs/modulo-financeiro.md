# Módulo Financeiro — Informatica Livre BR

Módulo de gestão financeira pessoal/empresarial: categorias, despesas e receitas fixas, lançamentos mensais (gerados e avulsos), orçamento por categoria, dashboard com gráficos, fluxo de caixa projetado, relatórios e alertas de vencimento via Telegram.

> **Stack:** PHP 8.3 · Laravel 12 · Livewire 3 · TailwindCSS · AlpineJS · Chart.js (CDN) · spatie/laravel-permission · Font Awesome 5 (AdminLTE)

---

## 1. Tabelas (5)

### `financial_categories`
Categorias de receitas/despesas.

| Coluna | Tipo | Obs |
|---|---|---|
| id | bigint PK | |
| user_id | bigint FK → users | ON DELETE CASCADE |
| name | varchar(191) | |
| type | varchar(191) | `income` \| `expense` |
| color | varchar(191) | default `#00ff41` |
| icon | varchar(191) null | classe Font Awesome |
| active | tinyint(1) | default 1 |
| timestamps | | |

Índice: `(user_id, type)`.

### `financial_expenses`
Despesas fixas recorrentes — **fonte** dos lançamentos mensais.

| Coluna | Tipo | Obs |
|---|---|---|
| id | bigint PK | |
| user_id | bigint FK → users | CASCADE |
| category_id | bigint FK → financial_categories | CASCADE |
| description | varchar(191) | |
| due_day | tinyint unsigned | dia do vencimento (1–28) |
| amount | decimal(10,2) | default 0 |
| notes | text null | |
| active | tinyint(1) | default 1 |
| timestamps | | |

Índice: `(user_id, active)`.

### `financial_incomes`
Receitas fixas recorrentes — **fonte** dos lançamentos mensais. Mesma estrutura de `financial_expenses`, com `receive_day` no lugar de `due_day`.

### `financial_transactions`
Lançamentos mensais. São **gerados** a partir de despesas/receitas fixas ou **criados avulsos** (sem vínculo).

| Coluna | Tipo | Obs |
|---|---|---|
| id | bigint PK | |
| user_id | bigint FK → users | CASCADE |
| type | varchar(191) | `income` \| `expense` |
| category_id | bigint FK → financial_categories | CASCADE |
| expense_id | bigint FK → financial_expenses null | vínculo com despesa fixa (null = avulso) |
| income_id | bigint FK → financial_incomes null | vínculo com receita fixa (null = avulso) |
| year | smallint unsigned | |
| month | tinyint unsigned | 1–12 |
| amount | decimal(10,2) | |
| status | varchar(191) | `pending` \| `paid` \| `received` \| `cancelled` |
| paid_at | timestamp null | |
| received_at | timestamp null | |
| notes | text null | |
| description | varchar(191) null | descrição avulsa / override da fixa |
| payment_method | varchar(191) null | `pix` \| `boleto` \| `cartao` \| `transferencia` \| `dinheiro` |
| due_date | date null | vencimento customizado (avulsos) |
| attachment_path | varchar(191) null | comprovante anexado (storage public) |
| due_notified_at | timestamp null | controle de alerta de vencimento |
| invoice_id | bigint FK → invoices null | integração com faturas (ON DELETE SET NULL) |
| timestamps | | |

Uniques:
- `(user_id, type, year, month, expense_id)` — evita duplicar lançamento da mesma despesa no mês
- `(user_id, type, year, month, income_id)` — idem para receita
- `(invoice_id)` — 1 fatura = 1 lançamento

Índices: `(user_id, year, month)` e `(user_id, type, year)`.

### `financial_budgets`
Orçamento mensal por categoria (controle de gasto).

| Coluna | Tipo | Obs |
|---|---|---|
| id | bigint PK | |
| user_id | bigint FK → users | CASCADE |
| category_id | bigint FK → financial_categories | CASCADE |
| year | smallint unsigned | |
| month | tinyint unsigned | |
| amount | decimal(10,2) | |
| timestamps | | |

Unique: `(user_id, category_id, year, month)`.

---

## 2. Enums (`app/Enums/`)

### `FinancialType` (backed string)
- `INCOME = 'income'` → label "Receita"
- `EXPENSE = 'expense'` → label "Despesa"

### `FinancialStatus` (backed string)
- `PENDING = 'pending'` → "Pendente"
- `PAID = 'paid'` → "Pago"
- `RECEIVED = 'received'` → "Recebido"
- `CANCELLED = 'cancelled'` → "Cancelado"

Ambos com `label()` e `color()` em pt-BR.

---

## 3. Models (`app/Models/`)

### `FinancialCategory`
- `$fillable`: user_id, name, type, color, icon, active
- `$casts`: type → FinancialType, active → boolean
- Relações: `expenses()`, `incomes()`, `transactions()`

### `FinancialExpense` / `FinancialIncome`
- `$fillable`: user_id, category_id, description, due_day/receive_day, amount, notes, active
- `$casts`: amount → `decimal:2`, active → boolean
- Relação: `category()`

### `FinancialTransaction`
- `$fillable`: user_id, type, category_id, expense_id, income_id, invoice_id, year, month, amount, status, paid_at, received_at, notes, description, payment_method, due_date, attachment_path, due_notified_at
- `$casts`: type → FinancialType, status → FinancialStatus, amount → `decimal:2`, due_date → date, due_notified_at → datetime
- Relações: `category()`, `expense()`, `income()`, `invoice()`
- Scopes: `expenses()`, `incomes()`, `forYear()`, `forMonth()`, `pending()`
- Métodos de domínio:
  - `isExpense()` / `isIncome()`
  - `isOverdue()` — pendente e vencida
  - `dueDate()` / `receiveDate()` — usa `due_date` se houver, senão o dia fixo da fonte
  - `descriptionText()` — descrição avulsa → fixa → "—"
  - `isAdHoc()` — sem expense_id/income_id/invoice_id
  - `daysUntilDue()` — dias até o vencimento (negativo = atrasado)
  - `paymentMethodLabel()` — label pt-BR do método
  - `markAsPaid()` / `undoPayment()` / `markAsReceived()` / `undoReceipt()` — com validações e `\Exception` pt-BR
  - `cancelTransaction()` — marca cancelado
  - `generateForYear($year)` / `generateForMonth($year, $month)` — gera lançamentos a partir das fixas ativas (idempotente)

### `FinancialBudget`
- `$fillable`: user_id, category_id, year, month, amount
- `$casts`: amount → `decimal:2`
- Relação: `category()`

> Todos usam o trait `App\Traits\BelongsToUser` (escopo global `user_id = auth()->id()`). Em contexto sem auth (webhook/command), usar `withoutGlobalScope('user')`.

---

## 4. Livewire — Componentes e Rotas (`/admin`)

| Rota | Componente | View | Função |
|---|---|---|---|
| `/financeiro` | `Financial\Dashboard` | `financial/dashboard` | Resumo do mês, indicadores, gráficos Chart.js, fluxo de caixa projetado (6 meses), comparação anual |
| `/financeiro/lancamentos` | `Financial\Transactions` | `financial/transactions` | Lançamentos do mês (despesas/receitas), gerar mês/ano, filtro por status, exportar CSV, CRUD avulso |
| `/financeiro/despesas` | `Financial\Expenses` | `financial/expenses` | Lista de despesas fixas |
| `/financeiro/despesas/cadastrar` | `Financial\ExpenseForm` | `financial/expense-form` | Criar despesa fixa |
| `/financeiro/despesas/{expense}/editar` | `Financial\ExpenseForm` | `financial/expense-form` | Editar despesa fixa |
| `/financeiro/receitas` | `Financial\Incomes` | `financial/incomes` | Lista de receitas fixas |
| `/financeiro/receitas/cadastrar` | `Financial\IncomeForm` | `financial/income-form` | Criar receita fixa |
| `/financeiro/receitas/{income}/editar` | `Financial\IncomeForm` | `financial/income-form` | Editar receita fixa |
| `/financeiro/categorias` | `Financial\Categories` | `financial/categories` | CRUD de categorias + orçamento mensal por categoria |
| `/financeiro/anual` | `Financial\AnnualView` | `financial/annual` | Visão anual |
| `/financeiro/relatorios` | `Financial\Report` | `financial/report` | Relatórios (mensal/trimestral/semestral/anual) |
| `/financeiro/relatorios/exportar` | `FinancialReportController@export` | — | Exportação |

### Destaques do `Transactions`
- **Lançamento avulso**: modal com tipo, categoria, descrição, valor, mês/ano, vencimento, método de pagamento, data efetiva (marca pago/recebido) e comprovante (upload até 2MB, `storage/app/public/financial/attachments`).
- **Editar lançamento**: descrição, categoria, mês/ano, valor, método, vencimento, data efetiva, comprovante. Verifica conflito de unicidade ao mover mês/ano de lançamento recorrente.
- **Excluir**: apenas avulsos (recorrentes usam "cancelar").
- **Badge "vencendo em X dias"** nas despesas pendentes.
- **Exportar CSV** do mês (respeita o filtro de status).

### Destaques do `Dashboard`
- Cards: receitas/despesas/saldo do mês + 5 indicadores (pendentes, vencidas, pagas, esperadas, recebidas).
- Gráficos Chart.js: barras "Receitas x Despesas" (ano) e doughnut "Despesas por categoria" (mês). Re-render via `wire:key` + Alpine `x-init`.
- Fluxo de caixa projetado: próximos 6 meses (pendentes).
- Ações rápidas: marcar todas as despesas pagas / receitas recebidas.

### Destaques do `Categories`
- CRUD com cor/ícone/ativo.
- **Orçamento**: modal por mês/ano + barra de progresso "gasto vs orçamento" na listagem.

---

## 5. Serviços (`app/Services/`)

### `FinancialReportService`
- `build($year, $period, $month, $quarter, $half)` → totais, saldo, despesas/receitas por categoria, lista de lançamentos.
- Períodos: mensal, trimestral, semestral, anual.

---

## 6. Notificações e Agendamento

### `FinancialDueNotification` (`app/Notifications/`)
- Canais: `database` + `telegram`.
- Tipos: `overdue` (vencida) e `soon` (a vencer).
- `toTelegram()` monta mensagem com descrição, vencimento e valor.
- URL: `route('financial.transactions')`.

### Command `financial:check-due` (`app/Console/Commands/`)
- Assinatura: `financial:check-due {--days=3}`
- Busca despesas pendentes vencidas ou a vencer em até N dias.
- Dedup: só notifica se `due_notified_at` for null ou > 24h; depois grava `due_notified_at = now()`.
- Notifica todos os usuários com role `super-admin`.
- Agendado no `Kernel.php`: `$schedule->command('financial:check-due')->dailyAt('08:00')->withoutOverlapping();`

### Canal Telegram
- `App\Services\TelegramService` — `sendMessage()`/`sendHtml()` via `config('services.telegram.bot_token')` e `chat_id`.
- `App\Notifications\Channels\TelegramChannel` — canal customizado com dedup por `spl_object_hash`.

---

## 7. Integração com Faturas (PagHiper) — "fatura paga vira receita"

- `invoices` ganha: `gateway_fee`, `gateway_net_amount`, `payment_method`.
- `financial_transactions.invoice_id` vincula o lançamento à fatura (unique).
- `Invoice::registerFinancialIncome()`:
  - Cria/retorna o lançamento de receita (idempotente).
  - Categoria "Faturas" (income) criada automaticamente se não existir.
  - Valor = `gateway_net_amount ?? amount` (líquido da taxa).
  - Status `received`, mês/ano atuais, notes "Fatura #id".
  - Usa `withoutGlobalScope('user')` (chamado por webhook sem auth).
- Chamado em: `PagHiperWebhookController` (markAsPaid), `InvoicesIndex::markAsPaid`, `PagHiperService::syncInvoice`.
- Estorno (`refunded`): cancela o lançamento vinculado.

---

## 8. Como portar para outro projeto

1. **Banco**: importar `database/sql/modulo-financeiro.sql` no phpMyAdmin (ou rodar as migrations). O script cria as 5 tabelas e funciona **sem** depender de `invoices`; a integração com faturas fica numa seção comentada no final (descomente só se o projeto tiver a tabela `invoices`).
2. **Enums**: copiar `FinancialType` e `FinancialStatus`.
3. **Models**: copiar os 5 models + trait `BelongsToUser` (se não existir).
4. **Livewire**: copiar `app/Livewire/Dashboard/Financial/*` e as views `resources/views/livewire/dashboard/financial/*`.
5. **Rotas**: registrar as rotas do item 4 dentro do grupo `/admin` (auth + verified).
6. **Menu**: adicionar o submenu "Financeiro" na sidebar (itens: Dashboard, Lançamentos, Despesas, Receitas, Categorias, Anual, Relatórios).
7. **Serviços**: copiar `FinancialReportService`.
8. **Notificações**: copiar `FinancialDueNotification`, `TelegramService`, `TelegramChannel` + config `services.telegram`.
9. **Command**: copiar `CheckDueFinancialTransactions` e agendar no `Kernel`.
10. **Chart.js**: garantir o CDN no layout (`https://cdn.jsdelivr.net/npm/chart.js`) e `@stack('scripts')`.
11. **Uploads**: garantir `php artisan storage:link` (comprovantes).
12. **Integração com faturas** (opcional): aplicar o ALTER em `invoices` e copiar `Invoice::registerFinancialIncome()` + ajustes no webhook.

---

## 9. Testes

- `tests/Feature/Livewire/Dashboard/Financial/FinancialCrudTest.php` — CRUD de categorias/despesas/receitas.
- `tests/Feature/Livewire/Dashboard/Financial/FinancialQuickActionsTest.php` — ações rápidas do dashboard.
- `tests/Feature/Livewire/Dashboard/Financial/FinancialTransactionsTest.php` — avulsos, edição, exclusão, filtro, CSV, orçamento, command de vencimento, dashboard.
- `tests/Feature/Livewire/Dashboard/Financial/FinancialInvoiceIncomeTest.php` — integração fatura → receita.
- `tests/Feature/Livewire/Dashboard/Financial/FinancialReportTest.php` — relatórios.

Rodar: `vendor/bin/phpunit` (ou `php artisan test`).