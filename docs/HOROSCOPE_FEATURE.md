# 🔮 Feature Proposal — Horoscope CRUD com Agendamento Semanal

## Objetivo

Exercitar **deadlocks**, **transações** e **joins avançados** através de uma feature real no FreeAstroAPI.

A tabela `horoscope` já existe no schema mas não possui endpoints nem lógica. Esta feature preenche essa lacuna.

---

## O que faz

Um admin cria/atualiza horóscopos semanais para cada signo. Usuários consultam o horóscopo do seu signo para a semana atual.

---

## Por que exercita cada conceito

### Transações
- Publicar horóscopos da semana insere/atualiza 12 registros (um por signo) atomicamente
- Se o de Libra falhar, nenhum pode ser salvo
- Tabela `horoscope_period` controla status (DRAFT → PUBLISHED), exigindo transação entre duas tabelas

### Deadlocks
- Dois admins tentam publicar horóscopos da mesma semana simultaneamente
- Consumer de report lê o horóscopo do signo enquanto o admin está atualizando
- Praticar `SELECT ... FOR UPDATE`, locks explícitos, detecção e retry

### Joins Avançados
- Horóscopo da semana atual do usuário → `user JOIN zodiac JOIN horoscope_entry JOIN horoscope_period` com filtro por range de datas
- Histórico de horóscopos de um usuário → join com subquery para pegar o mais recente por período
- Signos sem horóscopo publicado → `LEFT JOIN` com `WHERE IS NULL`
- Ranking de números da sorte por signo no mês → `GROUP BY` com `HAVING` e window functions

---

## Estrutura de Tabelas

```sql
-- Já existe
horoscope (id, start_date, message, luck_number, zodiac_id)

-- Novas
CREATE TABLE horoscope_period (
    id BINARY(16) PRIMARY KEY,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    status TINYINT NOT NULL DEFAULT 1,  -- 1=DRAFT, 2=PUBLISHED
    published_at DATETIME NULL,
    created_by BINARY(16) NOT NULL,     -- user_id do admin
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uk_period_dates (start_date, end_date),
    FOREIGN KEY (created_by) REFERENCES user(id)
);

CREATE TABLE horoscope_entry (
    id BINARY(16) PRIMARY KEY,
    period_id BINARY(16) NOT NULL,
    zodiac_id BINARY(16) NOT NULL,
    message TEXT NOT NULL,
    luck_number INT NOT NULL,
    compatibility_sign_id BINARY(16) NULL,
    UNIQUE KEY uk_entry_period_zodiac (period_id, zodiac_id),
    FOREIGN KEY (period_id) REFERENCES horoscope_period(id),
    FOREIGN KEY (zodiac_id) REFERENCES zodiac(id),
    FOREIGN KEY (compatibility_sign_id) REFERENCES zodiac(id)
);
```

---

## Endpoints

| Method | Route                                | Auth | Descrição                                      |
|--------|--------------------------------------|------|-------------------------------------------------|
| POST   | `/api/v1/horoscope/period`           | ✓    | Cria período com 12 entries (transação)         |
| PUT    | `/api/v1/horoscope/period/{id}/publish` | ✓ | Publica atomicamente (lock + transação)         |
| GET    | `/api/v1/horoscope/me`               | ✓    | Horóscopo do usuário logado (joins)             |
| GET    | `/api/v1/horoscope/history?months=3` | ✓    | Histórico com paginação (joins + subqueries)    |

---

## Cenários de Prática

### 1. Transação — Criar período
```
BEGIN TRANSACTION
  INSERT horoscope_period (status=DRAFT)
  INSERT horoscope_entry x12 (um por signo)
  Se qualquer entry falhar → ROLLBACK
COMMIT
```

### 2. Deadlock — Publish concorrente
```
-- Admin A                          -- Admin B
BEGIN                               BEGIN
SELECT ... FOR UPDATE               SELECT ... FOR UPDATE  ← WAIT/DEADLOCK
  WHERE period_id = X                 WHERE period_id = X
UPDATE status = PUBLISHED           UPDATE status = PUBLISHED
COMMIT                              COMMIT (ou retry após deadlock)
```

### 3. Joins — Horóscopo do usuário
```sql
SELECT he.message, he.luck_number, z2.sign AS compatibility
FROM user u
JOIN zodiac z ON u.zodiac_id = z.id
JOIN horoscope_entry he ON he.zodiac_id = z.id
JOIN horoscope_period hp ON he.period_id = hp.id
LEFT JOIN zodiac z2 ON he.compatibility_sign_id = z2.id
WHERE u.id = :userId
  AND hp.status = 2
  AND hp.start_date <= CURDATE()
  AND hp.end_date >= CURDATE();
```

### 4. Joins — Histórico com subquery
```sql
SELECT hp.start_date, hp.end_date, he.message, he.luck_number
FROM horoscope_entry he
JOIN horoscope_period hp ON he.period_id = hp.id
JOIN zodiac z ON he.zodiac_id = z.id
JOIN user u ON u.zodiac_id = z.id
WHERE u.id = :userId
  AND hp.status = 2
  AND hp.start_date >= DATE_SUB(CURDATE(), INTERVAL :months MONTH)
ORDER BY hp.start_date DESC;
```

### 5. LEFT JOIN — Signos sem horóscopo
```sql
SELECT z.sign
FROM zodiac z
LEFT JOIN horoscope_entry he ON he.zodiac_id = z.id
  AND he.period_id = :periodId
WHERE he.id IS NULL;
```

### 6. Window Functions — Ranking luck_number
```sql
SELECT z.sign, he.luck_number,
       RANK() OVER (ORDER BY he.luck_number DESC) AS ranking
FROM horoscope_entry he
JOIN horoscope_period hp ON he.period_id = hp.id
JOIN zodiac z ON he.zodiac_id = z.id
WHERE hp.status = 2
  AND MONTH(hp.start_date) = :month
  AND YEAR(hp.start_date) = :year;
```
