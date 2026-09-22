Sistema de Eventos SENAI

Projeto feito em PHP para uma atividade prática do SENAI. O sistema permite cadastrar, visualizar, editar e excluir eventos.

Tecnologias usadas:

PHP
HTML
CSS
$_SESSION
Git e GitHub

Funcionalidades:

Listagem de eventos
Visualização dos detalhes
Cadastro de eventos
Edição de eventos
Remoção de eventos
Confirmação antes de remover
Validação dos campos obrigatórios
Validação dos horários
Verificação de ID inexistente
Mensagem quando não existem eventos
Controle dos IDs pela sessão

Como executar:

É necessário ter o PHP instalado.

Abra o terminal dentro da pasta do projeto e execute:

php -S localhost:8000

Depois acesse no navegador:

http://localhost:8000/index.php

Como o projeto utiliza sessão, os testes devem ser feitos no mesmo navegador.

Arquivos:

init.php — inicia a sessão e cria os eventos iniciais.

menu.php — contém o menu de navegação.

style.css — contém os estilos da página.

index.php — mostra os eventos cadastrados.

detalhes.php — mostra os detalhes de um evento.

cadastro.php — permite cadastrar um novo evento.

edicao.php — permite editar um evento.

remocao.php — permite remover um evento.

Organização do Git:

A atividade foi feita pensando em quatro integrantes, mas como o grupo possui dois integrantes, foi dividido

Miguel 1:
Listagem, detalhes e cadastro.

Herny 2:
Edição e remoção.

Cada integrante deve trabalhar em sua própria branch, fazer os commits e depois abrir um Pull Request para juntar as alterações na branch main.

Testes:

Abrir a página inicial e conferir os eventos.

Cadastrar um novo evento e conferir se ele aparece na lista.

Abrir os detalhes do evento.

Editar o evento e verificar se o ID continua igual.

Abrir a remoção e cancelar.

Abrir novamente e confirmar a remoção.

Tentar acessar um ID que não existe, como detalhes.php?id=999.

Remover todos os eventos e verificar a mensagem de lista vazia.

Cadastrar outro evento depois de uma remoção e verificar se o ID foi criado corretamente.

Sessão:

Os eventos iniciais são criados somente quando a sessão ainda não possui eventos.

Por isso, alterações feitas nos eventos iniciais do init.php podem não aparecer se a sessão antiga ainda estiver ativa.

Para começar novamente com os eventos iniciais, é necessário encerrar ou limpar a sessão do navegador.