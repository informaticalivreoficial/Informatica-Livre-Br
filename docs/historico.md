# Histórico de alterações — Informatica Livre BR

Registro cronológico das correções e melhorias aplicadas ao sistema.

---

## 2026-10-28 — Upload de imagens na loja e Open Graph do produto

Commits relacionados:

| Commit | Descrição |
|---|---|
| `bdacaf0` | fix imagem loja |
| `c7be3a8` | fix seo loja |

### 1. Upload múltiplo de imagens na loja

**Sintoma**

Ao enviar imagens no formulário de produto (`/admin/loja/produtos/{produto}/editar`), o Livewire retornava:

> S3 temporary file upload driver only supports single file uploads. Remove the [multiple] HTML attribute from your input tag.

**Causa**

O disco default da aplicação é o R2 (`FILESYSTEM_DRIVER=r2`), que usa o driver `s3`. O Livewire, sem configuração própria, usa o disco default para os uploads temporários e, ao detectar driver `s3`, bloqueia qualquer `<input type="file" multiple>` (`S3DoesntSupportMultipleFileUploads`).

O problema não estava no Blade: o input `multiple` em `resources/views/livewire/dashboard/loja/produto-form.blade.php` está correto e o envio múltiplo deve continuar funcionando.

**Solução**

- Criado `config/livewire.php` definindo o disco temporário como `local`:
  - `livewire.temporary_file_upload.disk = env('LIVEWIRE_TEMP_DISK', 'local')`
- Adicionado `LIVEWIRE_TEMP_DISK=local` ao `.env.example`.
- Os arquivos definitivos continuam indo para o R2 via `Storage::disk()` em `ProdutoForm::uploadImages()`.

**Arquivos**

| Arquivo | Alteração |
|---|---|
| `config/livewire.php` | novo — disco temporário `local` + configurações completas de upload |
| `.env.example` | nova variável `LIVEWIRE_TEMP_DISK` |

**Efeito colateral positivo**

Os demais formulários com upload múltiplo (`Posts/PostForm`, `Portifolio/PortifolioForm`) também foram desbloqueados.

**Deploy**

```bash
php artisan config:clear   # ou config:cache, conforme o processo de deploy
php artisan storage:link   # garantir o link do disco local
```

---

### 2. Open Graph da página de produto

**Sintoma**

Ao validar `/sistemas/{slug}` no Facebook Sharing Debugger, a página não apresentava metadados: sem título, sem descrição, sem imagem de capa.

**Causa**

1. `ProdutoController@show` retornava a view sem a variável `$head`, e o layout `resources/views/web/layouts/app.blade.php` renderiza as meta tags apenas via `{!! $head ?? '' !!}`. Resultado: nenhuma tag OG, nenhum `<title>`, nenhum canonical.
2. `App\Support\Seo` tinha o nome do site fixo como `"Hotel São Charbel"` no `openGraph()`.
3. `article:publisher` era emitido mesmo sem configuração, gerando `https://www.facebook.com/` inválido.
4. `fb:app_id` recebia valores não numéricos do `.env` (ex.: `ss`).
5. Não havia `twitter:card` / `twitter:image`.

**Solução**

- `ProdutoController@show`: monta `$head` com título, descrição (headline → descrição → conteúdo resumido → fallback), URL canônica da rota e `$produto->cover_url` como `og:image`.
- `ProdutoController@index`: também passou a gerar `$head`, usando a capa do primeiro produto como imagem padrão.
- `Seo`: nome do site via `config('app.name')`; tags de redes sociais emitidas somente quando configuradas; inclusão de Twitter Card; `fb:app_id` apenas se numérico.
- `config/services.php`: credenciais sociais (`CLIENT_SOCIAL_*`) movidas para `services.social.*`, funcionando mesmo com `config:cache`.

**Arquivos**

| Arquivo | Alteração |
|---|---|
| `app/Http/Controllers/Web/ProdutoController.php` | geração de `$head` em `index()` e `show()` |
| `app/Support/Seo.php` | site name dinâmico, tags sociais condicionais, Twitter Card |
| `config/services.php` | chaves `services.social.*` para Facebook e Twitter |

**Validação**

Renderização conferida via bootstrap da aplicação, com saída contendo:

```html
<title>Sistema Teste - Informática Livre</title>
<meta property="og:site_name" content="Informática Livre"/>
<meta property="og:title" content="Sistema Teste - Informática Livre"/>
<meta property="og:url" content="https://.../sistemas/teste"/>
<meta property="og:image" content="https://...r2.dev/informaticalivre/produtos/1/x.webp"/>
<meta name="twitter:card" content="summary_large_image"/>
<meta name="twitter:image" content="https://...r2.dev/informaticalivre/produtos/1/x.webp"/>
```

`php -l` executado nos arquivos alterados sem erros.

**Deploy**

```bash
php artisan config:clear   # ou config:cache
```

Revalidar a URL no Facebook Sharing Debugger. Se a capa não aparecer, confirmar que a URL de `og:image` abre publicamente no navegador (disco R2 com `R2_URL`/`R2_ROOT` corretos).

**Observação**

O ambiente local de desenvolvimento não possuía o driver `pdo_mysql`, portanto não foi possível executar a página fim a fim nem a suíte de testes; a validação foi feita por lint PHP e renderização isolada do `Seo`.

---

## Como registrar novas alterações

Adicione uma nova seção no topo, no formato:

```markdown
## AAAA-MM-DD — Título curto

**Sintoma** · **Causa** · **Solução** · **Arquivos** · **Deploy**
```

Mantenha os commits relacionados em tabela e registre comandos de deploy necessários (`config:clear`, migrations, etc.).
