# 📈 Benchmark

## Results

| Metric | php -S | FrankenPHP | Gain |
|---|---:|---:|---:|
| P95 | 5354ms | 2106ms | -60% |
| Throughput | 43 req/s | 75.79 req/s | +76% |
| HTTP Errors | 33.16% | 0% | -100% |

With FrankenPHP worker mode under the same 500+ concurrent virtual users, throughput increased by **76%** (43 → 75.79 req/s) and P95 latency dropped by **60%** (5354ms → 2106ms) with **0% HTTP errors**.

## How to Run

Run the k6 load test against the API:

```bash
docker compose --profile benchmark run k6 run /scripts/benchmark_test.js
```

To pass a custom base URL or user ID:

```bash
docker compose --profile benchmark run k6 run -e BASE_URL=http://astro:8000 -e USER_ID=your-uuid /scripts/benchmark_test.js
```

After the test, reports are generated in this directory:
- `report.html` — visual HTML report
- `report.json` — raw JSON data
