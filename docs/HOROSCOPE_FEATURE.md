# 🔮 Feature Proposal — Horoscope CRUD com Agendamento Semanal

## Objetivo

Exercitar **deadlocks**, **transações** e **joins avançados** através de uma feature real no FreeAstroAPI.

A tabela `horoscope` já existe no schema mas não possui endpoints nem lógica. Esta feature preenche essa lacuna.

---

## O que faz

Um admin (com permissão `PUBLISH_HOROSCOPE`) cria/publica horóscopos semanais para cada signo. Usuários consultam o horóscopo do seu signo para a semana atual.

---

## Permissões

Controlado pela tabela `user_permission` + `permission_type`:

| ID | Permissão | Descrição |
|----|-----------|-----------|
| 1  | PUBLISH_HOROSCOPE | Pode criar e publicar horóscopos |
| 2  | COMMON_USER | Usuário padrão (consulta apenas) |

---

## Por que exercita cada conceito

### Transações
- Criar horóscopos da semana insere 12 registros (um por signo) atomicamente
- Se o de Libra falhar, nenhum pode ser salvo
- Campo `published` controla visibilidade (false → true), exigindo transação atômica nos 12 registros

### Deadlocks
- Dois admins tentam publicar horóscopos da mesma semana simultaneamente
- Consumer de report lê o horóscopo do signo enquanto o admin está atualizando
- Praticar `SELECT ... FOR UPDATE`, locks explícitos, detecção e retry

### Joins Avançados
- Horóscopo da semana atual do usuário → `user JOIN zodiac JOIN horoscope` com filtro por range de datas
- Histórico de horóscopos de um usuário → join com filtro por meses
- Signos sem horóscopo publicado → `LEFT JOIN` com `WHERE IS NULL`
- Ranking de números da sorte por signo no mês → `GROUP BY` com window functions

---

## Estrutura de Tabelas

```sql
-- Já existe (com campos adicionados: end_date, published)
horoscope (id, start_date, end_date, message, luck_number, published, zodiac_id)

-- Permissões (já criadas)
permission_type (id, description)
user_permission (id, user_id, permission_type_id)
```

Sem tabelas novas para horóscopo. Um "período" é representado pelo par `start_date` + `end_date` nos registros da `horoscope`.

---

## Endpoints

| Method | Route                                      | Auth | Permissão          | Descrição                                    |
|--------|--------------------------------------------|------|---------------------|----------------------------------------------|
| POST   | `/api/v1/horoscope/week`                   | ✓    | PUBLISH_HOROSCOPE   | Cria 12 entries da semana (transação)        |
| PUT    | `/api/v1/horoscope/week/{start_date}/publish` | ✓ | PUBLISH_HOROSCOPE   | Publica os 12 atomicamente (lock + transação)|
| GET    | `/api/v1/horoscope/me`                     | ✓    | COMMON_USER         | Horóscopo do usuário logado (joins)          |
| GET    | `/api/v1/horoscope/history?months=3`       | ✓    | COMMON_USER         | Histórico com paginação (joins)              |

---

## Cenários de Prática

### 1. Transação — Criar semana

```
BEGIN TRANSACTION
  INSERT horoscope (zodiac=Aries, start_date, end_date, published=false, ...)
  INSERT horoscope (zodiac=Taurus, ...)
  ... x12 (um por signo)
  Se qualquer entry falhar → ROLLBACK
COMMIT
```

### 2. Deadlock — Publish concorrente

```
-- Admin A                                    -- Admin B
BEGIN                                         BEGIN
SELECT ... FOR UPDATE                         SELECT ... FOR UPDATE  ← WAIT/DEADLOCK
  WHERE start_date = X AND end_date = Y        WHERE start_date = X AND end_date = Y
UPDATE published = true                       UPDATE published = true
COMMIT                                        COMMIT (ou retry após deadlock)
```

### 3. Joins — Horóscopo do usuário

```sql
SELECT h.message, h.luck_number, z.sign
FROM user u
JOIN zodiac z ON u.zodiac_id = z.id
JOIN horoscope h ON h.zodiac_id = z.id
WHERE u.id = :userId
  AND h.published = true
  AND h.start_date <= CURDATE()
  AND h.end_date >= CURDATE();
```

### 4. Joins — Histórico

```sql
SELECT h.start_date, h.end_date, h.message, h.luck_number
FROM horoscope h
JOIN zodiac z ON h.zodiac_id = z.id
JOIN user u ON u.zodiac_id = z.id
WHERE u.id = :userId
  AND h.published = true
  AND h.start_date >= DATE_SUB(CURDATE(), INTERVAL :months MONTH)
ORDER BY h.start_date DESC;
```

### 5. LEFT JOIN — Signos sem horóscopo na semana

```sql
SELECT z.sign
FROM zodiac z
LEFT JOIN horoscope h ON h.zodiac_id = z.id
  AND h.start_date = :startDate
  AND h.end_date = :endDate
WHERE h.id IS NULL;
```

### 6. Window Functions — Ranking luck_number

```sql
SELECT z.sign, h.luck_number,
       RANK() OVER (ORDER BY h.luck_number DESC) AS ranking
FROM horoscope h
JOIN zodiac z ON h.zodiac_id = z.id
WHERE h.published = true
  AND MONTH(h.start_date) = :month
  AND YEAR(h.start_date) = :year;
```
