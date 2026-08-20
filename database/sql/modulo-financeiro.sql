-- ============================================================================
-- MÓDULO FINANCEIRO — Informatica Livre BR
-- Script SQL para importar direto no phpMyAdmin (MySQL/MariaDB)
-- ----------------------------------------------------------------------------
-- ATENÇÃO:
-- 1. As tabelas dependem da tabela `users` (padrão Laravel). Importe depois
--    de criar o projeto/banco base.
-- 2. A integração com faturas (invoices) é OPCIONAL — veja a seção no final.
-- 3. Execute o script inteiro de uma vez no phpMyAdmin (aba "SQL").
-- ============================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------------------------------------------------------
-- 1. financial_categories — categorias de receitas/despesas
-- ----------------------------------------------------------------------------
CREATE TABLE `financial_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `type` varchar(191) NOT NULL COMMENT 'income | expense',
  `color` varchar(191) NOT NULL DEFAULT '#00ff41',
  `icon` varchar(191) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `financial_categories_user_id_type_index` (`user_id`,`type`),
  CONSTRAINT `financial_categories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 2. financial_expenses — despesas fixas recorrentes (fonte dos lançamentos)
-- ----------------------------------------------------------------------------
CREATE TABLE `financial_expenses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `description` varchar(191) NOT NULL,
  `due_day` tinyint(3) unsigned NOT NULL COMMENT 'dia do vencimento (1-28)',
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `financial_expenses_category_id_foreign` (`category_id`),
  KEY `financial_expenses_user_id_active_index` (`user_id`,`active`),
  CONSTRAINT `financial_expenses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `financial_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `financial_expenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 3. financial_incomes — receitas fixas recorrentes (fonte dos lançamentos)
-- ----------------------------------------------------------------------------
CREATE TABLE `financial_incomes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `description` varchar(191) NOT NULL,
  `receive_day` tinyint(3) unsigned NOT NULL COMMENT 'dia do recebimento (1-28)',
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `financial_incomes_category_id_foreign` (`category_id`),
  KEY `financial_incomes_user_id_active_index` (`user_id`,`active`),
  CONSTRAINT `financial_incomes_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `financial_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `financial_incomes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 4. financial_transactions — lançamentos mensais (gerados + avulsos)
-- ----------------------------------------------------------------------------
CREATE TABLE `financial_transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `type` varchar(191) NOT NULL COMMENT 'income | expense',
  `category_id` bigint(20) unsigned NOT NULL,
  `expense_id` bigint(20) unsigned DEFAULT NULL COMMENT 'vínculo com despesa fixa (null = avulso)',
  `income_id` bigint(20) unsigned DEFAULT NULL COMMENT 'vínculo com receita fixa (null = avulso)',
  `year` smallint(5) unsigned NOT NULL,
  `month` tinyint(3) unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` varchar(191) NOT NULL DEFAULT 'pending' COMMENT 'pending | paid | received | cancelled',
  `paid_at` timestamp NULL DEFAULT NULL,
  `received_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `description` varchar(191) DEFAULT NULL COMMENT 'descrição avulsa/override',
  `payment_method` varchar(191) DEFAULT NULL COMMENT 'pix | boleto | cartao | transferencia | dinheiro',
  `due_date` date DEFAULT NULL COMMENT 'vencimento customizado (avulsos)',
  `attachment_path` varchar(191) DEFAULT NULL COMMENT 'comprovante anexado',
  `due_notified_at` timestamp NULL DEFAULT NULL COMMENT 'controle de alerta de vencimento',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `invoice_id` bigint(20) unsigned DEFAULT NULL COMMENT 'vínculo com fatura (integração opcional)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `fin_tx_exp_unique` (`user_id`,`type`,`year`,`month`,`expense_id`),
  UNIQUE KEY `fin_tx_inc_unique` (`user_id`,`type`,`year`,`month`,`income_id`),
  UNIQUE KEY `fin_tx_invoice_unique` (`invoice_id`),
  KEY `financial_transactions_category_id_foreign` (`category_id`),
  KEY `financial_transactions_expense_id_foreign` (`expense_id`),
  KEY `financial_transactions_income_id_foreign` (`income_id`),
  KEY `financial_transactions_user_id_year_month_index` (`user_id`,`year`,`month`),
  KEY `financial_transactions_user_id_type_year_index` (`user_id`,`type`,`year`),
  CONSTRAINT `financial_transactions_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `financial_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `financial_transactions_expense_id_foreign` FOREIGN KEY (`expense_id`) REFERENCES `financial_expenses` (`id`) ON DELETE CASCADE,
  CONSTRAINT `financial_transactions_income_id_foreign` FOREIGN KEY (`income_id`) REFERENCES `financial_incomes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `financial_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------------------------------------------------------
-- 5. financial_budgets — orçamento mensal por categoria
-- ----------------------------------------------------------------------------
CREATE TABLE `financial_budgets` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned NOT NULL,
  `year` smallint(5) unsigned NOT NULL,
  `month` tinyint(3) unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fin_budget_unique` (`user_id`,`category_id`,`year`,`month`),
  KEY `financial_budgets_category_id_foreign` (`category_id`),
  CONSTRAINT `financial_budgets_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `financial_categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `financial_budgets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- INTEGRAÇÃO OPCIONAL COM FATURAS (invoices)
-- ----------------------------------------------------------------------------
-- Só execute esta seção se o projeto tiver a tabela `invoices` e quiser que
-- "fatura paga vire receita financeira" (com taxa da PagHiper).
-- Se a coluna já existir, o ALTER falha — remova a linha correspondente.
-- ============================================================================

-- ALTER TABLE `invoices`
--   ADD COLUMN `gateway_fee` decimal(10,2) DEFAULT NULL AFTER `boleto_barcode`,
--   ADD COLUMN `gateway_net_amount` decimal(10,2) DEFAULT NULL AFTER `gateway_fee`,
--   ADD COLUMN `payment_method` varchar(191) DEFAULT NULL AFTER `gateway_net_amount`;

-- ALTER TABLE `financial_transactions`
--   ADD CONSTRAINT `financial_transactions_invoice_id_foreign`
--   FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL;

SET FOREIGN_KEY_CHECKS = 1;