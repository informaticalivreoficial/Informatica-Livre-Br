@extends('errors.layout')

@section('code', '429')
@section('title', 'Muitas requisições')
@section('message', 'Você fez muitas requisições em um curto período. Aguarde alguns instantes e tente novamente.')
@section('icon', 'fas fa-tachometer-alt')
@section('iconBg', 'bg-pink-50 text-pink-600')