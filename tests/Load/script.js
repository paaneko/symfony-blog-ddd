import http from 'k6/http';
import { check, sleep } from 'k6';

// Define test options
export const options = {
    stages: [
        { duration: '10s', target: 100 },  // Ramp up to 50 VUs
        { duration: '20s', target: 100 },   // Maintain 50 VUs
        { duration: '10s', target: 0 },   // Ramp down to 0 VUs
    ],
    thresholds: {
        // Define your thresholds here
        // Example: The http_req_duration (response time) should be below 200ms 95% of the time
        'http_req_duration': ['p(95)<1000'],
        // Example: The number of failed requests should be zero
        'http_req_failed': ['rate<0.01'],
    },
};

export default function () {
    const url = 'http://nginx/article/c3d5d67a-986e-4733-a433-3924af775a4b';

    // Send an HTTP GET request
    const res = http.get(url);

    // Check if the response status is 200
    check(res, { 'status was 200': (r) => r.status === 200 });

    // Add a sleep period between requests to simulate real user behavior
    sleep(0.1);
}