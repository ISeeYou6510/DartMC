const express = require('express');
const fs = require('fs');
const app = express();

app.use(express.urlencoded({ extended: true }));

app.get('/', (req, res) => {
    res.send(`
        <h2>Uwaga</h2>
        <p>Wejście zapisuje adres IP.</p>
        <form method="POST">
            <button>Akceptuję</button>
        </form>
    `);
});

app.post('/', (req, res) => {
    const ip = req.headers['x-forwarded-for']?.split(',')[0] || req.socket.remoteAddress;
    const ua = req.headers['user-agent'];
    const time = new Date().toISOString();

    fs.appendFileSync('ip_log.txt', `${time} | IP: ${ip} | UA: ${ua}\n`);
    res.send('Dostęp przyznany.');
});

app.listen(3000);
