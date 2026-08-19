@extends('errors.layout')

@section('code', '401')
@section('title', 'Não autorizado')
@section('message', 'Você precisa estar autenticado para acessar esta página. Faça login e tente novamente.')
@section('icon', 'fas fa-lock')
@section('iconBg', 'bg-yellow-50 text-yellow-600')