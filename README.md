# API RESTful com Laravel 11 e PHP 8.2

## Justificativa para o Uso de Frameworks ou Bibliotecas

A escolha do Laravel como framework principal para a implementação desta API RESTful é fundamentada em vários fatores:

- **Familiaridade no Mercado:** Laravel é amplamente conhecido e utilizado, facilitando a contratação de novos profissionais sem a necessidade de treinamentos extensivos sobre o ambiente.
- **Comunidade e Documentação:** Laravel possui uma grande comunidade ativa e uma documentação abrangente, proporcionando suporte robusto e recursos atualizados.
- **Segurança e Escalabilidade:** Laravel oferece diversos recursos prontos que asseguram a segurança e escalabilidade da aplicação, permitindo que o foco da equipe permaneça nas regras de negócio.

## Decisões Técnicas e Arquiteturais

A arquitetura adotada para esta API é orientada para **DDD (Domain-Driven Design)** e **TDD (Test-Driven Development)**. Essas abordagens garantem que:

- As regras de negócio estejam centralizadas e bem definidas.
- A aplicação seja desenvolvida com foco na qualidade e conformidade das regras de negócio, assegurando que todos os endpoints funcionem corretamente.

Optou-se pelo uso do **SQLite** como banco de dados devido à sua simplicidade e facilidade de configuração, permitindo uma inicialização rápida do projeto sem complicações adicionais.

Para a autenticação, foi escolhido o **Laravel Passport** com o Grant Type Password, oferecendo uma solução completa e segura para a gestão de tokens de acesso.

Cada corrida possui um percentual de taxa e o valor da corrida é gerado aleatoriamente para ilustrar valores variáveis. O valor líquido que o motorista irá receber é o valor bruto menos a taxa.

## Instruções para Compilar e Executar o Projeto

### Pré-requisitos

- Docker
- Docker Compose

### Passos para Configuração e Execução

1. **Clonar o Repositório:**
    ```bash
    git clone https://github.com/pedro162/easy_trip_api
    cd easy_trip_api
    ```

2. **Configurar Variáveis de Ambiente:**
    - Copie o arquivo `.env.example` para `.env` e configure conforme necessário:
    ```bash
    cp .env.example .env
    ```

3. **Subir os Contêineres Docker:**
    ```bash
    docker-compose up -d
    ```

4. **Instalar as Dependências:**
    ```bash
    docker-compose exec app composer install
    ```

5. **Gerar a Key da Aplicação:**
    ```bash
    docker-compose exec app php artisan key:generate
    ```

6. **Rodar as Migrações:**
    ```bash
    docker-compose exec app php artisan migrate
    ```


7. **Caso seja necessário liberar permissões no diretório storage:**
    ```bash
    docker-compose exec app chown -R www-data:www-data /var/www/html/easy_trip_api/storage
    ```

8. **Iniciar o Servidor:**
    - A aplicação estará disponível em `http://localhost:9000`.

## Autenticação e Recursos da API

### Autenticação

Para autenticar-se na API, utilize o endpoint de login do Laravel Passport com Grant Type Password. As credenciais do usuário devem ser enviadas para obter o token de acesso.

- **Login:**
  - `POST: /api/login`
- **Logout:**
  - `POST: /api/logout`

### Recursos da API

#### Usuário

- Criar novo usuário do tipo cliente:
  - `POST: /api/user/store`
- Criar novo usuário do tipo motorista:
  - `POST: /api/user/driver/store`
- Outras rotas de usuário:
  - Listar usuários:
    - `GET: /api/user/index`
  - Atualizar usuário:
    - `PUT: /api/user/update/{id}`
  - Mostrar usuário:
    - `GET: /api/user/show/{id}`
  - Deletar usuário:
    - `DELETE: /api/user/destroy/{id}`

#### Viagem

- Listar viagens:
  - `GET: /api/trip/index`
- Criar nova viagem:
  - `POST: /api/trip/store`
