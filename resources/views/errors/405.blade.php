@extends('errors.layout')

@section('code', '405')
@section('title', 'Método não permitido')
@section('message', 'O método de requisição utilizado não é permitido para esta página. Utilize o link correto para continuar.')
@section('icon', 'fas fa-exchange-alt')
@section('iconBg', 'bg-orange-50 text-orange-600')