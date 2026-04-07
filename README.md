# FreeAstroAPI 🚀

**FreeAstroAPI** is a **free, RESTful API** that provides astrology (daily and weekly horoscopes), numerology calculations, and spiritual insights in a simple, structured JSON format.  
This API is designed to be easy to integrate with web apps, mobile apps, chatbots, dashboards, and other services that need cosmic or mystical data.

## 🌌 Features

- 🔮 **Horoscope endpoints** for all zodiac signs (daily, weekly, monthly)
- 📊 **Numerology analysis** based on birth date or name
- 🪄 Optional caching layer (Redis) for faster response
- 🛡️ Optional JWT authentication
- 🧪 Well-structured code and REST best practices
- 📄 Auto-generated documentation with Swagger/OpenAPI

## 🧠 Why This Project?

This API is both a **fun, spiritual tech project** and a **learning platform** for backend engineers.  
It helps to apply:
- API architecture and REST design
- Database modeling and SQL optimization
- Caching strategies (Redis)
- Queue processing (RabbitMQ)
- Authentication and security
- Automated testing and CI/CD

## 🛠️ Technologies Used

- **Backend:** PHP (Laravel / Symfony) or your preferred stack
- **Database:** MySQL / PostgreSQL
- **Caching:** Redis
- **Queue:** RabbitMQ (optional)
- **Documentation:** Swagger / OpenAPI
- **DevOps:** Docker, GitHub Actions (CI/CD)

## 🚀 Quick Start

### Clone the repo

```bash
git clone https://github.com/yourusername/FreeAstroAPI.git
cd FreeAstroAPI
```

## ⚙️ Project Setup

### 1. Start Docker

```bash
docker compose up --build
```

### 2. Run Migrations

```bash
docker exec -it astro php bin/console doctrine:migrations:migrate
```

### 3. Seed Required Data

After running migrations, insert the required seed data below.

#### 🔮 Zodiac Signs

```sql
INSERT INTO zodiac (id, sign, start_date, end_date) VALUES
(UUID_TO_BIN(UUID()), 'Aries',       '2000-03-21', '2000-04-19'),
(UUID_TO_BIN(UUID()), 'Taurus',      '2000-04-20', '2000-05-20'),
(UUID_TO_BIN(UUID()), 'Gemini',      '2000-05-21', '2000-06-20'),
(UUID_TO_BIN(UUID()), 'Cancer',      '2000-06-21', '2000-07-22'),
(UUID_TO_BIN(UUID()), 'Leo',         '2000-07-23', '2000-08-22'),
(UUID_TO_BIN(UUID()), 'Virgo',       '2000-08-23', '2000-09-22'),
(UUID_TO_BIN(UUID()), 'Libra',       '2000-09-23', '2000-10-22'),
(UUID_TO_BIN(UUID()), 'Scorpio',     '2000-10-23', '2000-11-21'),
(UUID_TO_BIN(UUID()), 'Sagittarius', '2000-11-22', '2000-12-21'),
(UUID_TO_BIN(UUID()), 'Capricorn',   '2000-12-22', '2000-01-19'),
(UUID_TO_BIN(UUID()), 'Aquarius',    '2000-01-20', '2000-02-18'),
(UUID_TO_BIN(UUID()), 'Pisces',      '2000-02-19', '2000-03-20');
```

> The year used in dates is irrelevant — only the month and day are used to determine the zodiac sign.

#### 📊 Report Status

Based on `Domain/Types/ReportStatus.php`:

```sql
INSERT INTO report_status (id, description) VALUES
(1, 'PENDING'),
(2, 'PROCESSING'),
(3, 'COMPLETED'),
(4, 'FAILURE');
```

## 📈 Benchmark

| Metric | php -S | FrankenPHP | Gain |
|---|---:|---:|---:|
| P95 | 5354ms | 2106ms | -60% |
| Throughput | 43 req/s | 75.79 req/s | +76% |
| HTTP Errors | 33.16% | 0% | -100% |

With FrankenPHP worker mode under the same 500+ concurrent virtual users, throughput increased by **76%** (43 → 75.79 req/s) and P95 latency dropped by **60%** (5354ms → 2106ms) with **0% HTTP errors**.

Run the k6 load test against the API:

```bash
docker compose --profile benchmark run k6 run /scripts/benchmark_test.js
```

To pass a custom base URL or user ID:

```bash
docker compose --profile benchmark run k6 run -e BASE_URL=http://astro:8000 -e USER_ID=your-uuid /scripts/benchmark_test.js
```

Scripts are located in `docs/benchmark/`.
