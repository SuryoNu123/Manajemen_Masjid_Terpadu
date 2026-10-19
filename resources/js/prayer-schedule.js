const prayerLabels = {
    subuh: 'Subuh',
    dzuhur: 'Dzuhur',
    ashar: 'Ashar',
    maghrib: 'Maghrib',
    isya: 'Isya',
};

export function findNextPrayer(schedulesByDate, now) {
    const candidates = [];

    for (const [date, schedules] of Object.entries(schedulesByDate)) {
        for (const schedule of schedules) {
            const [year, month, day] = date.split('-').map(Number);
            const [hour, minute] = schedule.time.split(':').map(Number);
            const prayerDate = new Date(year, month - 1, day, hour, minute, 0, 0);

            if (prayerDate > now) {
                candidates.push({ ...schedule, date, dateTime: prayerDate });
            }
        }
    }

    candidates.sort((first, second) => first.dateTime - second.dateTime);

    return candidates[0] ?? null;
}

function localDateString(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}

async function loadSchedules(date) {
    const response = await fetch(`/jadwal-sholat-harian?date=${encodeURIComponent(date)}`, {
        headers: { Accept: 'application/json' },
    });

    if (!response.ok) {
        throw new Error(`Schedule request failed (${response.status})`);
    }

    return response.json();
}

function renderScheduleList(schedules) {
    const names = Object.keys(prayerLabels);
    const schedulesByName = Object.fromEntries(schedules.map((schedule) => [schedule.name, schedule]));

    document.querySelectorAll('[data-prayer-row]').forEach((row) => row.remove());
    document.querySelectorAll('[data-prayer-card]').forEach((card) => card.remove());

    const rowList = document.querySelector('#prayer-schedule-list');
    const cardList = document.querySelector('#prayer-schedule-cards');

    for (const name of names) {
        const schedule = schedulesByName[name];

        if (!schedule) {
            continue;
        }

        const row = document.createElement('div');
        row.dataset.prayerRow = name;
        row.className = 'flex items-center justify-between px-4 py-3 rounded-xl bg-slate-50';
        row.innerHTML = `<span class="font-medium">${prayerLabels[name]}</span><span class="font-semibold">${schedule.time}</span>`;
        rowList.append(row);

        const card = document.createElement('div');
        card.dataset.prayerCard = name;
        card.className = 'bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-lg transition';
        card.innerHTML = `<div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center mb-5"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/><circle cx="12" cy="12" r="9" stroke-width="2"/></svg></div><p class="text-sm text-slate-500">${prayerLabels[name]}</p><p class="text-2xl font-bold mt-2">${schedule.time}</p>`;
        cardList.append(card);
    }

    document.querySelector('#prayer-schedule-empty').hidden = schedules.length > 0;
}

export function startPrayerScheduleClock() {
    const nameElement = document.querySelector('#next-prayer-name');

    if (!nameElement) {
        return;
    }

    const scheduleMap = {};
    let loadedDate = null;
    let loadingDate = null;

    async function refreshSchedules(today) {
        if (loadingDate === today) {
            return;
        }

        loadingDate = today;
        const tomorrowDate = new Date(`${today}T12:00:00`);
        tomorrowDate.setDate(tomorrowDate.getDate() + 1);
        const tomorrow = localDateString(tomorrowDate);

        try {
            const [todayResult, tomorrowResult] = await Promise.all([
                loadSchedules(today),
                loadSchedules(tomorrow),
            ]);

            scheduleMap[today] = todayResult.schedules;
            scheduleMap[tomorrow] = tomorrowResult.schedules;
            renderScheduleList(todayResult.schedules);
            nameElement.textContent = todayResult.schedules.length ? 'Memuat jadwal…' : 'Jadwal belum tersedia';
            loadedDate = today;
        } catch {
            nameElement.textContent = 'Jadwal tidak dapat dimuat';
            loadedDate = today;
        } finally {
            loadingDate = null;
        }
    }

    function updateClock() {
        const now = new Date();
        const today = localDateString(now);
        document.querySelector('#device-clock').textContent = new Intl.DateTimeFormat(undefined, {
            hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false,
        }).format(now);

        if (loadedDate !== today) {
            refreshSchedules(today);
        }

        const nextPrayer = scheduleMap[today]?.length ? findNextPrayer(scheduleMap, now) : null;
        document.querySelector('#next-prayer-time').textContent = nextPrayer?.time ?? '--:--';
        document.querySelector('#next-prayer-name').textContent = nextPrayer
            ? prayerLabels[nextPrayer.name] ?? nextPrayer.name
            : loadedDate === today ? 'Jadwal belum tersedia' : 'Memuat jadwal…';
        document.querySelector('#next-prayer-date').textContent = nextPrayer?.date === today
            ? 'Hari ini'
            : nextPrayer ? `Besok, ${new Intl.DateTimeFormat(undefined, { dateStyle: 'medium' }).format(nextPrayer.dateTime)}` : 'Jadwal sholat';
    }

    updateClock();
    window.setInterval(updateClock, 1000);
}
