# RotaMP
RotaMP - Sistema de Agendamento de Motoristas - Sudoeste I Altamira

# Visão Geral

O sistema **RotaMP** é uma aplicação desenvolvida para gerenciamento de solicitações de veículos e acompanhamento de percursos realizados pelos motoristas do Ministério Público do Estado do Pará - Polo Regional de Altamira.
Foi idealizado para otimizar a comunicação entre solicitantes, motoristas e fiscais de contrato, garantindo rastreabilidade, economia e confiabilidade no uso da frota oficial.

# Objetivos

- Facilitar o agendamento e gestão das viagens institucionais.
- Centralizar as informações de deslocamento.
- Oferecer painel de controle e filtros para auditoria e fiscalização.
- Gerar comprovantes de circulação com possibilidade de impressão e assinatura.

# Justificativa

A ausência de um sistema estruturado de controle de frota institucional resultava em falhas de comunicação, perda de dados e dificuldade na fiscalização das viagens realizadas. O sistema surge como solução prática, segura e replicável para todo o Estado.

# Tecnologias Utilizadas

- PHP 8.x
- MySQL 8.x
- Bootstrap 5.3
- FullCalendar.js
- Git + GitHub
- Servidor Ubuntu (via Proxmox)

# Perfis de Usuário

- **Administrador**: Gerencia usuários, cidades, promotorias, acessa relatórios e visualiza todas as solicitações.
- **Solicitante**: Realiza pedidos de viagem, acompanha status e assina comprovantes.
- **Motorista**: Visualiza suas viagens, registra odômetro e assina o percurso realizado.
- **Fiscal**: Valida as viagens finalizadas, assina os comprovantes e gera relatórios.

# Regras de Negócio

- Cada motorista só pode estar vinculado a um veículo por vez.
- Um motorista não pode ser agendado para dois percursos com horários sobrepostos.
- Comprovantes só podem ser visualizados para impressão após as três assinaturas.
- O sistema só exibe para o usuário as solicitações vinculadas a ele.
- Calendário exibe todas as viagens para todos os perfis.

# Funcionalidades

- Cadastro de usuários com vínculo à promotoria.
- Cadastro de cidades e promotorias.
- Solicitação de viagens com seleção de motorista e destino.
- Painel do motorista com registro de odômetro e tempo de operação.
- Geração automática de comprovante de circulação.
- Assinatura digital simples (registro de data, hora e autor).
- Filtros de relatórios por período, motorista e status.
- Exportação e impressão de relatórios e comprovantes com layout oficial.

# Fluxo Resumido

1. Solicitante faz login → cadastra solicitação.
2. Motorista vê a viagem → inicia percurso → finaliza percurso.
3. Comprovante é gerado automaticamente.
4. Assinaturas: motorista → solicitante → fiscal.
5. Após assinaturas, comprovante pode ser impresso.
6. Fiscal acessa painel e gera relatórios.

# Telas do Sistema

- Login
- Dashboard
- Cadastro de usuários
- Cadastro de cidades e promotorias
- Formulário de solicitação
- Painel do motorista
- Painel do fiscal
- Comprovante visual
- Calendário de viagens
- Relatórios e filtros

# Considerações Finais

O sistema RotaMP é flexível, replicável e pode ser facilmente adaptado para outros polos e instituições públicas. O código-fonte é de fácil manutenção e pode ser integrado a serviços como Active Directory e assinatura digital institucional no futuro.

# Nota Técnica

Este sistema foi desenvolvido por **Liliane de Freitas – Técnica em Tecnologia da Informação no MPPA**, com apoio de inteligência artificial como ferramenta de produtividade. A IA foi utilizada para auxílio na codificação, estruturação lógica, documentação técnica e boas práticas de desenvolvimento.

