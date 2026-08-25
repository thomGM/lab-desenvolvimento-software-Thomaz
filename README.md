# 1. Identificação do Projeto 
- Nome do Projeto: Software para gerenciamento de clientes e pacientes em serviços de home care.
- Autor: Thomaz Milkewicz 
- Orientador: Clarissa Castellã Xavier
- Data: 

# 2. Introdução e Visão Geral  
O sistema tem como principal objetivo resolver a falta de visibilidade sobre os cuidados prestados ao paciente,  integrando a equipe de cuidados e os familiares.

# 3. Objetivos do Projeto 
- Desenvolver um software para controle da rotina do paciente.
- Implementar funcionalidades que auxiliam a organização de medicações, rotinas e consultas médicas.
- Garantir que o sistema seja de fácil instalação e utilização, mesmo para usuários com pouca experiência técnica. 
 
# 4. Escopo do Projeto
O escopo do projeto está documentado no arquivo [ESCOPO.md](./ESCOPO.md)
 
# 5. Tecnologias

## Website
- HTML
- CSS
- Jquery
- PHP (POO)
- Banco de dados (MySQL)

# 6. Configuração da API Gemini
O relatório automático de cuidados usa a API do Google Gemini, que exige um token de acesso.

1. Copie `app/Core/config.example.php` para `app/Core/config.php`.
2. Gere um token em https://aistudio.google.com/apikey.
3. Defina o valor gerado na constante `GEMINI_API_KEY` dentro de `app/Core/config.php`.

O arquivo `app/Core/config.php` está no `.gitignore` e não deve ser commitado.