- Atualizar viagem:
  - `PUT: /api/trip/update/{id}`
- Mostrar viagem:
  - `GET: /api/trip/show/{id}`
- Deletar viagem:
  - `DELETE: /api/trip/destroy/{id}`
- Iniciar viagem:
  - `PUT: /api/trip/start/{id}`
- Concluir viagem:
  - `PUT: /api/trip/complete/{id}`
- Cancelar viagem:
  - `PUT: /api/trip/cancel/{id}`

#### Pagamento

- Listar solicitações de pagamento:
  - `GET: /api/trip/payment/request/index`
- Criar solicitação de pagamento:
  - `POST: /api/trip/payment/request/store/{trip_id}`
- Atualizar solicitação de pagamento:
  - `PUT: /api/trip/payment/request/update/{id}`
- Mostrar solicitação de pagamento:
  - `GET: /api/trip/payment/request/show/{id}`
- Deletar solicitação de pagamento:
  - `DELETE: /api/trip/payment/request/destroy/{id}`

#### Conta Bancária

- Listar contas bancárias:
  - `GET: /api/bank/account/index`
- Criar conta bancária:
  - `POST: /api/bank/account/store/{owner_id}`
- Atualizar conta bancária:
  - `PUT: /api/bank/account/update/{id}`
- Mostrar conta bancária:
  - `GET: /api/bank/account/show/{id}`
- Deletar conta bancária:
  - `DELETE: /api/bank/account/destroy/{id}`

## Exemplos de Uso

### Autenticação

- **Login:**
    ```
    Header:
    POST /api/login
    Content-Type: application/json
    
    Body:
    {
      "email": "admin@gmail.com",
      "password": "123456"
    }
    ```

- **Logout:**
    ```
    Header:
    POST /api/logout
    Content-Type: application/json
    Authorization: Bearer {token}
    ```

### Usuário

- **Criar novo usuário do tipo cliente:**
    ```
    Header:
    POST /api/user/store
    Content-Type: application/json
    
    Body:
    {
      "name": "Cliente",
      "email": "cliente@gmail.com",
      "password": "123456"
    }
    ```

- **Criar novo usuário do tipo motorista:**
    ```
    Header:
    POST /api/user/driver/store
    Content-Type: application/json
    
    Body:
    {
      "name": "Motorista",
      "email": "motorista@gmail.com",
      "password": "123456"
    }
    ```

- **Listar usuários:**
    ```
    Header:
    GET /api/user/index
    Content-Type: application/json
    Authorization: Bearer {token}
    ```

- **Atualizar usuário:**
    ```
    Header:
    PUT /api/user/update/{id}
    Content-Type: application/json
    Authorization: Bearer {token}
    
    Body:
    {
      "name": "Novo Nome",
      "email": "novoemail@gmail.com"
    }
    ```

- **Mostrar usuário:**
    ```
    Header:
    GET /api/user/show/{id}
    Content-Type: application/json
    Authorization: Bearer {token}
    ```

- **Deletar usuário:**
    ```
    Header:
    DELETE /api/user/destroy/{id}
    Content-Type: application/json
    Authorization: Bearer {token}
    ```

### Viagem

- **Listar viagens:**
    ```
    Header:
    GET /api/trip/index
    Content-Type: application/json
    Authorization: Bearer {token}
    ```

- **Criar nova viagem:**
    ```
    Header:
    POST /api/trip/store
    Content-Type: application/json
    Authorization: Bearer {token}
    
    Body:
    {
      "client_id": 1,
      "starting_address": "Local A",
      "end_address": "Local B",
      "distance": 10
    }
    ```

- **Atualizar viagem:**
    ```
    Header:
    PUT /api/trip/update/{id}
    Content-Type: application/json
    Authorization: Bearer {token}
    
    Body:
    {
      "starting_address": "Local X",
      "end_address": "Local Y",
    }
    ```

