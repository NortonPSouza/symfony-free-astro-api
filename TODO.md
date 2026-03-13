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
- [ ] **Endpoint de Requisição**
  - POST `/api/horoscope/monthly-report`
  - Parâmetros: `user_id`, `month`, `year`
  - Retorna: `request_id` (para tracking)

- [ ] **Fila de Processamento (RabbitMQ)**
  - Criar Producer: envia job para fila
  - Criar Consumer: processa relatório em background
  - Queue: `horoscope.monthly.report`

- [ ] **Geração do Relatório**
  - Buscar dados do usuário (signo)
  - Gerar previsões mensais
  - Compilar relatório em JSON/PDF

- [ ] **Log no MongoDB**
  - Collection: `horoscope_logs`
  - Campos:
    ```json
    {
      "request_id": "uuid",
      "user_id": 123,
      "zodiac_sign": "aries",
      "month": 12,
      "year": 2024,
      "status": "completed|failed|processing",
      "requested_at": "2024-01-15T10:30:00Z",
      "completed_at": "2024-01-15T10:30:05Z",
      "error_message": null
    }
    ```

- [ ] **Infraestrutura**
  - Configurar MongoDB connection
  - Criar `MongoLogRepository` (Adapter)
  - Criar `LogRepositoryInterface` (Port)
  - Configurar RabbitMQ connection
  - Criar `QueueService` (Adapter)

---

## 🏗️ Arquitetura Hexagonal

### Domain Layer
- [ ] Entity: `MonthlyReport`
- [ ] ValueObject: `ReportStatus`
- [ ] Service: `HoroscopeReportGenerator`

### Application Layer
- [ ] UseCase: `RequestMonthlyReportUseCase`
- [ ] UseCase: `ProcessMonthlyReportUseCase`
- [ ] Port Input: `RequestMonthlyReportInterface`
- [ ] Port Output: `LogRepositoryInterface`
- [ ] Port Output: `QueueServiceInterface`

### Infrastructure Layer
- [ ] Adapter Input: `HoroscopeController`
- [ ] Adapter Output: `MongoLogRepository`
- [ ] Adapter Output: `RabbitMQService`
- [ ] Consumer: `MonthlyReportConsumer`

---

## 🔧 Tarefas Técnicas

### Banco de Dados
- [ ] Migration: criar tabela `users` (se não existe)
- [ ] Configurar MongoDB para logs
- [ ] Índices no MongoDB para performance

### Mensageria
- [ ] Instalar RabbitMQ (Docker)
- [ ] Configurar exchanges e queues
- [ ] Criar worker para consumir fila

### Testes
- [ ] Unit tests: UserRepository (find, delete)
- [ ] Unit tests: MonthlyReportUseCase
- [ ] Integration tests: Fila + MongoDB
- [ ] E2E tests: Fluxo completo do relatório

### Documentação
- [ ] Swagger: documentar novos endpoints
- [ ] README: instruções de setup RabbitMQ + MongoDB
- [ ] Diagramas: fluxo da fila de relatórios

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

---

## 🚀 Prioridades

1. ✅ Completar gestão de usuários (find, delete)
2. 🔥 Implementar endpoint de requisição de relatório
3. 🔥 Configurar RabbitMQ e criar fila
4. 🔥 Implementar log no MongoDB
5. ⚡ Criar consumer para processar relatórios
6. 📝 Documentar no Swagger

---

## 📝 Notas

- Usar padrão Repository para MongoDB
- Logs devem ser assíncronos (não bloquear request)
- Relatório pode demorar, por isso usar fila
- Considerar retry policy para falhas na fila
- Adicionar rate limiting no endpoint de relatório
