import http from 'k6/http';
import { check, sleep } from 'k6';
import { htmlReport } from 'https://raw.githubusercontent.com/benc-uk/k6-reporter/main/dist/bundle.js';

export const options = {
    scenarios: {
        baseline: {
            executor: 'constant-vus',
            vus: 1,
            duration: '30s',
            tags: { scenario: 'baseline' },
        },
        load: {
            executor: 'ramping-vus',
            startVUs: 0,
            stages: [
                { duration: '30s', target: 10 },
                { duration: '1m',  target: 10 },
                { duration: '30s', target: 0  },
            ],
            tags: { scenario: 'load' },
        },
    },
    thresholds: {
        http_req_duration: ['p(95)<500'],
        http_req_failed: ['rate<0.01'],
    },
};

const BASE_URL = __ENV.BASE_URL || 'http://astro:8000';
const EMAIL    = __ENV.EMAIL    || 'ana.silva@email.com';
const PASSWORD = __ENV.PASSWORD || '123456';

export function setup() {
    const res = http.post(`${BASE_URL}/api/v1/token`, {
        grant_type: 'token',
        email: EMAIL,
        password: PASSWORD,
    });
    const token = res.json('access_token');
    return { token };
}

export default function ({ token }) {
    const params = {
        headers: { Authorization: `Bearer ${token}` },
    };

    const healthRes = http.get(`${BASE_URL}/health`);
    check(healthRes, {
        'health status 200': (r) => r.status === 200,
        'health response < 100ms': (r) => r.timings.duration < 100,
    });

    const reportRes = http.post(
        `${BASE_URL}/api/v1/report`,
        {
            user_id: __ENV.USER_ID || '019d457a-a891-7ac3-8796-40ea3a3b5f23',
            month: '6',
            year: '2025',
        },
        params
    );
    check(reportRes, {
        'report status 201': (r) => r.status === 201,
        'report response < 500ms': (r) => r.timings.duration < 500,
    });

    sleep(1);
}

export function handleSummary(data) {
    return {
        '/scripts/report.html': htmlReport(data),
        '/scripts/report.json': JSON.stringify(data, null, 2),
    };
}