- **Mostrar viagem:**
    ```
    Header:
    GET /api/trip/show/{id}
    Content-Type: application/json
    Authorization: Bearer {token}
    ```

- **Deletar viagem:**
    ```
    Header:
    DELETE /api/trip/destroy/{id}
    Content-Type: application/json
    Authorization: Bearer {token}
    ```

- **Iniciar viagem:**
    ```
    Header:
    PUT /api/trip/start/{id}
    Content-Type: application/json
    Authorization: Bearer {token}
    ```

- **Concluir viagem:**
    ```
    Header:
    PUT /api/trip/complete/{id}
    Content-Type: application/json
    Authorization: Bearer {token}
    ```

- **Cancelar viagem:**
    ```
    Header:
    PUT /api/trip/cancel/{id}
    Content-Type: application/json
    Authorization: Bearer {token}
    ```

### Pagamento

- **Listar solicitações de pagamento:**
    ```
    Header:
    GET /api/trip/payment/request/index
    Content-Type: application/json
    Authorization: Bearer {token}
    ```

- **Criar solicitação de pagamento:**
    ```
    Header:
    POST /api/trip/payment/request/store/{trip_id}
    Content-Type: application/json
    Authorization: Bearer {token}
    ```

- **Atualizar solicitação de pagamento:**
    ```
    Header:
    PUT /api/trip/payment/request/update/{id}
    Content-Type: application/json
    Authorization: Bearer {token}
    
    Body:
    {
      "reques_description": "Descrição da solicitação atualizada"
    }
    ```

- **Mostrar solicitação de pagamento:**
    ```
    Header:
    GET /api/trip/payment/request/show/{id}
    Content-Type: application/json
    Authorization: Bearer {token}
    ```

- **Deletar solicitação de pagamento:**
    ```
    Header:
    DELETE /api/trip/payment/request/destroy/{id}
    Content-Type: application/json
    Authorization: Bearer {token}
    ```

### Conta Bancária

- **Listar contas bancárias:**
    ```
    Header:
    GET /api/bank/account/index
    Content-Type: application/json
    Authorization: Bearer {token}
    ```

- **Criar conta bancária:**
    ```
    Header:
    POST /api/bank/account/store/{owner_id}
    Content-Type: application/json
    Authorization: Bearer {token}
    
    Body:
    {
      "bank_branch": "6500",
      "bank_account_number": "123456789",
      "bank_account_digit": "1"
    }
    ```

- **Atualizar conta bancária:**
    ```
    Header:
    PUT /api/bank/account/update/{id}
    Content-Type: application/json
    Authorization: Bearer {token}
    
    Body:
    {
      "bank_branch": "6600",
      "bank_account_number": "0987654321",
      "bank_account_digit": "0"
    }
    ```

- **Mostrar conta bancária:**
    ```
    Header:
    GET /api/bank/account/show/{id}
    Content-Type: application/json
    Authorization: Bearer {token}
    ```

- **Deletar conta bancária:**
    ```
    Header:
    DELETE /api/bank/account/destroy/{id}
    Content-Type: application/json
    Authorization: Bearer {token}
    ```

## Referências

Para mais detalhes sobre o desafio técnico, consulte o documento a seguir:

[Desafio Técnico - Gerador de Corridas.pdf](Desafio_Tecnico_Gerador_de_Corridas.pdf)


## Notas Adicionais

- **Testes:** Para rodar os testes, utilize o comando:
    ```bash
    docker-compose exec app php artisan test
    ```

- **Desempenho:** Recomenda-se monitorar o desempenho e ajustar as configurações de acordo com o ambiente de produção para garantir eficiência e estabilidade.

- **Segurança:** Certifique-se de revisar e configurar adequadamente as permissões e políticas de segurança antes de colocar a aplicação em produção.

Esperamos que esta API RESTful implementada com Laravel 11 e PHP 8.2 atenda às necessidades e expectativas, proporcionando uma base sólida e escalável para futuros desenvolvimentos.
