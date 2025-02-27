import http from 'k6/http';
import { check, sleep } from 'k6';

export const options = {
  vus: 500,
  duration: '30s',
};

export default function () {
  const url = 'http://localhost:8080/src/api.php';
  const name = 'Doe';
  const email = `jmeter+${__VU}_${Date.now()}@example.com`;
  const body = `name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}`;

  const params = {
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
  };

  const res = http.post(url, body, params);
  check(res, {
    'réponse OK': (r) => r.status === 200,
  });

  sleep(1);
}