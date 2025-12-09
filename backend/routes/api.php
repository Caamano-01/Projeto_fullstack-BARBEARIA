<?php

require_once __DIR__ . '/../controllers/UsuariosController.php';
require_once __DIR__ . '/../controllers/ServicosController.php';
require_once __DIR__ . '/../controllers/ProfissionaisController.php';
require_once __DIR__ . '/../controllers/AgendamentosController.php';

$usuarios = new UsuariosController();
$servicos = new ServicosController();
$profissionais = new ProfissionaisController();
$agendamentos = new AgendamentosController();

/* Usuários */
Router::route("POST", fn() => $usuarios->registrar());
Router::route("POST", fn() => $usuarios->login());

/* Serviços */
Router::route("GET", fn() => $servicos->listar());

/* Profissionais */
Router::route("GET", fn() => $profissionais->listar());

/* Agendamentos */
Router::route("POST", fn() => $agendamentos->criar());
Router::route("GET", fn() => $agendamentos->listar());
