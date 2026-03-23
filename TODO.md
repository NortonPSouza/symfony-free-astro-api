# TODO - FreeAstroAPI 📋

## 🎯 Funcionalidades Principais

### 1. Gestão de Usuários
- [x] **Create** - Cadastro de usuário
  - Nome, sobrenome, data/hora nascimento, signo
- [x] **Find** - Buscar usuário por ID
  - Implementar método `find()` no `UserRepository`
- [x] **Delete** - Remover usuário
  - Implementar método `delete()` no `UserRepository`

---

### 2. Relatório Mensal de Horóscopo (com Fila + Log MongoDB)
- [x] **Table Report (SQL)**
  - Campos:
    ```json
    {
      "process_id": "uuid",
      "user_id": 123,
      "month": 12,
      "year": 2024,
      "status": "completed|failed|processing",
      "requested_at": "2024-01-15T10:30:00Z",
      "completed_at": "2024-01-15T10:30:05Z"
    }
    ```
- [ ] **Endpoint de Requisição**
  - POST `/api/horoscope/monthly-report`
  - Parâmetros: `user_id`, `month`, `year`
  - Salva os dados na tabela `report` (flush)
  - Publica `process_id` na fila

- [ ] **Fila de Processamento (RabbitMQ)**
  - Instalar RabbitMQ
  - Criar uma interface EventReport...
  - Implementar a interface em infra (eventReportRabbitMq)
  - Injetar no useCaese de create Report
  - Criar Producer: envia job para fila
  - Criar Consumer: processa relatório em background
  - Queue: `horoscope.monthly.report`

- [ ] **Geração do Relatório**
  - Buscar dados do usuário (signo)
  - Gerar previsões mensais
  - Compilar relatório em JSON/PDF


---

## 📦 Dependências Necessárias

```bash
# MongoDB
composer require mongodb/mongodb

# RabbitMQ
composer require php-amqplib/php-amqplib

# UUID (para request_id)
composer require ramsey/uuid
```