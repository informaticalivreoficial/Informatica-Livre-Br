<?php

use App\Http\Controllers\Admin\{
    BancoController,
    CatProdutoController,
    FaturaController,
    OrcamentoController,
    PedidoController,
    ProdutoController,
    ServicoController
};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('auth')->group( function(){

    //******************** Bancos *************************************************************/
    Route::get('bancos', [BancoController::class, 'index'])->name('bancos.index');
    Route::get('bancos/refresh', [BancoController::class, 'refresh'])->name('bancos.refresh');

    //******************** Vendas *************************************************************/
    Route::get('pedidos/faturas/{pedido}', [FaturaController::class, 'faturas'])->name('faturas.list');
    Route::delete('pedidos/fatura/deleteon', [FaturaController::class, 'deleteon'])->name('faturas.deleteon');
    Route::get('pedidos/fatura/delete', [FaturaController::class, 'delete'])->name('faturas.delete');
    Route::get('pedidos/sendFormFaturaClient', [FaturaController::class, 'sendFormFaturaClient'])->name('pedidos.sendFormFaturaClient');
    Route::post('vendas/faturas/store', [FaturaController::class, 'store'])->name('vendas.faturas.store');
    Route::get('vendas/faturas/create', [FaturaController::class, 'create'])->name('vendas.faturas.create');
    Route::get('vendas/faturas', [FaturaController::class, 'index'])->name('vendas.faturas');
    
    Route::get('pedidos/show/{id}', [PedidoController::class, 'show'])->name('pedidos.show');
    Route::get('pedidos/create', [PedidoController::class, 'create'])->name('pedidos.create');
    Route::post('pedidos/store', [PedidoController::class, 'store'])->name('pedidos.store');
    Route::post('pedidos/store-product', [PedidoController::class, 'storeProduct'])->name('pedidos.storeProduct');
    Route::post('pedidos/store-service', [PedidoController::class, 'storeService'])->name('pedidos.storeService');
    Route::put('pedidos/{id}', [PedidoController::class, 'update'])->name('pedidos.update');
    Route::put('pedidos-product/{id}', [PedidoController::class, 'updateProduct'])->name('pedidos.updateProduct');
    Route::put('pedidos-service/{id}', [PedidoController::class, 'updateService'])->name('pedidos.updateService');
    Route::get('pedidos/{id}/edit', [PedidoController::class, 'edit'])->name('pedidos.edit');
    Route::get('pedidos', [PedidoController::class, 'index'])->name('pedidos.index'); 
       
        
    Route::post('itemPedidos/store', [PedidoController::class, 'storeItem'])->name('storeItem.store');

    Route::delete('pedidos/deleteon', [PedidoController::class, 'deleteon'])->name('pedidos.deleteon');
    Route::get('pedidos/delete', [PedidoController::class, 'delete'])->name('pedidos.delete'); 
    Route::get('pedidos/set-status', [PedidoController::class, 'setStatus'])->name('pedidos.setStatus'); 
    Route::get('pedidos/set-gateway', [PedidoController::class, 'SetGateway'])->name('pedidos.SetGateway'); 
    Route::get('orcamentos/set-status', [OrcamentoController::class, 'orcamentoSetStatus'])->name('orcamento.orcamentoSetStatus'); 
    Route::delete('orcamentos/deleteon', [OrcamentoController::class, 'deleteon'])->name('orcamento.deleteon');
    Route::get('orcamentos/delete', [OrcamentoController::class, 'delete'])->name('orcamento.delete'); 
    Route::get('orcamentos', [OrcamentoController::class, 'index'])->name('vendas.orcamentos');
    Route::get('/sendFormCaptacaoClient', [OrcamentoController::class, 'sendFormCaptacaoClient'])->name('orcamento.sendFormCaptacaoClient');
    //Route::get('/testecron', [OrcamentoController::class, 'checkOrcamentoClient'])->name('orcamento.checkOrcamentoClient');

    //*************************** Produtos Categorias **********************************/
    Route::get('produtos/categorias/delete', [CatProdutoController::class, 'delete'])->name('produtos-categorias.delete');
    Route::delete('produtos/categorias/deleteon', [CatProdutoController::class, 'deleteon'])->name('produtos-categorias.deleteon');
    Route::get('produtos/categorias/{id}/edit', [CatProdutoController::class, 'edit'])->name('produtos-categorias.edit');
    Route::put('produtos/categorias/{id}', [CatProdutoController::class, 'update'])->name('produtos-categorias.update');
    Route::match(['post', 'get'],'produtos/categorias/create/{catpai}', [CatProdutoController::class, 'create'])->name('produtos-categorias.create');
    Route::post('produtos/categorias/store', [CatProdutoController::class, 'store'])->name('produtos-categorias.store');
    Route::get('produtos/categorias', [CatProdutoController::class, 'index'])->name('catprodutos.index');

    //*************************** Produtos *********************************************/
    Route::match(['get', 'post'], 'produtos/pesquisa', [ProdutoController::class, 'search'])->name('produtos.search');
    Route::get('produtos/set-status', [ProdutoController::class, 'produtoSetStatus'])->name('produtos.produtoSetStatus');
    Route::post('produtos/image-set-cover', [ProdutoController::class, 'imageSetCover'])->name('produtos.imageSetCover');
    Route::delete('produtos/image-remove', [ProdutoController::class, 'imageRemove'])->name('produtos.imageRemove');
    Route::delete('produtos/deleteon', [ProdutoController::class, 'deleteon'])->name('produtos.deleteon');
    Route::get('produtos/delete', [ProdutoController::class, 'delete'])->name('produtos.delete');
    Route::put('produtos/{id}', [ProdutoController::class, 'update'])->name('produtos.update');
    Route::get('produtos/{id}/edit', [ProdutoController::class, 'edit'])->name('produtos.edit');
    Route::get('produtos/create', [ProdutoController::class, 'create'])->name('produtos.create');
    Route::post('produtos/store', [ProdutoController::class, 'store'])->name('produtos.store');
    Route::get('produtos', [ProdutoController::class, 'index'])->name('produtos.index');

    //*************************** Serviços Categorias **********************************/
    Route::get('servicos/categorias/delete', [ServicoController::class, 'categoriaDelete'])->name('servicos-categorias.delete');
    Route::delete('servicos/categorias/deleteon', [ServicoController::class, 'categoriaDeleteon'])->name('servicos-categorias.deleteon');
    Route::get('servicos/categorias/{id}/edit', [ServicoController::class, 'categoriaEdit'])->name('servicos-categorias.edit');
    Route::put('servicos/categorias/{id}', [ServicoController::class, 'categoriaUpdate'])->name('servicos-categorias.update');
    Route::match(['post', 'get'],'servicos/categorias/create/{catpai}', [ServicoController::class, 'categoriaCreate'])->name('servicos-categorias.create');
    Route::post('servicos/categorias/store', [ServicoController::class, 'categoriaStore'])->name('servicos-categorias.store');
    Route::get('servicos/categorias', [ServicoController::class, 'categorias'])->name('servicos-categorias.index');

    //****************************** Serviços *********************************************/
    Route::match(['get', 'post'], 'servicos/pesquisa', [ServicoController::class, 'search'])->name('servicos.search');
    Route::get('servicos/set-status', [ServicoController::class, 'servicoSetStatus'])->name('servicos.servicoSetStatus');
    Route::post('servicos/image-set-cover', [ServicoController::class, 'imageSetCover'])->name('servicos.imageSetCover');
    Route::delete('servicos/image-remove', [ServicoController::class, 'imageRemove'])->name('servicos.imageRemove');
    Route::delete('servicos/deleteon', [ServicoController::class, 'deleteon'])->name('servicos.deleteon');
    Route::get('servicos/delete', [ServicoController::class, 'delete'])->name('servicos.delete');
    Route::put('servicos/{id}', [ServicoController::class, 'update'])->name('servicos.update');
    Route::get('servicos/{id}/edit', [ServicoController::class, 'edit'])->name('servicos.edit');
    Route::get('servicos/create', [ServicoController::class, 'create'])->name('servicos.create');
    Route::post('servicos/store', [ServicoController::class, 'store'])->name('servicos.store');
    Route::get('servicos', [ServicoController::class, 'index'])->name('servicos.index');

});

Auth::routes();