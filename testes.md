# 🧪 Plano de Testes Automatizados - Senai Barbearia

Este repositório contém a suíte de testes funcionais para o sistema **Senai Barbearia**, desenvolvida para ser executada com a extensão **Selenium IDE**. O objetivo é garantir a integridade das funcionalidades críticas, como agendamento dinâmico, validação de formulários e navegação.

---

## 🚀 Como Preparar o Ambiente

1. **Instale o Selenium IDE**:
   - [Para Google Chrome](https://chrome.google.com/webstore/detail/selenium-ide/mooikfkahbdpdpjjallocaolgcokghbe)
   - [Para Mozilla Firefox](https://addons.mozilla.org/pt-BR/firefox/addon/selenium-ide/)

2. **Importe os Testes**:
   - Abra a extensão no seu navegador.
   - Clique em **"Open an existing project"**.
   - Selecione o arquivo `senai-barbearia-user.side` ou `senia-barbearia-adm.side`

3. **Configure a URL**:
   - No campo **Playback Base URL** (no topo do Selenium IDE), insira o link onde o seu projeto está hospedado.
   - *Exemplo:* `http://localhost/projeto-barbearia/`

---

## 📋 Detalhamento dos Casos de Teste de USUÁRIO

Abaixo, a descrição do que cada teste valida no sistema:

| ID | Caso de Teste | Página | Validação Principal |
| :--- | :--- | :--- | :--- |
| **CT01** | **Agendamento Dinâmico** | `agendar.html` | Verifica se a lista de profissionais e horários carrega via Fetch API após selecionar um serviço. |
| **CT02** | **Visibilidade de Senha** | `registro.html` | Testa se o ícone de "olho" alterna o tipo do input entre `password` e `text`. |
| **CT03** | **Cancelamento** | `meus-horarios.html` | Garante que o sistema solicita confirmação do usuário antes de cancelar um registro. |
| **CT04** | **Bloqueio de Datas** | `agendar.html` | Verifica se o sistema impede o carregamento de slots de horários para datas no passado. |
| **CT05** | **Carrossel Automático** | `home.html` | Testa se o `setInterval` do JavaScript troca as imagens de fundo a cada 5 segundos. |

## 🛠️ Testes da Área Administrativa (Back-office)

Estes testes garantem que os gestores da barbearia conseguem operar o sistema sem erros de interface:

- **Gestão de CRUD**: Testes de criação e exclusão de serviços/profissionais.
- **Controlo de Disponibilidade**: Testes de bloqueio de horários para impedir agendamentos indevidos.
- **Monitorização**: Validação do carregamento da agenda em tempo real no Dashboard.

| ID | Caso de Teste | Página Alvo | Validação Principal | Resultado Esperado |
| :--- | :--- | :--- | :--- | :--- |
| **CT01** | **Login** | `servicos-adm.html` | Fazer login no site. | A tela de administradores deve aparecer. |
| **CT02** | **Adicionar Serviço** | `servicos-adm.html` | Cadastro de novo serviço via Modal. | O serviço deve aparecer na tabela após o salvamento. |
| **CT03** | **Bloqueio de Horário** | `horarios-adm.html` | Impedir agendamentos em faixas específicas. | O bloqueio deve ser registrado na lista de exceções. |
| **CT04** | **Monitoria da Agenda** | `agenda-adm.html` | Carregamento de dados de clientes agendados. | A tabela deve listar nome, serviço e hora via Fetch API. |
| **CT05** | **Navegação Sidebar** | Global Admin | Links de redirecionamento do menu lateral. | O clique deve levar à página correta e alterar o título da guia. |

---

## ⚠️ Observações para o Testador

- **Velocidade**: Se o navegador fechar muito rápido ou "atropelar" as animações, reduza a velocidade no ícone do relógio (**Speed Slider**) no Selenium IDE.
- **Banco de Dados**: Certifique-se de que o servidor Apache/MySQL esteja ligado, pois os testes CT01 e CT03 dependem de dados reais vindos do backend.
- **Limpeza**: Após rodar o teste de cancelamento, o registro sumirá da tela conforme esperado pela lógica do sistema.

---
Desenvolvido para fins de testes de qualidade (QA) no projeto Senai Barbearia.